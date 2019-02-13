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
class Apptha_Qrcode_Model_Qrcodefrontend extends Mage_Catalog_Block_Product_View
{
//Function to display QRCode in FrontEnd
public function getProduct()
    {
        if (!Mage::registry('product') && $this->getProductId()) {
            $product = Mage::getModel('catalog/product')->load($this->getProductId());
            Mage::register('product', $product);
        }

    $product = Mage::registry('product');
    $productid = $product->getId();
    $baseUrl =  Mage::getBaseUrl();
	$baseUrl = str_replace('index.php/', '', $baseUrl);
	$PNG_DIR = $baseUrl.'/skin/frontend/default/default/Qrcode/images/';
	//Fetch QR Code entries from QR Code table
	$tprefix = (string) Mage::getConfig()->getTablePrefix();
                    $resource = Mage::getSingleton('core/resource');
                    $QrProduct = $resource->getConnection('core_write');
                    $read = $resource->getConnection('catalog_read');
	$QRGetImage = $QrProduct->fetchRow("Select qrcode_img  from " . $tprefix . "qrcode where product_id = " . $productid);
    if($QRGetImage[qrcode_img])
    {
   	$image_path_qr = $PNG_DIR.$QRGetImage[qrcode_img];
    $get_Image = '<img src=' . $image_path_qr . ' width="180" height="180" >';
    }
    else
    {
    $get_Image =NULL;
    }
    return $get_Image;
    }

}?>