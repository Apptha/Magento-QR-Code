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

class Apptha_Qrcode_Block_Adminhtml_Qrcode_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{

 public function __construct()
    {
        parent::__construct();
        //$this->setTemplate('qrcode/sharecode.phtml');
    }
    
	protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $qrid = $this->getRequest()->getParam('qrcodeid');
      $this->setForm($form);
      $fieldset = $form->addFieldset('qrcode_form', array('legend'=>Mage::helper('qrcode')->__('Share QR Codes')));
     
      $fieldset->addField('name', 'text', array(
          'label'     => Mage::helper('qrcode')->__('Name'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'name',
      ));

      $fieldset->addField('mail', 'text', array(
          'label'     => Mage::helper('qrcode')->__('E-mail'),
          'required'  => true,
          'name'      => 'mail',
          'class'      => 'validate-email required-entry input-text required-entry',
	  ));
  
	  $fieldset->addField('qrid', 'hidden', array(
          'name'      => 'qrid',
	      'value'   => $qrid,
	  ));
      
	
        $fieldset->addField('meta_keywords', 'textarea', array(
            'name' => 'message',
            'label' => Mage::helper('qrcode')->__('Message'),
       ));
 
      if ( Mage::getSingleton('adminhtml/session')->getQrcodeData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getQrcodeData());
          Mage::getSingleton('adminhtml/session')->setQrcodeData(null);
      } elseif ( Mage::registry('qrcode_data') ) {
          $form->setValues(Mage::registry('qrcode_data')->getData());
      }
      return parent::_prepareForm();
  }
}