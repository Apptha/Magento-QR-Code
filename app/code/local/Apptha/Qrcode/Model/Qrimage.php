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
class Apptha_Qrcode_Model_Qrimage extends Mage_Core_Model_Abstract
{
//Function to display QRCode in AdminPanel
public function QR_Image()
{
	$productCount = array();
    $productCollection = Mage::getModel('catalog/product')->getCollection();
    $baseUrl =  Mage::getBaseUrl();
	$baseUrl = str_replace('index.php/', '', $baseUrl);
	$PNG_DIR = $baseUrl.'/skin/frontend/default/default/Qrcode/images/';
    foreach ($productCollection as $product)
      {
       $productID = $product->getId();
       $tprefix = (string) Mage::getConfig()->getTablePrefix();
                    $resource = Mage::getSingleton('core/resource');
                    $QrProduct = $resource->getConnection('core_write');
                    $read = $resource->getConnection('catalog_read');
    //Get QR Code file name from data base
	   $QRGetImage = $read->fetchRow("Select qrcode_img  from " . $tprefix . "qrcode where product_id = " . $productID);
       if($QRGetImage[qrcode_img])
    	{
   		 $image_path_qr = $PNG_DIR.$QRGetImage[qrcode_img];
    	 $get_Image = '<img src=' . $image_path_qr . ' width="100" height="100" >';
    	}
    	else
    	{
    	 $get_Image ='Not available';
    	}
       $productCount[$product->getId()] = $get_Image;
      }
    return $productCount;
}
}?>