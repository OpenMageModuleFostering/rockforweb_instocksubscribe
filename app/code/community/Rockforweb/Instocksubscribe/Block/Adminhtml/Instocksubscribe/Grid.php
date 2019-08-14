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
class Rockforweb_Instocksubscribe_Block_Adminhtml_Instocksubscribe_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('instocksubscribe_grid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('instocksubscribe/instocksubscribe')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
        return $this;
    }

    protected function _prepareColumns()
    {
        $this->addColumn('customer_id', array(
            'header' => $this->__('Customer Id'),
            'index'  => 'customer_id'
        ));

        $this->addColumn('sku', array(
            'header' => $this->__('Product SKU'),
            'index'  => 'sku'
        ));

        $this->addColumn('email', array(
            'header' => $this->__('Customer Email'),
            'index'  => 'email'
        ));

        $this->addColumn('date', array(
            'header' => $this->__('Date/Time'),
            'index'  => 'date',
            'type'   => 'date'
        ));

        $this->addColumn('notification_status', array(
            'header' => $this->__('Notification Status'),
            'index'  => 'notification_status'
        ));

        $this->addExportType('*/*/exportCsv', $this->__('CSV'));
        $this->addExportType('*/*/exportExcel', $this->__('Excel XML'));
        return parent::_prepareColumns();
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('instocksubscribe');
        $this->getMassactionBlock()->setFormFieldName('instocksubscribe_id');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> $this->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
            'confirm' => $this->__('Are you sure?')
        ));
        return $this;
    }
}