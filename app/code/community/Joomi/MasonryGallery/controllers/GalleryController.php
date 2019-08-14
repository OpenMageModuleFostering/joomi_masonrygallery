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
 * Gallery front contrller
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_GalleryController
    extends Mage_Core_Controller_Front_Action {
    /**
      * default action
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction(){
         $this->loadLayout();
         $this->_initLayoutMessages('catalog/session');
         $this->_initLayoutMessages('customer/session');
         $this->_initLayoutMessages('checkout/session');
         if (Mage::helper('joomi_masonrygallery/gallery')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('galleries', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Galleries'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('joomi_masonrygallery/gallery/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('joomi_masonrygallery/gallery/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('joomi_masonrygallery/gallery/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Gallery
     * @access protected
     * @return Joomi_MasonryGallery_Model_Entity
     * @author Ultimate Module Creator
     */
    protected function _initGallery(){
        $galleryId   = $this->getRequest()->getParam('id', 0);
        $gallery     = Mage::getModel('joomi_masonrygallery/gallery')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($galleryId);
        if (!$gallery->getId()){
            return false;
        }
        elseif (!$gallery->getStatus()){
            return false;
        }
        return $gallery;
    }
    /**
      * view gallery action
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function viewAction(){
        $gallery = $this->_initGallery();
        if (!$gallery) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_gallery', $gallery);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('masonrygallery-gallery masonrygallery-gallery' . $gallery->getId());
        }
        if (Mage::helper('joomi_masonrygallery/gallery')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('galleries', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Galleries'),
                            'link'    => Mage::helper('joomi_masonrygallery/gallery')->getGalleriesUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb('gallery', array(
                            'label'    => $gallery->getTitle(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($gallery->getMetaTitle()){
                $headBlock->setTitle($gallery->getMetaTitle());
            }
            else{
                $headBlock->setTitle($gallery->getTitle());
            }
            $headBlock->setKeywords($gallery->getMetaKeywords());
            $headBlock->setDescription($gallery->getMetaDescription());
        }
        $this->renderLayout();
    }
}
