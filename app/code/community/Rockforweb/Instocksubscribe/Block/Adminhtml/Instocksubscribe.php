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
class Rockforweb_Instocksubscribe_Block_Adminhtml_Instocksubscribe extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'instocksubscribe';
        $this->_controller = 'adminhtml_instocksubscribe';
        $this->_headerText = $this->__('In Stock Notifications');
        parent::__construct();
        $this->_removeButton('add');
    }
}