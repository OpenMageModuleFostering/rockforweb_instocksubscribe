<?php
/**
 * RockForWeb
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category RockForWeb Mod
 * @package Rockforweb_Instocksubscribe
 * @author RockForWeb Core Team
 * @copyright Copyright (c) 2014 RockForWeb (http://www.rockforweb.com)
 * @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class Rockforweb_Instocksubscribe_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction() {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction() {
        $this->loadLayout();
        $notLoggedInAllow = Mage::getStoreConfig('instocksubscribe/instocksubscribe_group/logged_in');
        $post = $this->getRequest()->getPost();
        $notificationCollection = Mage::getResourceModel('instocksubscribe/instocksubscribe_collection');
        $notificationCollection ->addFieldToSelect('sku', 'email')
            ->addFieldToFilter('sku', array('eq' => $post['sku']))
            ->addFieldToFilter('email', array('eq' => $post['subscribeEmail']))
            ->addFieldToFilter('notification_status', array('eq' => 'on'))
            ->load();
        if($notificationCollection->count() >= 1) {
            $message = $this->__('You can subscribe for that product only once');
            Mage::getSingleton('core/session')->addError($message);
            header("Location: " . $this->_getRefererUrl());
            exit();
        }
        if($notLoggedInAllow == 1) {
            if(isset($post['subscribeEmail']) && filter_var($post['subscribeEmail'], FILTER_VALIDATE_EMAIL)) {
                try {
                    $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
                    Mage::getModel('instocksubscribe/instocksubscribe')->saveSubscribe($customerId,
                        $post['sku'], $post['subscribeEmail']);
                    Mage::getSingleton('core/session')->addSuccess($this->__("Thank You !"));
                    header("Location: " . $this->_getRefererUrl());
                    exit();
                } catch(Exception $e) {
                    echo $e->getMessage(), "\n";
                }
            } else {
                $message = $this->__("Please check your email");
                Mage::getSingleton('core/session')->addError($message);
                header("Location: " . $this->_getRefererUrl());
                exit();
            }
        }
        $this->renderLayout();
    }
    public function showAllAction() {
        $this->loadLayout();
        $this->renderLayout();
    }
    public function unsubscribeAction() {
        $post = $this->getRequest()->getPost();
        if(isset($post['sku']) && isset($post['email']) && isset($post['id'])) {
            $notificationCollection = Mage::getResourceModel('instocksubscribe/instocksubscribe_collection');
            $notificationCollection ->addFieldToSelect('*')
                ->addFieldToFilter('sku', array('eq' => $post['sku']))
                ->addFieldToFilter('id', array('eq' => $post['id']))
                ->addFieldToFilter('email', array('eq' => $post['email']))
                ->load();
            $count = $notificationCollection->count();
            if($count >= 1) {
                $result = Mage::getModel('instocksubscribe/instocksubscribe')->unsubscribe($post['id']);
            }
            if($result->getId()) {
                Mage::getSingleton('core/session')->addSuccess($this->__("Done"));
                header("Location: " . $this->_getRefererUrl());
                exit();
            } else {
                Mage::getSingleton('core/session')->addError($this->__("Error"));
                header("Location: " . $this->_getRefererUrl());
                exit();
            }
        }
    }
}