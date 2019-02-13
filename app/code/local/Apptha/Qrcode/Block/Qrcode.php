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
class Apptha_Qrcode_Block_Qrcode extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getQrcode()     
     { 
        if (!$this->hasData('qrcode')) {
            $this->setData('qrcode', Mage::registry('qrcode'));
        }
        return $this->getData('qrcode');
    }
//Function to call image in fronend    
    public function getFrontendImage()
    {
    	return Mage::getModel('qrcode/qrcodefrontend')->getProduct();
    }
}