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
 * Category helper
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Helper_Category
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the categories list page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getCategoriesUrl(){
        if ($listKey = Mage::getStoreConfig('joomi_masonrygallery/category/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('joomi_masonrygallery/category/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('joomi_masonrygallery/category/breadcrumbs');
    }
    const CATEGORY_ROOT_ID = 1;
    /**
     * get the root id
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getRootCategoryId(){
        return self::CATEGORY_ROOT_ID;
    }
}
