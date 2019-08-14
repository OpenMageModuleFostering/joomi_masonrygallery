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
 * Gallery - product relation model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Resource_Gallery_Product
    extends Mage_Core_Model_Resource_Db_Abstract {
    /**
     * initialize resource model
     * @access protected
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct(){
        $this->_init('joomi_masonrygallery/gallery_product', 'rel_id');
    }
    /**
     * Save gallery - product relations
     * @access public
     * @param Joomi_MasonryGallery_Model_Gallery $gallery
     * @param array $data
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Product
     * @author Ultimate Module Creator
     */
    public function saveGalleryRelation($gallery, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('gallery_id=?', $gallery->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $productId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'gallery_id'      => $gallery->getId(),
                'product_id'     => $productId,
                'position'      => @$info['position']
            ));
        }
        return $this;
    }
    /**
     * Save  product - gallery relations
     * @access public
     * @param Mage_Catalog_Model_Product $prooduct
     * @param array $data
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Product
     * @@author Ultimate Module Creator
     */
    public function saveProductRelation($product, $data){
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('product_id=?', $product->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $galleryId => $info) {
            $this->_getWriteAdapter()->insert($this->getMainTable(), array(
                'gallery_id' => $galleryId,
                'product_id' => $product->getId(),
                'position'   => @$info['position']
            ));
        }
        return $this;
    }
}
