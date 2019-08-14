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
 * Gallery product model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Gallery_Product
    extends Mage_Core_Model_Abstract {
    /**
     * Initialize resource
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct(){
        $this->_init('joomi_masonrygallery/gallery_product');
    }
    /**
     * Save data for gallery-product relation
     * @access public
     * @param  Joomi_MasonryGallery_Model_Gallery $gallery
     * @return Joomi_MasonryGallery_Model_Gallery_Product
     * @author Ultimate Module Creator
     */
    public function saveGalleryRelation($gallery){
        $data = $gallery->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->saveGalleryRelation($gallery, $data);
        }
        return $this;
    }
    /**
     * get products for gallery
     * @access public
     * @param Joomi_MasonryGallery_Model_Gallery $gallery
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($gallery){
        $collection = Mage::getResourceModel('joomi_masonrygallery/gallery_product_collection')
            ->addGalleryFilter($gallery);
        return $collection;
    }
}
