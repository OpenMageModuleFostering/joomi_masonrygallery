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
 * Category admin widget controller
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Adminhtml_Masonrygallery_Category_WidgetController
    extends Mage_Adminhtml_Controller_Action {
    /**
     * Chooser Source action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function chooserAction(){
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_widget_chooser', '', array(
            'id' => $uniqId,
        ));
        $this->getResponse()->setBody($grid->toHtml());
    }
    /**
     * categories json action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesJsonAction(){
        if ($categoryId = (int) $this->getRequest()->getPost('id')) {
            $category = Mage::getModel('joomi_masonrygallery/category')->load($categoryId);
            if ($category->getId()) {
                Mage::register('category', $category);
                Mage::register('current_category', $category);
            }
            $this->getResponse()->setBody(
                $this->_getCategoryTreeBlock()->getTreeJson($category)
            );
        }
    }
    /**
     * get category tree block
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Widget_Chooser
     * @author Ultimate Module Creator
     */
    protected function _getCategoryTreeBlock(){
        return $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_widget_chooser', '', array(
            'id' => $this->getRequest()->getParam('uniq_id'),
            'use_massaction' => $this->getRequest()->getParam('use_massaction', false)
        ));
    }
}
