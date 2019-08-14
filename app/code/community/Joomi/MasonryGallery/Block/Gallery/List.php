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
 * Gallery list block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Gallery_List
    extends Mage_Core_Block_Template {
    /**
     * initialize
     * @access public
     * @author Ultimate Module Creator
     */
     public function __construct(){
        parent::__construct();
         $galleries = Mage::getResourceModel('joomi_masonrygallery/gallery_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $galleries->setOrder('ordering', 'asc');
        $this->setGalleries($galleries);
    }
    /**
     * prepare the layout
     * @access protected
     * @return Joomi_MasonryGallery_Block_Gallery_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'joomi_masonrygallery.gallery.html.pager')
            ->setCollection($this->getGalleries());
        $this->setChild('pager', $pager);
        $this->getGalleries()->load();
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
}
