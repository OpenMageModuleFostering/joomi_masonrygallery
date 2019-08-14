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
 * Gallery admin edit form
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Gallery_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container {
    /**
     * constructor
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->_blockGroup = 'joomi_masonrygallery';
        $this->_controller = 'adminhtml_gallery';
        $this->_updateButton('save', 'label', Mage::helper('joomi_masonrygallery')->__('Save Gallery'));
        $this->_updateButton('delete', 'label', Mage::helper('joomi_masonrygallery')->__('Delete Gallery'));
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('joomi_masonrygallery')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);
        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    /**
     * get the edit form header
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText(){
        if( Mage::registry('current_gallery') && Mage::registry('current_gallery')->getId() ) {
            return Mage::helper('joomi_masonrygallery')->__("Edit Gallery '%s'", $this->escapeHtml(Mage::registry('current_gallery')->getTitle()));
        }
        else {
            return Mage::helper('joomi_masonrygallery')->__('Add Gallery');
        }
    }
}
