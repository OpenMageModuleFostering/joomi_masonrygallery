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
 * Gallery admin grid block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
    extends Mage_Adminhtml_Block_Widget_Grid {
    /**
     * constructor
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct(){
        parent::__construct();
        $this->setId('galleryGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }
    /**
     * prepare collection
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection(){
        $collection = Mage::getModel('joomi_masonrygallery/gallery')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    /**
     * prepare grid collection
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns(){
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Id'),
            'index'        => 'entity_id',
            'type'        => 'number'
        ));
        $this->addColumn('category_id', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Category'),
            'index'     => 'category_id',
            'type'      => 'options',
            'options'   => Mage::getResourceModel('joomi_masonrygallery/category_collection')->toOptionHash(),
            'renderer'  => 'joomi_masonrygallery/adminhtml_helper_column_renderer_parent',
            'params' => array(
                'id'=>'getCategoryId'
            ),
            'static' => array(
                'clear' => 1
            ),
            'base_link' => 'adminhtml/masonrygallery_category/edit'
        ));
        $this->addColumn('title', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));
        $this->addColumn('status', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Status'),
            'index'        => 'status',
            'type'        => 'options',
            'options'    => array(
                '1' => Mage::helper('joomi_masonrygallery')->__('Enabled'),
                '0' => Mage::helper('joomi_masonrygallery')->__('Disabled'),
            )
        ));
        $this->addColumn('column_width', array(
            'header'=> Mage::helper('joomi_masonrygallery')->__('Column Width'),
            'index' => 'column_width',
            'type'=> 'number',

        ));
        $this->addColumn('thumbnail', array(
            'header'=> Mage::helper('joomi_masonrygallery')->__('Thumbnail'),
            'index' => 'thumbnail',
            'type'  => 'options',
            'options' => Mage::helper('joomi_masonrygallery')->convertOptions(Mage::getModel('joomi_masonrygallery/gallery_attribute_source_thumbnail')->getAllOptions(false))

        ));
        $this->addColumn('big_image', array(
            'header'=> Mage::helper('joomi_masonrygallery')->__('Big Image'),
            'index' => 'big_image',
            'type'  => 'options',
            'options' => Mage::helper('joomi_masonrygallery')->convertOptions(Mage::getModel('joomi_masonrygallery/gallery_attribute_source_bigimage')->getAllOptions(false))

        ));
        $this->addColumn('ordering', array(
            'header'=> Mage::helper('joomi_masonrygallery')->__('Ordering'),
            'index' => 'ordering',
            'type'=> 'number',

        ));
        $this->addColumn('url_key', array(
            'header' => Mage::helper('joomi_masonrygallery')->__('URL key'),
            'index'  => 'url_key',
        ));
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn('store_id', array(
                'header'=> Mage::helper('joomi_masonrygallery')->__('Store Views'),
                'index' => 'store_id',
                'type'  => 'store',
                'store_all' => true,
                'store_view'=> true,
                'sortable'  => false,
                'filter_condition_callback'=> array($this, '_filterStoreCondition'),
            ));
        }
        $this->addColumn('created_at', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Created at'),
            'index'     => 'created_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('joomi_masonrygallery')->__('Updated at'),
            'index'     => 'updated_at',
            'width'     => '120px',
            'type'      => 'datetime',
        ));
        $this->addColumn('action',
            array(
                'header'=>  Mage::helper('joomi_masonrygallery')->__('Action'),
                'width' => '100',
                'type'  => 'action',
                'getter'=> 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('joomi_masonrygallery')->__('Edit'),
                        'url'   => array('base'=> '*/*/edit'),
                        'field' => 'id'
                    )
                ),
                'filter'=> false,
                'is_system'    => true,
                'sortable'  => false,
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('joomi_masonrygallery')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('joomi_masonrygallery')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('joomi_masonrygallery')->__('XML'));
        return parent::_prepareColumns();
    }
    /**
     * prepare mass action
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction(){
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('gallery');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('joomi_masonrygallery')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete'),
            'confirm'  => Mage::helper('joomi_masonrygallery')->__('Are you sure?')
        ));
        $this->getMassactionBlock()->addItem('status', array(
            'label'=> Mage::helper('joomi_masonrygallery')->__('Change status'),
            'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
            'additional' => array(
                'status' => array(
                        'name' => 'status',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('joomi_masonrygallery')->__('Status'),
                        'values' => array(
                                '1' => Mage::helper('joomi_masonrygallery')->__('Enabled'),
                                '0' => Mage::helper('joomi_masonrygallery')->__('Disabled'),
                        )
                )
            )
        ));
        $this->getMassactionBlock()->addItem('thumbnail', array(
            'label'=> Mage::helper('joomi_masonrygallery')->__('Change Thumbnail'),
            'url'  => $this->getUrl('*/*/massThumbnail', array('_current'=>true)),
            'additional' => array(
                'flag_thumbnail' => array(
                        'name' => 'flag_thumbnail',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('joomi_masonrygallery')->__('Thumbnail'),
                        'values' => Mage::getModel('joomi_masonrygallery/gallery_attribute_source_thumbnail')->getAllOptions(true),

                )
            )
        ));
        $this->getMassactionBlock()->addItem('big_image', array(
            'label'=> Mage::helper('joomi_masonrygallery')->__('Change Big Image'),
            'url'  => $this->getUrl('*/*/massBigImage', array('_current'=>true)),
            'additional' => array(
                'flag_big_image' => array(
                        'name' => 'flag_big_image',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('joomi_masonrygallery')->__('Big Image'),
                        'values' => Mage::getModel('joomi_masonrygallery/gallery_attribute_source_bigimage')->getAllOptions(true),

                )
            )
        ));
        $values = Mage::getResourceModel('joomi_masonrygallery/category_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem('category_id', array(
            'label'=> Mage::helper('joomi_masonrygallery')->__('Change Category'),
            'url'  => $this->getUrl('*/*/massCategoryId', array('_current'=>true)),
            'additional' => array(
                'flag_category_id' => array(
                        'name' => 'flag_category_id',
                        'type' => 'select',
                        'class' => 'required-entry',
                        'label' => Mage::helper('joomi_masonrygallery')->__('Category'),
                        'values' => $values
                )
            )
        ));
        return $this;
    }
    /**
     * get the row url
     * @access public
     * @param Joomi_MasonryGallery_Model_Gallery
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row){
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
    /**
     * get the grid url
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl(){
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    /**
     * after collection load
     * @access protected
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection(){
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
    /**
     * filter store column
     * @access protected
     * @param Joomi_MasonryGallery_Model_Resource_Gallery_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Joomi_MasonryGallery_Block_Adminhtml_Gallery_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column){
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
