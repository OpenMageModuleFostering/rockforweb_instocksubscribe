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
class Rockforweb_Instocksubscribe_Adminhtml_InstocksubscribeController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->_title($this->__('Instocksubscribe'))->_title($this->__('In Stock Notificationы'));
        $this->loadLayout();
        $this->_setActiveMenu('rockforweb');
        $this->_addContent($this->getLayout()->createBlock('instocksubscribe/adminhtml_instocksubscribe'));
        $this->renderLayout();
    }
    public function gridAction()
    {
        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('instocksubscribe/adminhtml_instocksubscribe_grid')->toHtml()
        );
    }
    public function exportCsvAction()
    {
        $fileName = 'instocksubscribe.csv';
        $grid = $this->getLayout()->createBlock('instocksubscribe/adminhtml_instocksubscribe_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    public function exportExcelAction()
    {
        $fileName = 'instocksubscribe.xml';
        $grid = $this->getLayout()->createBlock('instocksubscribe/adminhtml_instocksubscribe_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
    public function massDeleteAction()
    {
        $notifyIds = $this->getRequest()->getParam('instocksubscribe_id');
        if(!is_array($notifyIds)) {
            Mage::getSingleton('adminhtml/session')->addError($this->__('Please select notification(s)'));
        } else {
            try {
                $notifyModel = Mage::getModel('instocksubscribe/instocksubscribe');
                foreach ($notifyIds as $notifyId) {
                    $notifyModel->load($notifyId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    $this->__(
                        'Total of %d record(s) were deleted', count($notifyIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}