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
class Rockforweb_Instocksubscribe_Model_Observer extends Varien_Event_Observer
{
    public function notification(Varien_Event_Observer $observer) {
        $post = Mage::app()->getRequest()->getPost();
        $event = $observer->getEvent()->getProduct();
        $sku = $post['product']['sku'];
        $previousStockStatus = $event->getIsInStock();
        $currentStockStatus = $post['product']['stock_data']['is_in_stock'];
        if($currentStockStatus == 1 && $currentStockStatus !== $previousStockStatus) {
            if($event->getStockData('qty') !== '0') {
                $result = Mage::getModel('instocksubscribe/instocksubscribe')->findProductsBySku($sku);
                Mage::getModel('instocksubscribe/instocksubscribe')->prepareSendingEmail($result, $currentStockStatus);
            }
        }
    }
}