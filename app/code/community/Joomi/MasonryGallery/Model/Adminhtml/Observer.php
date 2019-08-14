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
 * Adminhtml observer
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Adminhtml_Observer {
    /**
     * check if tab can be added
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product){
        if ($product->getId()){
            return true;
        }
        if (!$product->getAttributeSetId()){
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable'){
            if ($request->getParam('attributes')){
                return true;
            }
        }
        return false;
    }
    /**
     * add the gallery tab to products
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Joomi_MasonryGallery_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductGalleryBlock($observer){
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)){
            $block->addTab('galleries', array(
                'label' => Mage::helper('joomi_masonrygallery')->__('Galleries'),
                'url'   => Mage::helper('adminhtml')->getUrl('adminhtml/masonrygallery_gallery_catalog_product/galleries', array('_current' => true)),
                'class' => 'ajax',
            ));
        }
        return $this;
    }
    /**
     * save gallery - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Joomi_MasonryGallery_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductGalleryData($observer){
        $post = Mage::app()->getRequest()->getPost('galleries', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $galleryProduct = Mage::getResourceSingleton('joomi_masonrygallery/gallery_product')->saveProductRelation($product, $post);
        }
        return $this;
    }}
