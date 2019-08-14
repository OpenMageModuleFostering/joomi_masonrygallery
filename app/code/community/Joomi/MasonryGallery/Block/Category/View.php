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
 * Category view block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_View
    extends Mage_Core_Block_Template {
    /**
     * get the current category
     * @access public
     * @return mixed (Joomi_MasonryGallery_Model_Category|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentCategory(){
        return Mage::registry('current_category');
    }
}
