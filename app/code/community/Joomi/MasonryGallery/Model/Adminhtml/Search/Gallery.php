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
 * Admin search model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Adminhtml_Search_Gallery
    extends Varien_Object {
    /**
     * Load search results
     * @access public
     * @return Joomi_MasonryGallery_Model_Adminhtml_Search_Gallery
     * @author Ultimate Module Creator
     */
    public function load(){
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('joomi_masonrygallery/gallery_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $gallery) {
            $arr[] = array(
                'id'=> 'gallery/1/'.$gallery->getId(),
                'type'  => Mage::helper('joomi_masonrygallery')->__('Gallery'),
                'name'  => $gallery->getTitle(),
                'description'   => $gallery->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl('*/masonrygallery_gallery/edit', array('id'=>$gallery->getId())),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
