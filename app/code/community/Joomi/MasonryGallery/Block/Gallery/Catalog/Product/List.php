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
 * Gallery product list
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Gallery_Catalog_Product_List extends Mage_Core_Block_Template {
    /**
     * get the list of products
     * @access public
     * @return Mage_Catalog_Model_Resource_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection() {
        $collection = $this->getGallery()->getSelectedProductsCollection();
        $collection->addAttributeToSelect('name');
        $collection->addUrlRewrite();
        $collection->getSelect()->order('related.position');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
        return $collection;
    }
    /**
     * get current gallery
     * @access public
     * @return Joomi_MasonryGallery_Model_Gallery
     * @author Ultimate Module Creator
     */
    public function getGallery() {
        return Mage::registry('current_gallery');
    }
}