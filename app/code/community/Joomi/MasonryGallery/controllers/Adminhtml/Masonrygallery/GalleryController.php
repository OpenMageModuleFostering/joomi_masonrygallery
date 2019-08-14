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
 * Gallery admin controller
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Adminhtml_Masonrygallery_GalleryController
    extends Joomi_MasonryGallery_Controller_Adminhtml_MasonryGallery {
    /**
     * init the gallery
     * @access protected
     * @return Joomi_MasonryGallery_Model_Gallery
     */
    protected function _initGallery(){
        $galleryId  = (int) $this->getRequest()->getParam('id');
        $gallery    = Mage::getModel('joomi_masonrygallery/gallery');
        if ($galleryId) {
            $gallery->load($galleryId);
        }
        Mage::register('current_gallery', $gallery);
        return $gallery;
    }
     /**
     * default action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction() {
        $this->loadLayout();
        $this->_title(Mage::helper('joomi_masonrygallery')->__('Masonry Gallery'))
             ->_title(Mage::helper('joomi_masonrygallery')->__('Galleries'));
        $this->renderLayout();
    }
    /**
     * grid action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction() {
        $this->loadLayout()->renderLayout();
    }
    /**
     * edit gallery - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction() {
        $galleryId    = $this->getRequest()->getParam('id');
        $gallery      = $this->_initGallery();
        if ($galleryId && !$gallery->getId()) {
            $this->_getSession()->addError(Mage::helper('joomi_masonrygallery')->__('This gallery no longer exists.'));
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getGalleryData(true);
        if (!empty($data)) {
            $gallery->setData($data);
        }
        Mage::register('gallery_data', $gallery);
        $this->loadLayout();
        $this->_title(Mage::helper('joomi_masonrygallery')->__('Masonry Gallery'))
             ->_title(Mage::helper('joomi_masonrygallery')->__('Galleries'));
        if ($gallery->getId()){
            $this->_title($gallery->getTitle());
        }
        else{
            $this->_title(Mage::helper('joomi_masonrygallery')->__('Add gallery'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }
    /**
     * new gallery action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction() {
        $this->_forward('edit');
    }
    /**
     * save gallery - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction() {
        if ($data = $this->getRequest()->getPost('gallery')) {
            try {
                $gallery = $this->_initGallery();
                $gallery->addData($data);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $gallery->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $gallery->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('joomi_masonrygallery')->__('Gallery was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $gallery->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setGalleryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
            catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was a problem saving the gallery.'));
                Mage::getSingleton('adminhtml/session')->setGalleryData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Unable to find gallery to save.'));
        $this->_redirect('*/*/');
    }
    /**
     * delete gallery - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction() {
        if( $this->getRequest()->getParam('id') > 0) {
            try {
                $gallery = Mage::getModel('joomi_masonrygallery/gallery');
                $gallery->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('joomi_masonrygallery')->__('Gallery was successfully deleted.'));
                $this->_redirect('*/*/');
                return;
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error deleting gallery.'));
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Could not find gallery to delete.'));
        $this->_redirect('*/*/');
    }
    /**
     * mass delete gallery - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction() {
        $galleryIds = $this->getRequest()->getParam('gallery');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Please select galleries to delete.'));
        }
        else {
            try {
                foreach ($galleryIds as $galleryId) {
                    $gallery = Mage::getModel('joomi_masonrygallery/gallery');
                    $gallery->setId($galleryId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('joomi_masonrygallery')->__('Total of %d galleries were successfully deleted.', count($galleryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error deleting galleries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass status change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction(){
        $galleryIds = $this->getRequest()->getParam('gallery');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Please select galleries.'));
        }
        else {
            try {
                foreach ($galleryIds as $galleryId) {
                $gallery = Mage::getSingleton('joomi_masonrygallery/gallery')->load($galleryId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d galleries were successfully updated.', count($galleryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error updating galleries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Thumbnail change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massThumbnailAction(){
        $galleryIds = $this->getRequest()->getParam('gallery');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Please select galleries.'));
        }
        else {
            try {
                foreach ($galleryIds as $galleryId) {
                $gallery = Mage::getSingleton('joomi_masonrygallery/gallery')->load($galleryId)
                            ->setThumbnail($this->getRequest()->getParam('flag_thumbnail'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d galleries were successfully updated.', count($galleryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error updating galleries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass Big Image change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massBigImageAction(){
        $galleryIds = $this->getRequest()->getParam('gallery');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Please select galleries.'));
        }
        else {
            try {
                foreach ($galleryIds as $galleryId) {
                $gallery = Mage::getSingleton('joomi_masonrygallery/gallery')->load($galleryId)
                            ->setBigImage($this->getRequest()->getParam('flag_big_image'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d galleries were successfully updated.', count($galleryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error updating galleries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * mass category change - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massCategoryIdAction(){
        $galleryIds = $this->getRequest()->getParam('gallery');
        if(!is_array($galleryIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('Please select galleries.'));
        }
        else {
            try {
                foreach ($galleryIds as $galleryId) {
                $gallery = Mage::getSingleton('joomi_masonrygallery/gallery')->load($galleryId)
                            ->setCategoryId($this->getRequest()->getParam('flag_category_id'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess($this->__('Total of %d galleries were successfully updated.', count($galleryIds)));
            }
            catch (Mage_Core_Exception $e){
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
            catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('joomi_masonrygallery')->__('There was an error updating galleries.'));
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }
    /**
     * get grid of products action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction(){
        $this->_initGallery();
        $this->loadLayout();
        $this->getLayout()->getBlock('gallery.edit.tab.product')
            ->setGalleryProducts($this->getRequest()->getPost('gallery_products', null));
        $this->renderLayout();
    }
    /**
     * get grid of products action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction(){
        $this->_initGallery();
        $this->loadLayout();
        $this->getLayout()->getBlock('gallery.edit.tab.product')
            ->setGalleryProducts($this->getRequest()->getPost('gallery_products', null));
        $this->renderLayout();
    }
    /**
     * export as csv - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction(){
        $fileName   = 'gallery.csv';
        $content    = $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_grid')->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as MsExcel - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction(){
        $fileName   = 'gallery.xls';
        $content    = $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_grid')->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * export as xml - action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction(){
        $fileName   = 'gallery.xml';
        $content    = $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_grid')->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }
    /**
     * Check if admin has permissions to visit related pages
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed() {
        return Mage::getSingleton('admin/session')->isAllowed('joomi_masonrygallery/gallery');
    }
}
