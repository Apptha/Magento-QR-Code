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

class Apptha_Qrcode_Adminhtml_PrintController extends Mage_Adminhtml_Controller_Action
{
   	
    protected function _initAction() {
		$this->loadLayout()
			->_setActiveMenu('qrcode/items')
			->_addBreadcrumb(Mage::helper('adminhtml')->__('Items Manager'), Mage::helper('adminhtml')->__('Item Manager'));

		return $this;
	}

	public function indexAction() {
		$this->_initAction()
			->renderLayout();
	}
 
//Action to Print QR Codes    
    public function printAction() {
       	   $qrcodeId = $this->getRequest()->getParam('qrcode');
           if (!is_array($qrcodeId)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
        } 
        else {

        	try {
        	     Mage::getModel('qrcode/printstyle')->getstyle($qrcodeId);
        		 //$this->loadLayout();
                 //$this->getLayout()->getBlock('print')->setqrid($qrcodeId);
                 //$this->renderLayout();
                 } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
    }
     
    public function listLayoutAction()
    {
    	echo "listLayout";die;
    	Mage::getModel('qrcode/printstyle')->layoutCollection($qrcodeId);
    } 

    public function exportCsvAction()
    {
        $fileName   = 'qrcode.csv';
        $content    = $this->getLayout()->createBlock('qrcode/adminhtml_qrcode_grid')
            ->getCsv();

        $this->_sendUploadResponse($fileName, $content);
    }

    public function exportXmlAction()
    {
        $fileName   = 'qrcode.xml';
        $content    = $this->getLayout()->createBlock('qrcode/adminhtml_qrcode_grid')
            ->getXml();

        $this->_sendUploadResponse($fileName, $content);
    }

    protected function _sendUploadResponse($fileName, $content, $contentType='application/octet-stream')
    {
        $response = $this->getResponse();
        $response->setHeader('HTTP/1.1 200 OK','');
        $response->setHeader('Pragma', 'public', true);
        $response->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $response->setHeader('Content-Disposition', 'attachment; filename='.$fileName);
        $response->setHeader('Last-Modified', date('r'));
        $response->setHeader('Accept-Ranges', 'bytes');
        $response->setHeader('Content-Length', strlen($content));
        $response->setHeader('Content-type', $contentType);
        $response->setBody($content);
        $response->sendResponse();
        die;
    }
}