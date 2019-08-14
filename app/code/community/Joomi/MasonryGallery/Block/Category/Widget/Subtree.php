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
 * Category subtree block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_Widget_Subtree
    extends Joomi_MasonryGallery_Block_Category_List
    implements Mage_Widget_Block_Interface {
    protected $_template = 'joomi_masonrygallery/category/widget/subtree.phtml';
    /**
     * prepare the layout
     * @access protected
     * @return Joomi_MasonryGallery_Block_Category_Widget_Subtree
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        $this->getCategories()->addFieldToFilter('entity_id', $this->getCategoryId());
        return $this;
    }
    /**
     * get the display mode
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    protected function _getDisplayMode(){
        return 1;
    }
    /**
     * get the element id
     * @access protected
     * @return int
     * @author Ultimate Module Creator
     */
    public function getUniqueId(){
        if (!$this->getData('uniq_id')){
            $this->setData('uniq_id', uniqid('subtree'));
        }
        return $this->getData('uniq_id');
    }
}
