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
 * Category source model
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Model_Category_Source
    extends Mage_Eav_Model_Entity_Attribute_Source_Abstract {
    /**
     * Get all options
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getAllOptions($withEmpty = false) {
        if (is_null($this->_options)) {
            $this->_options = Mage::getResourceModel('joomi_masonrygallery/category_collection')

                ->load()
                ->toOptionArray();
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, array('value'=>'', 'label'=>''));
        }
        return $options;
    }

    /**
     * Get a text for option value
     * @access public
     * @param string|integer $value
     * @return string
     * @author Ultimate Module Creator
     */
    public function getOptionText($value) {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    /**
     * Convert to options array
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function toOptionArray() {
        return $this->getAllOptions();
    }

    /**
     * Retrieve flat column definition
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getFlatColums() {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        $column = array(
            'unsigned'  => true,
            'default'   => null,
            'extra'     => null
        );
        if (Mage::helper('core')->useDbCompatibleMode()) {
            $column['type']     = 'int';
            $column['is_null']  = true;
        } else {
            $column['type']     = Varien_Db_Ddl_Table::TYPE_INTEGER;
            $column['nullable'] = true;
            $column['comment']  = $attributeCode . ' Category column';
        }
        return array($attributeCode => $column);
   }

    /**
     * Retrieve Select for update attribute value in flat table
     * @access public
     * @param   int $store
     * @return  Varien_Db_Select|null
     * @author Ultimate Module Creator
     */
    public function getFlatUpdateSelect($store) {
        return Mage::getResourceModel('eav/entity_attribute_option')
            ->getFlatUpdateSelect($this->getAttribute(), $store, false);
    }
}