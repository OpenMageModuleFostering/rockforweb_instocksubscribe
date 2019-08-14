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
class Rockforweb_Instocksubscribe_Model_Instocksubscribe extends Mage_Core_Model_Abstract
{
    protected function _construct() {
        parent::_construct();
        $this->_init('instocksubscribe/instocksubscribe');
    }
    public function saveSubscribe($customerId, $sku, $subscribeEmail) {
        $subscribeCollection = Mage::getModel('instocksubscribe/instocksubscribe');
        $subscribeCollection->setData('id', null);
        if($customerId == null) {
            $subscribeCollection->setData('customer_id', 'not logged in');
        } else {
            $subscribeCollection->setData('customer_id', $customerId);
        }
        $subscribeCollection->setData('sku', $sku);
        $subscribeCollection->setData('email', $subscribeEmail);
        $subscribeCollection->setData('date', null);
        $subscribeCollection->setData('notification_status', 'on');

        $subscribeCollection->save();
    }
    public function findProductsBySku($sku) {
        $notificationCollection = Mage::getResourceModel('instocksubscribe/instocksubscribe_collection');
        $notificationCollection ->addFieldToSelect('*')
            ->addFieldToFilter('sku', array('eq' => $sku))
            ->load();
        if(count($notificationCollection) >= 1) {
            return $notificationCollection;
        }
    }
    public function prepareSendingEmail($result) {
        $emailTemplateId = Mage::getStoreConfig('instocksubscribe/instocksubscribe_group/email_template');
        foreach ($result as $item) {
            if($item->getNotificationStatus() == 'on') {
                $this->sendEmail($emailTemplateId, $item);
            }
        }
    }
    public function sendEmail($emailTemplateId, $item) {
        $product = Mage::getModel('catalog/product')->loadByAttribute('sku', $item->getSku());
        $sender = Array('name' => Mage::getStoreConfig('trans_email/ident_general/name'),
            'email' => Mage::getStoreConfig('trans_email/ident_general/email'));
        $store = Mage::app()->getStore();
        $vars = array(
            'product_name' => $product->getName(),
            'product_price' => Mage::helper('core')->currency($product->getFinalPrice(), true, false),
            'product_url' => $product->getProductUrl(),
            'product_picture' => (string)Mage::helper('catalog/image')->init($product, 'image'),
            'product_unsubscribe_url' => Mage::getBaseUrl() . "instocksubscribe/index/showAll'"
        );
        Mage::getModel('core/email_template')->sendTransactional($emailTemplateId, $sender, $item->getEmail(),
            $item->getCustomerId(), $vars, $store->getId());
    }
    public function unsubscribe($id) {
        $model = Mage::getModel('instocksubscribe/instocksubscribe')->load($id)->addData(array('notification_status' => 'off'));
        if($model->setId($id)->save()) {
            return $model;
        }
    }
}