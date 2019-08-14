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
 * Category list block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Ultimate Module Creator
     */
     public function __construct(){
        parent::__construct();
         $categories = Mage::getResourceModel('joomi_masonrygallery/category_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        ;
        $categories->getSelect()->order('main_table.position');
        $this->setCategories($categories);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Joomi_MasonryGallery_Block_Category_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $this->getCategories()->addFieldToFilter('level', 1);
        if ($this->_getDisplayMode() == 0){
            $pager = $this->getLayout()->createBlock('page/html_pager', 'joomi_masonrygallery.categories.html.pager')
                ->setCollection($this->getCategories());
            $this->setChild('pager', $pager);
            $this->getCategories()->load();
        }
        return $this;
    }
    /**
     * get the pager html
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml(){
        return $this->getChildHtml('pager');
    }
    /**
     * get the display mode
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode(){
        return Mage::getStoreConfigFlag('joomi_masonrygallery/category/tree');
    }
    /**
     * draw category
     * @access public
     * @param Joomi_MasonryGallery_Model_Category
     * @param int $level
     * @return int
     * @author Ultimate Module Creator
     */
    public function drawCategory($category, $level = 0){
        $html = '';
        $recursion = $this->getRecursion();
        if ($recursion !== '0' && $level >= $recursion){
            return '';
        }
        $storeIds = Mage::getResourceSingleton('joomi_masonrygallery/category')->lookupStoreIds($category->getId());
        $validStoreIds = array(0, Mage::app()->getStore()->getId());
        if (!array_intersect($storeIds, $validStoreIds)){
            return '';
        }
        if (!$category->getStatus()){
            return '';
        }
        $children = $category->getChildrenCategories();
        $activeChildren = array();
        if ($recursion == 0 || $level < $recursion-1){
            foreach ($children as $child) {
                $childStoreIds = Mage::getResourceSingleton('joomi_masonrygallery/category')->lookupStoreIds($child->getId());
                $validStoreIds = array(0, Mage::app()->getStore()->getId());
                if (!array_intersect($childStoreIds, $validStoreIds)){
                    continue;
                }
                if ($child->getStatus()) {
                    $activeChildren[] = $child;
                }
            }
        }
        $html .= '<li>';
        $html .= '<a href="'.$category->getCategoryUrl().'">'.$category->getTitle().'</a>';
        if (count($activeChildren) > 0) {
            $html .= '<ul>';
            foreach ($children as $child){
                $html .= $this->drawCategory($child, $level+1);
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
        return $html;
    }
    /**
     * get recursion
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRecursion(){
        if (!$this->hasData('recursion')){
            $this->setData('recursion', Mage::getStoreConfig('joomi_masonrygallery/category/recursion'));
        }
        return $this->getData('recursion');
    }
}
