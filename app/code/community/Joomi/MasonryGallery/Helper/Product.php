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
 * Product helper
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Helper_Product
    extends Joomi_MasonryGallery_Helper_Data {
    /**
     * get the selected galleries for a product
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedGalleries(Mage_Catalog_Model_Product $product){
        if (!$product->hasSelectedGalleries()) {
            $galleries = array();
            foreach ($this->getSelectedGalleriesCollection($product) as $gallery) {
                $galleries[] = $gallery;
            }
            $product->setSelectedGalleries($galleries);
        }
        return $product->getData('selected_galleries');
    }
    /**
     * get gallery collection for a product
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedGalleriesCollection(Mage_Catalog_Model_Product $product){
        $collection = Mage::getResourceSingleton('joomi_masonrygallery/gallery_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
