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
 * Gallery admin edit tabs
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Gallery_Edit_Tabs
    extends Mage_Adminhtml_Block_Widget_Tabs {
    /**
     * Initialize Tabs
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        parent::__construct();
        $this->setId('gallery_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('joomi_masonrygallery')->__('Gallery'));
    }
    /**
     * before render html
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml(){
        $this->addTab('form_gallery', array(
            'label'        => Mage::helper('joomi_masonrygallery')->__('Gallery'),
            'title'        => Mage::helper('joomi_masonrygallery')->__('Gallery'),
            'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_edit_tab_form')->toHtml(),
        ));
        $this->addTab('form_meta_gallery', array(
            'label'        => Mage::helper('joomi_masonrygallery')->__('Meta'),
            'title'        => Mage::helper('joomi_masonrygallery')->__('Meta'),
            'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_edit_tab_meta')->toHtml(),
        ));
        if (!Mage::app()->isSingleStoreMode()){
            $this->addTab('form_store_gallery', array(
                'label'        => Mage::helper('joomi_masonrygallery')->__('Store views'),
                'title'        => Mage::helper('joomi_masonrygallery')->__('Store views'),
                'content'     => $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_edit_tab_stores')->toHtml(),
            ));
        }
        $this->addTab('products', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Associated products'),
            'url'   => $this->getUrl('*/*/products', array('_current' => true)),
            'class'    => 'ajax'
        ));
        return parent::_beforeToHtml();
    }
    /**
     * Retrieve gallery entity
     * @access public
     * @return Joomi_MasonryGallery_Model_Gallery
     * @author Ultimate Module Creator
     */
    public function getGallery(){
        return Mage::registry('current_gallery');
    }
}
