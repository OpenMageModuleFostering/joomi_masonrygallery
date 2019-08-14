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
 * Gallery model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Gallery
    extends Mage_Core_Model_Abstract {
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'joomi_masonrygallery_gallery';
    const CACHE_TAG = 'joomi_masonrygallery_gallery';
    /**
     * Prefix of model events names
     * @var string
     */
    protected $_eventPrefix = 'joomi_masonrygallery_gallery';

    /**
     * Parameter name in event
     * @var string
     */
    protected $_eventObject = 'gallery';
    protected $_productInstance = null;
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct(){
        parent::_construct();
        $this->_init('joomi_masonrygallery/gallery');
    }
    /**
     * before save gallery
     * @access protected
     * @return Joomi_MasonryGallery_Model_Gallery
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
     * get the url to the gallery details page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGalleryUrl(){
        if ($this->getUrlKey()){
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('joomi_masonrygallery/gallery/url_prefix')){
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('joomi_masonrygallery/gallery/url_suffix')){
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('joomi_masonrygallery/gallery/view', array('id'=>$this->getId()));
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
     * get the gallery Description
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
     * save gallery relation
     * @access public
     * @return Joomi_MasonryGallery_Model_Gallery
     * @author Ultimate Module Creator
     */
    protected function _afterSave() {
        $this->getProductInstance()->saveGalleryRelation($this);
        return parent::_afterSave();
    }
    /**
     * get product relation model
     * @access public
     * @return Joomi_MasonryGallery_Model_Gallery_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance(){
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('joomi_masonrygallery/gallery_product');
        }
        return $this->_productInstance;
    }
    /**
     * get selected products array
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts(){
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }
    /**
     * Retrieve collection selected products
     * @access public
     * @return Joomi_MasonryGallery_Resource_Gallery_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection(){
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }
    /**
     * Retrieve parent 
     * @access public
     * @return null|Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function getParentCategory(){
        if (!$this->hasData('_parent_category')) {
            if (!$this->getCategoryId()) {
                return null;
            }
            else {
                $category = Mage::getModel('joomi_masonrygallery/category')->load($this->getCategoryId());
                if ($category->getId()) {
                    $this->setData('_parent_category', $category);
                }
                else {
                    $this->setData('_parent_category', null);
                }
            }
        }
        return $this->getData('_parent_category');
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
        $values['column_width'] = '200';
        $values['limit_first_load'] = '30';

        return $values;
    }
}
