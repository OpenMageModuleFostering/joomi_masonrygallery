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
 * Gallery helper
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Helper_Gallery
    extends Mage_Core_Helper_Abstract {
    /**
     * get the url to the galleries list page
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGalleriesUrl(){
        if ($listKey = Mage::getStoreConfig('joomi_masonrygallery/gallery/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('joomi_masonrygallery/gallery/index');
    }
    /**
     * check if breadcrumbs can be used
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs(){
        return Mage::getStoreConfigFlag('joomi_masonrygallery/gallery/breadcrumbs');
    }
}
