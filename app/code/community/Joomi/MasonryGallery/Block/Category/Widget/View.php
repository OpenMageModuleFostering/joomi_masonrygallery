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
 * Category widget block
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Block_Category_Widget_View
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface {
    protected $_htmlTemplate = 'joomi_masonrygallery/category/widget/view.phtml';
    /**
     * Prepare a for widget
     * @access protected
     * @return Joomi_MasonryGallery_Block_Category_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml() {
        parent::_beforeToHtml();
        $categoryId = $this->getData('category_id');
        if ($categoryId) {
            $category = Mage::getModel('joomi_masonrygallery/category')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($categoryId);
            if ($category->getStatusPath()) {
                $this->setCurrentCategory($category);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
