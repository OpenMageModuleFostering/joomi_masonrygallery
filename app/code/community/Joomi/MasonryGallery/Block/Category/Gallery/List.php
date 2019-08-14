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
 * Category Galleries list block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_Gallery_List
    extends Joomi_MasonryGallery_Block_Gallery_List {
    /**
     * initialize
     * @access public
     * @author Ultimate Module Creator
     */
     public function __construct(){
        parent::__construct();
        $category = $this->getCategory();
        if ($category){
            $this->getGalleries()->addFieldToFilter('category_id', $category->getId());
        }
    }
    /**
     * prepare the layout - actually do nothing
     * @access protected
     * @return Joomi_MasonryGallery_Block_Category_Gallery_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout(){
        return $this;
    }
    /**
     * get the current category
     * @access public
     * @return Joomi_MasonryGallery_Model_Category
     * @author Ultimate Module Creator
     */
    public function getCategory(){
        return Mage::registry('current_category');
    }
}
