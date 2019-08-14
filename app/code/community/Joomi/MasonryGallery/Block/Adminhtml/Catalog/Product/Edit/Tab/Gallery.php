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
 * Gallery tab on product edit form
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Gallery
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * Set grid params
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct() {
        parent::__construct();
        $this->setId('gallery_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_galleries'=>1));
        }
    }
    /**
     * prepare the gallery collection
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Gallery
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection() {
        $collection = Mage::getResourceModel('joomi_masonrygallery/gallery_collection');
        if ($this->getProduct()->getId()){
            $constraint = 'related.product_id='.$this->getProduct()->getId();
            }
            else{
                $constraint = 'related.product_id=0';
            }
        $collection->getSelect()->joinLeft(
            array('related'=>$collection->getTable('joomi_masonrygallery/gallery_product')),
            'related.gallery_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }
    /**
     * prepare mass action grid
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Gallery
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction(){
        return $this;
    }
    /**
     * prepare the grid columns
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Gallery
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns(){
        $this->addColumn('in_galleries', array(
            'header_css_class'  => 'a-center',
            'type'  => 'checkbox',
            'name'  => 'in_galleries',
            'values'=> $this->_getSelectedGalleries(),
            'align' => 'center',
            'index' => 'entity_id'
        ));
        $this->addColumn('title', array(
            'header'=> Mage::helper('joomi_masonrygallery')->__('Title'),
            'align' => 'left',
            'index' => 'title',
            'renderer'  => 'joomi_masonrygallery/adminhtml_helper_column_renderer_relation',
            'params' => array(
                'id'=>'getId'
            ),
            'base_link' => 'adminhtml/masonrygallery_gallery/edit',
        ));
        $this->addColumn('position', array(
            'header'        => Mage::helper('joomi_masonrygallery')->__('Position'),
            'name'          => 'position',
            'width'         => 60,
            'type'        => 'number',
            'validate_class'=> 'validate-number',
            'index'         => 'position',
            'editable'      => true,
        ));
        return parent::_prepareColumns();
    }
    /**
     * Retrieve selected galleries
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedGalleries(){
        $galleries = $this->getProductGalleries();
        if (!is_array($galleries)) {
            $galleries = array_keys($this->getSelectedGalleries());
        }
        return $galleries;
    }
     /**
     * Retrieve selected galleries
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedGalleries() {
        $galleries = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('joomi_masonrygallery/product')->getSelectedGalleries(Mage::registry('current_product'));
        if (!is_array($selected)){
            $selected = array();
        }
        foreach ($selected as $gallery) {
            $galleries[$gallery->getId()] = array('position' => $gallery->getPosition());
        }
        return $galleries;
    }
    /**
     * get row url
     * @access public
     * @param Joomi_MasonryGallery_Model_Gallery
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item){
        return '#';
    }
    /**
     * get grid url
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/galleriesGrid', array(
            'id'=>$this->getProduct()->getId()
        ));
    }
    /**
     * get the current product
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct(){
        return Mage::registry('current_product');
    }
    /**
     * Add filter
     * @access protected
     * @param object $column
     * @return Joomi_MasonryGallery_Block_Adminhtml_Catalog_Product_Edit_Tab_Gallery
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column){
        if ($column->getId() == 'in_galleries') {
            $galleryIds = $this->_getSelectedGalleries();
            if (empty($galleryIds)) {
                $galleryIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$galleryIds));
            }
            else {
                if($galleryIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$galleryIds));
                }
            }
        }
        else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
