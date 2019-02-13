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
class Apptha_Qrcode_Model_Qrcode extends Mage_Core_Model_Abstract {

    public function _construct() {
        parent::_construct();
        $this->_init('qrcode/qrcode');
    }

    public function productCollection($qrcodeId) {
        //Function to Generate QR Code and Store Details in QRCode table
        foreach ($qrcodeId as $qr_Id) {
            $QrcodeFileName = $qr_Id;
            $tprefix = (string) Mage::getConfig()->getTablePrefix();
            $resource = Mage::getSingleton('core/resource');
            $QrProduct = $resource->getConnection('core_write');
            $read = $resource->getConnection('catalog_read');
            $QRTableEntry = $QrProduct->fetchRow("Select product_id  from " . $tprefix . "qrcode  where product_id = " . $qr_Id);
            if (!$QRTableEntry['product_id']) {
                $QRTableEntry = $QrProduct->fetchRow("Select request_path  from " . $tprefix . "core_url_rewrite  where id_path = 'product/$QrcodeFileName' ");
                $QRTableEntry['request_path'];
                $URL = Mage::getBaseUrl() . $QRTableEntry['request_path'];
                $Generate_image = Mage::getModel('qrcode/qrcode')->generateQrCode($URL, $QrcodeFileName);
            } else {
                $QRTableEntry = $QrProduct->fetchRow("Select request_path  from " . $tprefix . "core_url_rewrite  where id_path = 'product/$QrcodeFileName' ");
                $QRTableEntry['request_path'];
                $URL = Mage::getBaseUrl() . $QRTableEntry['request_path'];
                $Generate_image = Mage::getModel('qrcode/qrcode')->updateqrcode($URL, $QrcodeFileName,$qr_Id);
            }
           
        }
       
    }
//Function to generate QR code for product
     public function generateQrCode($url, $FileName) {           
        	$baseUrl = Mage::getBaseDir();
        	$baseUrl = str_replace('index.php/', '', $baseUrl);
        	$PNG_DIR = $baseUrl . '/skin/frontend/default/default/Qrcode/images/';
        	if (!is_dir($PNG_DIR)) {
            	mkdir($PNG_DIR, 0777, true);
        		}
        	$productID = $FileName;
        	$productUrl = $url;
        	$size = '250x250';
        	$content = $productUrl;
        	$correction = 'L';
        	$encoding = 'UTF-8';
        	$filename = $FileName . '.png';
        //Generate QR Code Using Google Api
        	$rootUrl = "http://chart.googleapis.com/chart?cht=qr&chs=$size&chl=$content&choe=$encoding&chld=$correction";
        //Function to write Image files in Specified Directory
        	 if(function_exists ("curl_init"))
            {
        	$curl_handle = curl_init();
        	curl_setopt($curl_handle, CURLOPT_URL, $rootUrl);
        	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        	$get_image = curl_exec($curl_handle);
        	curl_close($curl_handle);
        	$image_to_fetch = $get_image;
        	$image_path_qr = $PNG_DIR . $filename; 
           	$local_image_file = fopen($image_path_qr, 'w');
        	chmod($image_path_qr, 0777);
        	$fp = fwrite($local_image_file, $image_to_fetch);
        	fclose($local_image_file);
//        	$data = array('product_id' => $productID, 'qrcode_img' => $filename);
//        	$model = Mage::getModel('qrcode/qrcode')->setData($data);
//        	$model->save();
            
            $tprefix = (string) Mage::getConfig()->getTablePrefix();
            $resource = Mage::getSingleton('core/resource');
            $QrProduct = $resource->getConnection('core_write');
        	$insert = $QrProduct->query("INSERT INTO " . $tprefix . "qrcode (product_id,qrcode_img) VALUES ('" . $productID . "','" . $filename . "')");
        	return $image_path_qr;
            }
     else {
           	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qrcode')->__('Curl function does not exist in your server please contact your Hosting server'));
           }
            
        }
//Function to update the QR Code which are already exist
     public function updateqrcode($url,$FileName,$qr_Id)
        {
            $baseUrl = Mage::getBaseDir();
        	$baseUrl = str_replace('index.php/', '', $baseUrl);
        	$PNG_DIR = $baseUrl . '/skin/frontend/default/default/Qrcode/images/';
        	if (!is_dir($PNG_DIR)) {
            	mkdir($PNG_DIR, 0777, true);
        		}
        	$productID = $FileName;
        	$productUrl = $url;
        	$size = '250x250';
        	$content = $productUrl;
        	$correction = 'L';
        	$encoding = 'UTF-8';
        	$filename = $FileName . '.png';
        //Request QR Code Using Google Api
        	$rootUrl = "http://chart.googleapis.com/chart?cht=qr&chs=$size&chl=$content&choe=$encoding&chld=$correction";
        //Function to write Image files in Specified Directory
           if(function_exists ("curl_init"))
            {
        	$curl_handle = curl_init();
        	curl_setopt($curl_handle, CURLOPT_URL, $rootUrl);
        	curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        	curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        	curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false);
        	$get_image = curl_exec($curl_handle);
        	curl_close($curl_handle);
        	$image_to_fetch = $get_image;
        	$image_path_qr = $PNG_DIR . $filename; 
        	$local_image_file = fopen($image_path_qr, 'w');
        	chmod($image_path_qr, 0777);
        	$fp = fwrite($local_image_file, $image_to_fetch);
        	fclose($local_image_file);
        	$tprefix = (string) Mage::getConfig()->getTablePrefix();
            $resource = Mage::getSingleton('core/resource');
            $QrProduct = $resource->getConnection('core_write');
        	$update = $QrProduct->query("UPDATE " . $tprefix . "qrcode SET qrcode_img = '" . $filename . " ' WHERE product_id = " . $qr_Id);
        	return $image_path_qr;	
            }
           else {
           	Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qrcode')->__('Curl function does not exist in your server please contact your Hosting server'));
           }
        }

}