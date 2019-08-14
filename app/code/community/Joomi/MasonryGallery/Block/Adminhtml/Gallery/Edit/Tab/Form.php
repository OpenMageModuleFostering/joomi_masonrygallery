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
 * Gallery edit form tab
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Gallery_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form {
    /**
     * prepare the form
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm(){
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('gallery_');
        $form->setFieldNameSuffix('gallery');
        $this->setForm($form);
        $fieldset = $form->addFieldset('gallery_form', array('legend'=>Mage::helper('joomi_masonrygallery')->__('Gallery')));
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();
        $values = Mage::getResourceModel('joomi_masonrygallery/category_collection')->toOptionArray();
        array_unshift($values, array('label'=>'', 'value'=>''));

        $html = '<a href="{#url}" id="gallery_category_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeCategoryIdLink(){
                if ($(\'gallery_category_id\').value == \'\') {
                    $(\'gallery_category_id_link\').hide();
                }
                else {
                    $(\'gallery_category_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/masonrygallery_category/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'gallery_category_id\').value);
                    $(\'gallery_category_id_link\').href = realUrl;
                    $(\'gallery_category_id_link\').innerHTML = text.replace(\'{#name}\', $(\'gallery_category_id\').options[$(\'gallery_category_id\').selectedIndex].innerHTML);
                }
            }
            $(\'gallery_category_id\').observe(\'change\', changeCategoryIdLink);
            changeCategoryIdLink();
            </script>';

        $fieldset->addField('category_id', 'select', array(
            'label'     => Mage::helper('joomi_masonrygallery')->__('Category'),
            'name'      => 'category_id',
            'required'  => false,
            'values'    => $values,
            'after_element_html' => $html
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Title'),
            'name'  => 'title',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('description', 'editor', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Description'),
            'name'  => 'description',
            'config' => $wysiwygConfig,

        ));

        $fieldset->addField('column_width', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Column Width'),
            'name'  => 'column_width',
            'required'  => true,
            'class' => 'required-entry',

        ));

        $fieldset->addField('thumbnail', 'select', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Thumbnail'),
            'name'  => 'thumbnail',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('joomi_masonrygallery/gallery_attribute_source_thumbnail')->getAllOptions(true),
        ));

        $fieldset->addField('big_image', 'select', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Big Image'),
            'name'  => 'big_image',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('joomi_masonrygallery/gallery_attribute_source_bigimage')->getAllOptions(true),
        ));

        $fieldset->addField('ordering', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Ordering'),
            'name'  => 'ordering',

        ));

        $fieldset->addField('limit_first_load', 'text', array(
            'label' => Mage::helper('joomi_masonrygallery')->__('Limit First Load'),
            'name'  => 'limit_first_load',

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
            Mage::registry('current_gallery')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_gallery')->getDefaultValues();
        if (!is_array($formValues)){
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getGalleryData()){
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getGalleryData());
            Mage::getSingleton('adminhtml/session')->setGalleryData(null);
        }
        elseif (Mage::registry('current_gallery')){
            $formValues = array_merge($formValues, Mage::registry('current_gallery')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
