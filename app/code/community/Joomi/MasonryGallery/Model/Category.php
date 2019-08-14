<?php
/**
 * Joomi_MasonryGallery extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Joomi
 * @package        Joomi_MasonryGallery
 * @copyright      Copyright (c) 2016
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Category model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Category
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'joomi_masonrygallery_category';
    const CACHE_TAG = 'joomi_masonrygallery_category';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'joomi_masonrygallery_category';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'category';
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct(){
        parent::_construct();
        $this->_init('joomi_masonrygallery/category');
    }
    /**
     * before save category
     * @access protected
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    protected function _beforeSave(){
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()){
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }
    /**
     * get the url to the category details page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getCategoryUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('joomi_masonrygallery/category/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('joomi_masonrygallery/category/url_suffix')){
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('joomi_masonrygallery/category/view', array('id'=>$this->getId()));
    }
    /**
     * check URL key
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true){
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the category Description
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDescription(){
        $description = $this->getData('description');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($description);
        return $html;
    }
    /**
     * save category relation
     * @access public
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    protected function _afterSave() {
        return parent::_afterSave();
    }
    /**
     * Retrieve  collection
     * @access public
     * @return Joomi_MasonryGallery_Model_Gallery_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedGalleriesCollection(){
        if (!$this->hasData('_gallery_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            }
            else {
                $collection = Mage::getResourceModel('joomi_masonrygallery/gallery_collection')
                        ->addFieldToFilter('category_id', $this->getId());
                $this->setData('_gallery_collection', $collection);
            }
        }
        return $this->getData('_gallery_collection');
    }
    /**
     * get the tree model
     * @access public
     * @return Joomi_MasonryGallery_Model_Resource_Category_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModel(){
        return Mage::getResourceModel('joomi_masonrygallery/category_tree');
    }
    /**
     * get tree model instance
     * @access public
     * @return Joomi_MasonryGallery_Model_Resource_Category_Tree
     * @author Ultimate Module Creator
     */
    public function getTreeModelInstance(){
        if (is_null($this->_treeModel)) {
            $this->_treeModel = Mage::getResourceSingleton('joomi_masonrygallery/category_tree');
        }
        return $this->_treeModel;
    }
    /**
     * Move category
     * @access public
     * @param   int $parentId new parent category id
     * @param   int $afterCategoryId category id after which we have put current category
     * @return  Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function move($parentId, $afterCategoryId){
        $parent = Mage::getModel('joomi_masonrygallery/category')->load($parentId);
        if (!$parent->getId()) {
            Mage::throwException(
                Mage::helper('joomi_masonrygallery')->__('Category move operation is not possible: the new parent category was not found.')
            );
        }
        if (!$this->getId()) {
            Mage::throwException(
                Mage::helper('joomi_masonrygallery')->__('Category move operation is not possible: the current category was not found.')
            );
        }
        elseif ($parent->getId() == $this->getId()) {
            Mage::throwException(
                Mage::helper('joomi_masonrygallery')->__('Category move operation is not possible: parent category is equal to child category.')
            );
        }
        $this->setMovedCategoryId($this->getId());
        $eventParams = array(
            $this->_eventObject => $this,
            'parent'            => $parent,
            'category_id'       => $this->getId(),
            'prev_parent_id'    => $this->getParentId(),
            'parent_id'         => $parentId
        );
        $moveComplete = false;
        $this->_getResource()->beginTransaction();
        try {
            $this->getResource()->changeParent($this, $parent, $afterCategoryId);
            $this->_getResource()->commit();
            $this->setAffectedCategoryIds(array($this->getId(), $this->getParentId(), $parentId));
            $moveComplete = true;
        }
        catch (Exception $e) {
            $this->_getResource()->rollBack();
            throw $e;
        }
        if ($moveComplete) {
            Mage::app()->cleanCache(array(self::CACHE_TAG));
        }
        return $this;
    }
    /**
     * Get the parent category
     * @access public
     * @return  Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function getParentCategory(){
        if (!$this->hasData('parent_category')) {
            $this->setData('parent_category', Mage::getModel('joomi_masonrygallery/category')->load($this->getParentId()));
        }
        return $this->_getData('parent_category');
    }
    /**
     * Get the parent id
     * @access public
     * @return  int
     * @author Ultimate Module Creator
     */
    public function getParentId(){
        $parentIds = $this->getParentIds();
        return intval(array_pop($parentIds));
    }
    /**
     * Get all parent categories ids
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentIds(){
        return array_diff($this->getPathIds(), array($this->getId()));
    }
    /**
     * Get all categories children
     * @access public
     * @param bool $asArray
     * @return mixed (array|string)
     * @author Ultimate Module Creator
     */
    public function getAllChildren($asArray = false){
        $children = $this->getResource()->getAllChildren($this);
        if ($asArray) {
            return $children;
        }
        else {
            return implode(',', $children);
        }
    }
    /**
     * Get all categories children
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getChildCategories(){
        return implode(',', $this->getResource()->getChildren($this, false));
    }
    /**
     * check the id
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function checkId($id){
        return $this->_getResource()->checkId($id);
    }
    /**
     * Get array categories ids which are part of category path
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getPathIds(){
        $ids = $this->getData('path_ids');
        if (is_null($ids)) {
            $ids = explode('/', $this->getPath());
            $this->setData('path_ids', $ids);
        }
        return $ids;
    }
    /**
     * Retrieve level
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getLevel(){
        if (!$this->hasLevel()) {
            return count(explode('/', $this->getPath())) - 1;
        }
        return $this->getData('level');
    }
    /**
     * Verify category ids
     * @access public
     * @param array $ids
     * @return bool
     * @author Ultimate Module Creator
     */
    public function verifyIds(array $ids){
        return $this->getResource()->verifyIds($ids);
    }
    /**
     * check if category has children
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function hasChildren(){
        return $this->_getResource()->getChildrenAmount($this) > 0;
    }
    /**
     * check if category can be deleted
     * @access protected
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    protected function _beforeDelete(){
        if ($this->getResource()->isForbiddenToDelete($this->getId())) {
            Mage::throwException(Mage::helper('joomi_masonrygallery')->__("Can't delete root category."));
        }
        return parent::_beforeDelete();
    }
    /**
     * get the categories
     * @access public
     * @param Joomi_MasonryGallery_Model_Category $parent
     * @param int $recursionLevel
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     * @author Ultimate Module Creator
     */
    public function getCategories($parent, $recursionLevel = 0, $sorted=false, $asCollection=false, $toLoad=true){
        return $this->getResource()->getCategories($parent, $recursionLevel, $sorted, $asCollection, $toLoad);
    }
    /**
     * Return parent categories of current category
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getParentCategories(){
        return $this->getResource()->getParentCategories($this);
    }
    /**
     * Retuen children categories of current category
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getChildrenCategories(){
        return $this->getResource()->getChildrenCategories($this);
    }
    /**
     * check if parents are enabled
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getStatusPath(){
        $parents = $this->getParentCategories();
        $rootId = Mage::helper('joomi_masonrygallery/category')->getRootCategoryId();
        foreach ($parents as $parent){
            if ($parent->getId() == $rootId) {
                continue;
            }
            if (!$parent->getStatus()){
                return false;
            }
        }
        return $this->getStatus();
    }
    /**
     * get default values
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues() {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
}
