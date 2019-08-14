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
 * Category admin edit tabs
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        $this->setId('category_info_tabs');
        $this->setDestElementId('category_tab_content');
        $this->setTitle(Mage::helper('joomi_masonrygallery')->__('Category'));
        $this->setTemplate('widget/tabshoriz.phtml');
    }
    /**
     * Prepare Layout Content
     * @access public
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Tabs
     */
    protected function _prepareLayout(){
        $this->addTab('form_category', array(
            'label'        => Mage::helper('joomi_masonrygallery')->__('Category'),
            'title'        => Mage::helper('joomi_masonrygallery')->__('Category'),
            'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_edit_tab_form')->toHtml(),
        ));
        $this->addTab('form_meta_category', array(
            'label'        => Mage::helper('joomi_masonrygallery')->__('Meta'),
            'title'        => Mage::helper('joomi_masonrygallery')->__('Meta'),
            'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_edit_tab_meta')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_category', array(
                'label'        => Mage::helper('joomi_masonrygallery')->__('Store views'),
                'title'        => Mage::helper('joomi_masonrygallery')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_edit_tab_stores')->toHtml(),
            ));
        }
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve category entity
     * @access public
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function getCategory(){
        return Mage::registry('current_category');
    }
}
