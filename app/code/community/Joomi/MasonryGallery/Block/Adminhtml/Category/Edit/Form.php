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
 * Category edit form
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */


class Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Form
    extends Joomi_MasonryGallery_Block_Adminhtml_Category_Abstract {

    /**
     * Additional buttons on category page
     * @var array
     */
    protected $_additionalButtons = array();
    /**
     * constructor
     * set template
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        parent::__construct();
        $this->setTemplate('joomi_masonrygallery/category/edit/form.phtml');
    }
    /**
     * prepare the layout
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout() {
        $category = $this->getCategory();
        $categoryId = (int)$category->getId();
        $this->setChild('tabs',
            $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_category_edit_tabs', 'tabs')
        );
        $this->setChild('save_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('joomi_masonrygallery')->__('Save Category'),
                    'onclick'   => "categorySubmit('" . $this->getSaveUrl() . "', true)",
                    'class' => 'save'
            ))
        );
        // Delete button
        if (!in_array($categoryId, $this->getRootIds())) {
            $this->setChild('delete_button',
                $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setData(array(
                        'label' => Mage::helper('joomi_masonrygallery')->__('Delete Category'),
                        'onclick'   => "categoryDelete('" . $this->getUrl('*/*/delete', array('_current' => true)) . "', true, {$categoryId})",
                        'class' => 'delete'
                ))
            );
        }

        // Reset button
        $resetPath = $category ? '*/*/edit' : '*/*/add';
        $this->setChild('reset_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label' => Mage::helper('joomi_masonrygallery')->__('Reset'),
                    'onclick'   => "categoryReset('".$this->getUrl($resetPath, array('_current'=>true))."',true)"
            ))
        );
        return parent::_prepareLayout();
    }
    /**
     * get html for delete button
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDeleteButtonHtml() {
        return $this->getChildHtml('delete_button');
    }
    /**
     * get html for save button
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getSaveButtonHtml() {
        return $this->getChildHtml('save_button');
    }
    /**
     * get html for reset button
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getResetButtonHtml() {
        return $this->getChildHtml('reset_button');
    }
    /**
     * Retrieve additional buttons html
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getAdditionalButtonsHtml() {
        $html = '';
        foreach ($this->_additionalButtons as $childName) {
            $html .= $this->getChildHtml($childName);
        }
        return $html;
    }

    /**
     * Add additional button
     *
     * @param string $alias
     * @param array $config
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Form
     * @author Ultimate Module Creator
     */
    public function addAdditionalButton($alias, $config){
        if (isset($config['name'])) {
            $config['element_name'] = $config['name'];
        }
        $this->setChild($alias . '_button',
        $this->getLayout()->createBlock('adminhtml/widget_button')->addData($config));
        $this->_additionalButtons[$alias] = $alias . '_button';
        return $this;
    }
    /**
     * Remove additional button
     * @access public
     * @param string $alias
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Form
     * @author Ultimate Module Creator
     */
    public function removeAdditionalButton($alias) {
        if (isset($this->_additionalButtons[$alias])) {
            $this->unsetChild($this->_additionalButtons[$alias]);
            unset($this->_additionalButtons[$alias]);
        }
        return $this;
    }
    /**
     * get html for tabs
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getTabsHtml() {
        return $this->getChildHtml('tabs');
    }
    /**
     * get the form header
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeader() {
        if ($this->getCategoryId()) {
            return $this->getCategoryTitle();
        }
        else {
            return Mage::helper('joomi_masonrygallery')->__('New Root Category');
        }
    }
    /**
     * get the delete url
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDeleteUrl(array $args = array()) {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/delete', $params);
    }
    /**
     * Return URL for refresh input element 'path' in form
     * @access public
     * @param array $args
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRefreshPathUrl(array $args = array()) {
        $params = array('_current'=>true);
        $params = array_merge($params, $args);
        return $this->getUrl('*/*/refreshPath', $params);
    }
    /**
     * check if request is ajax
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isAjax() {
        return Mage::app()->getRequest()->isXmlHttpRequest() || Mage::app()->getRequest()->getParam('isAjax');
    }
}
