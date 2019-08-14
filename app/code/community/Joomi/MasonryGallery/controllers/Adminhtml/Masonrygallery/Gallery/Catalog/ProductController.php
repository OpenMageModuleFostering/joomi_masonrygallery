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
 * Gallery - product controller
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Joomi_MasonryGallery_Adminhtml_Masonrygallery_Gallery_Catalog_ProductController
    extends Mage_Adminhtml_Catalog_ProductController {
    /**
     * construct
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct(){
        // Define module dependent translate
        $this->setUsedModuleName('Joomi_MasonryGallery');
    }
    /**
     * galleries in the catalog page
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function galleriesAction(){
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.gallery')
            ->setProductGalleries($this->getRequest()->getPost('product_galleries', null));
        $this->renderLayout();
    }
    /**
     * galleries grid in the catalog page
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function galleriesGridAction(){
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.gallery')
            ->setProductGalleries($this->getRequest()->getPost('product_galleries', null));
        $this->renderLayout();
    }
}
