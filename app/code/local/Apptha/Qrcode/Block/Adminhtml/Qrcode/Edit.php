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
class Apptha_Qrcode_Block_Adminhtml_Qrcode_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'qrcode';
        $this->_controller = 'adminhtml_qrcode';
        
        $this->_updateButton('save', 'label', Mage::helper('qrcode')->__('Send Mail'));
        $this->_updateButton('reset', 'label', Mage::helper('qrcode')->__('Reset'));
        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('qrcode_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'qrcode_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'qrcode_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'*/*/');
            }
        ";
        
      }

    public function getHeaderText()
    {
        if( Mage::registry('qrcode_data') && Mage::registry('qrcode_data')->getId() ) {
            return Mage::helper('qrcode')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('qrcode_data')->getTitle()));
        } else {
            return Mage::helper('qrcode')->__('Share QR Codes');
        }
    }
}