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

class Apptha_Qrcode_Block_Adminhtml_Qrcode extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_qrcode';
    $this->_blockGroup = 'qrcode';
    $this->_headerText = Mage::helper('qrcode')->__('QR Code Manager');
    $this->_addButtonLabel = Mage::helper('qrcode')->__('Add Item');
    parent::__construct();
    $this->_removeButton('add');
    }
//Function used to print using layout    
//    public function printQrcode()
//    {
//     $qrcodeId = $this->getRequest()->getParam('qrcode');
//    }
//Function to fetch QR code image for print layout    
    public function printimage($qr_id)
    {
    $baseUrl =  Mage::getBaseUrl();
	$baseUrl = str_replace('index.php/', '', $baseUrl);
	$PNG_DIR = $baseUrl.'/skin/frontend/default/default/Qrcode/images/';
   	$tprefix = (string) Mage::getConfig()->getTablePrefix();
                    $resource = Mage::getSingleton('core/resource');
                    $read = $resource->getConnection('catalog_read'); 
    $QRGetImage = $read->fetchRow("Select qrcode_img  from " . $tprefix . "qrcode where product_id = " . $qr_id);
    if($QRGetImage[qrcode_img])
                {
   	             $image_path_qr = $PNG_DIR.$QRGetImage[qrcode_img];
                 $get_Image = '<img src=' . $image_path_qr . ' width="180" height="180" >';
                }
    else
                {
                 $get_Image ='QR Code not available please Generate QRCode';
                }  
   return $get_Image;
    }
 }
    
   