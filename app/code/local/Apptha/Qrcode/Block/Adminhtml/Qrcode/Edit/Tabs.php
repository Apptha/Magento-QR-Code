<?php
/**
 * @name         :  Apptha QR Code for Magento
 * @version      :  1.0
 * @since        :  Magento 1.5
 * @author       :  Apptha - http://www.apptha.com
 * @copyright    :  Copyright (C) 2011 Powered by Apptha
 * @license      :  http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Creation Date:  Nov 19 2011
 * 
 * */
class Apptha_Qrcode_Block_Adminhtml_Qrcode_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('qrcode_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('qrcode')->__('QR Code Information'));
  }

  protected function _beforeToHtml()
  {
      $this->addTab('form_section', array(
          'label'     => Mage::helper('qrcode')->__('Share QR codes'),
          'title'     => Mage::helper('qrcode')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('qrcode/adminhtml_qrcode_edit_tab_form')->toHtml(),
      ));
     
      return parent::_beforeToHtml();
  }
}