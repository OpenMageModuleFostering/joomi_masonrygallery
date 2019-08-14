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
 * Category edit form tab
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Category_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('category_');
        $form->setFieldNameSuffix('category');
        $this->setForm($form);
        $fieldset = $form->addFieldset('category_form', array('legend'=>Mage::helper('joomi_masonrygallery')->__('Category')));
        $fieldset->addType('editor', Mage::getConfig()->getBlockClassName('joomi_masonrygallery/adminhtml_helper_wysiwyg'));
        if (!$this->getCategory()->getId()) {
            $parentId = $this->getRequest()->getParam('parent');
            if (!$parentId) {
                $parentId = Mage::helper('joomi_masonrygallery/category')->getRootCategoryId();
            }
            $fieldset->addField('path', 'hidden', array(
                'name'  => 'path',
                'value' => $parentId
            ));
        }
        else {
            $fieldset->addField('id', 'hidden', array(
                'name'  => 'id',
                'value' => $this->getCategory()->getId()
            ));
            $fieldset->addField('path', 'hidden', array(
                'name'  => 'path',
                'value' => $this->getCategory()->getPath()
            ));
        }

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('description', 'editor', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Description'),
            'name'  => 'description',

        ));

        $fieldset->addField('ordering', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Ordering'),
            'name'  => 'ordering',

        ));
        $fieldset->addField('url_key', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Url key'),
            'name'  => 'url_key',
            'note'    => Mage::helper('joomi_masonrygallery')->__('Relative to Website Base URL')
        ));
        $fieldset->addField('status', 'select', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Status'),
            'name'  => 'status',
            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('joomi_masonrygallery')->__('Enabled'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('joomi_masonrygallery')->__('Disabled'),
                ),
            ),
        ));
        if (Mage::app()->isSingleStoreMode()){
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            Mage::registry('current_category')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getCategory()->getData());
        return parent::_prepareForm();
    }
    /**
     * get the current category
     * @access public
     * @return Joomi_MasonryGallery_Model_Category
     */
    public function getCategory(){
        return Mage::registry('category');
    }
}
