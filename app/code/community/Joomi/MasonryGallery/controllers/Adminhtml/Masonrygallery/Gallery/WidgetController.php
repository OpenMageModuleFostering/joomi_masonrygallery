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
 * Gallery admin widget controller
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Adminhtml_Masonrygallery_Gallery_WidgetController
    extends Mage_Adminhtml_Controller_Action {
    /**
     * Chooser Source action
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function chooserAction(){
        $uniqId = $this->getRequest()->getParam('uniq_id');
        $grid = $this->getLayout()->createBlock('joomi_masonrygallery/adminhtml_gallery_widget_chooser', '', array(
            'id' => $uniqId,
        ));
        $this->getResponse()->setBody($grid->toHtml());
    }
}
