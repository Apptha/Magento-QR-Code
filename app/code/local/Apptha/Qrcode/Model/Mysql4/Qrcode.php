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
class Apptha_Qrcode_Model_Mysql4_Qrcode extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the qrcode_id refers to the key field in your database table.
        $this->_init('qrcode/qrcode', 'qrcode_id');
     }
}