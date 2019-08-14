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
 * Router
 *
 * @category    Joomi
 * @package     Joomi_MasonryGallery
 * @author      Ultimate Module Creator
 */
class Joomi_MasonryGallery_Controller_Router
    extends Mage_Core_Controller_Varien_Router_Abstract {
    /**
     * init routes
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Joomi_MasonryGallery_Controller_Router
     * @author Ultimate Module Creator
     */
    public function initControllerRouters($observer){
        $front = $observer->getEvent()->getFront();
        $front->addRouter('joomi_masonrygallery', $this);
        return $this;
    }
    /**
     * Validate and match entities and modify request
     * @access public
     * @param Zend_Controller_Request_Http $request
     * @return bool
     * @author Ultimate Module Creator
     */
    public function match(Zend_Controller_Request_Http $request){
        if (!Mage::isInstalled()) {
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('install'))
                ->sendResponse();
            exit;
        }
        $urlKey = trim($request->getPathInfo(), '/');
        $check = array();
        $check['category'] = new Varien_Object(array(
            'prefix'        => Mage::getStoreConfig('joomi_masonrygallery/category/url_prefix'),
            'suffix'        => Mage::getStoreConfig('joomi_masonrygallery/category/url_suffix'),
            'list_key'       => Mage::getStoreConfig('joomi_masonrygallery/category/url_rewrite_list'),
            'list_action'   => 'index',
            'model'         =>'joomi_masonrygallery/category',
            'controller'    => 'category',
            'action'        => 'view',
            'param'         => 'id',
            'check_path'    => 1
        ));
        $check['gallery'] = new Varien_Object(array(
            'prefix'        => Mage::getStoreConfig('joomi_masonrygallery/gallery/url_prefix'),
            'suffix'        => Mage::getStoreConfig('joomi_masonrygallery/gallery/url_suffix'),
            'list_key'       => Mage::getStoreConfig('joomi_masonrygallery/gallery/url_rewrite_list'),
            'list_action'   => 'index',
            'model'         =>'joomi_masonrygallery/gallery',
            'controller'    => 'gallery',
            'action'        => 'view',
            'param'         => 'id',
            'check_path'    => 0
        ));
        foreach ($check as $key=>$settings) {
            if ($settings->getListKey()) {
                if ($urlKey == $settings->getListKey()) {
                    $request->setModuleName('masonry')
                        ->setControllerName($settings->getController())
                        ->setActionName($settings->getListAction());
                    $request->setAlias(
                        Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                        $urlKey
                    );
                    return true;
                }
            }
            if ($settings['prefix']){
                $parts = explode('/', $urlKey);
                if ($parts[0] != $settings['prefix'] || count($parts) != 2){
                    continue;
                }
                $urlKey = $parts[1];
            }
            if ($settings['suffix']){
                $urlKey = substr($urlKey, 0 , -strlen($settings['suffix']) - 1);
            }
            $model = Mage::getModel($settings->getModel());
            $id = $model->checkUrlKey($urlKey, Mage::app()->getStore()->getId());
            if ($id){
                if ($settings->getCheckPath() && !$model->load($id)->getStatusPath()) {
                    continue;
                }
                $request->setModuleName('masonry')
                    ->setControllerName($settings->getController())
                    ->setActionName($settings->getAction())
                    ->setParam($settings->getParam(), $id);
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    $urlKey
                );
                return true;
            }
        }
        return false;
    }
}
