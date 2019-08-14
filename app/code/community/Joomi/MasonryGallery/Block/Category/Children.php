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
 * Category children list block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_Children
    extends Joomi_MasonryGallery_Block_Category_List {
    /**
     * prepare the layout
     * @access protected
     * @return Joomi_MasonryGallery_Block_Category_Children
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        $this->getCategories()->addFieldToFilter('parent_id', $this->getCurrentCategory()->getId());
        return $this;
    }
    /**
     * ge the current category
     * @access protected
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function getCurrentCategory(){
        return Mage::registry('current_category');
    }
}
