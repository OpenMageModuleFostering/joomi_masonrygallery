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
 * Category front contrller
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_CategoryController
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
         if (Mage::helper('joomi_masonrygallery/category')->getUseBreadcrumbs()){
             if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                 $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                 );
                 $breadcrumbBlock->addCrumb('categories', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Categories'),
                            'link'    => '',
                    )
                 );
             }
         }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('joomi_masonrygallery/category/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('joomi_masonrygallery/category/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('joomi_masonrygallery/category/meta_description'));
        }
        $this->renderLayout();
    }
    /**
     * init Category
     * @access protected
     * @return Joomi_MasonryGallery_Model_Entity
     * @author Ultimate Module Creator
     */
    protected function _initCategory(){
        $categoryId   = $this->getRequest()->getParam('id', 0);
        $category     = Mage::getModel('joomi_masonrygallery/category')
                        ->setStoreId(Mage::app()->getStore()->getId())
                        ->load($categoryId);
        if (!$category->getId()){
            return false;
        }
        elseif (!$category->getStatus()){
            return false;
        }
        return $category;
    }
    /**
      * view category action
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function viewAction(){
        $category = $this->_initCategory();
        if (!$category) {
            $this->_forward('no-route');
            return;
        }
        if (!$category->getStatusPath()){
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_category', $category);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('masonrygallery-category masonrygallery-category' . $category->getId());
        }
        if (Mage::helper('joomi_masonrygallery/category')->getUseBreadcrumbs()){
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')){
                $breadcrumbBlock->addCrumb('home', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Home'),
                            'link'     => Mage::getUrl(),
                        )
                );
                $breadcrumbBlock->addCrumb('categories', array(
                            'label'    => Mage::helper('joomi_masonrygallery')->__('Categories'),
                            'link'    => Mage::helper('joomi_masonrygallery/category')->getCategoriesUrl(),
                    )
                );
                $parents = $category->getParentCategories();
                foreach ($parents as $parent){
                    if ($parent->getId() != Mage::helper('joomi_masonrygallery/category')->getRootCategoryId() && $parent->getId() != $category->getId()){
                        $breadcrumbBlock->addCrumb('category-'.$parent->getId(), array(
                                'label'    => $parent->getTitle(),
                                'link'    => $link = $parent->getCategoryUrl(),
                        ));
                    }
                }
                $breadcrumbBlock->addCrumb('category', array(
                            'label'    => $category->getTitle(),
                            'link'    => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            if ($category->getMetaTitle()){
                $headBlock->setTitle($category->getMetaTitle());
            }
            else{
                $headBlock->setTitle($category->getTitle());
            }
            $headBlock->setKeywords($category->getMetaKeywords());
            $headBlock->setDescription($category->getMetaDescription());
        }
        $this->renderLayout();
    }
}
