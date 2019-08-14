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
 * Gallery - product relation resource model collection
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Resource_Gallery_Product_Collection
    extends Mage_Catalog_Model_Resource_Product_Collection {
    /**
     * remember if fields have been joined
     * @var bool
     */
    protected $_joinedFields = false;
    /**
     * join the link table
     * @access public
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Product_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields(){
        if (!$this->_joinedFields){
            $this->getSelect()->join(
                array('related' => $this->getTable('joomi_masonrygallery/gallery_product')),
                'related.product_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }
    /**
     * add gallery filter
     * @access public
     * @param Joomi_MasonryGallery_Model_Gallery | int $gallery
     * @return Joomi_MasonryGallery_Model_Resource_Gallery_Product_Collection
     * @author Ultimate Module Creator
     */
    public function addGalleryFilter($gallery){
        if ($gallery instanceof Joomi_MasonryGallery_Model_Gallery){
            $gallery = $gallery->getId();
        }
        if (!$this->_joinedFields){
            $this->joinFields();
        }
        $this->getSelect()->where('related.gallery_id = ?', $gallery);
        return $this;
    }
}
