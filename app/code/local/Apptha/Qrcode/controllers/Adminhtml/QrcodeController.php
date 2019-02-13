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

class Apptha_Qrcode_Adminhtml_QrcodeController extends Mage_Adminhtml_Controller_Action
{
	const XML_PATH_EMAIL_RECIPIENT = 'qrcode/email/recipient_email';
	const XML_PATH_EMAIL_SENDER = 'qrcode/email/sender_email_identity';
	const XML_PATH_EMAIL_TEMPLATE = 'qrcode/email/email_template';

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
	public function saveAction()
	{
		$name = $this->getRequest()->getPost('name');
		$mailid = $this->getRequest()->getPost('mail');
		$message = $this->getRequest()->getPost('message');
		$qrId = $this->getRequest()->getPost('qrid');
		$qrcodeId = (explode(',', $qrId));
		$mail1 = '<table rules="rows" style="border:8px solid #EDEDED" width="60%">';
		foreach($qrcodeId as $qr_id)
		{
			$baseUrl =  Mage::getBaseUrl();
			$baseUrl = str_replace('index.php/', '', $baseUrl);
			$PNG_DIR = $baseUrl.'skin/frontend/default/default/Qrcode/images/';
			$collection = Mage::getModel('catalog/product')->getCollection();
			$model = Mage::getModel('catalog/product');
			$product = $model->load($qr_id);
			//$productimage = Mage::helper('catalog/image')->init($product, 'image');
			$currentproduct = '<img src='.$productimage.' width="180" height="180">';
			$Product_name= $product->getName();
			$productUrl = $product->getProductUrl();
			$Product_price= floor($product->getPrice());
			$Product_splprice= floor($product->getSpecialPrice());
			$Product_from_date= date("m-d-Y", strtotime($product->getSpecialFromDate()));
			$Product_to_date= date("m-d-Y",strtotime($product->getSpecialToDate()));
			$Product_desc = $product->getDescription();
			$tprefix = (string) Mage::getConfig()->getTablePrefix();
			$resource = Mage::getSingleton('core/resource');
			$read = $resource->getConnection('catalog_read');
			$QRGetImage = $read->fetchRow("Select qrcode_img  from " . $tprefix . "qrcode where product_id = " . $qr_id);
			if($QRGetImage[qrcode_img])
			{
				$image_path_qr = $PNG_DIR.$QRGetImage[qrcode_img];
				$get_Image= '<img src=' . $image_path_qr . ' width="180" height="180" >';
			}
			else
			{
				$get_Image='QR Code not available';
			}
			$mail2.= '
    <tr>
        <td style="padding-left:20px;font-family:arial;" width="80%">
            <a href="'.$productUrl.'"><strong style="font-size: 18px;color:#333333;">'.$Product_name.'</strong></a><br/>
            <strong style="font-size: 15px;color:#444444;">Price: </strong><strong style="font-size: 13px;color:#555555;">'.$Product_price.'</strong><br/>
            <strong style="font-size: 15px;color:#444444;">Special Price: </strong><strong style="font-size: 13px;color:#555555;">'.$Product_splprice.'</strong><br/>
            <strong style="font-size: 15px;color:#444444;">Product Available From: </strong><strong style="font-size: 13px;color:#555555;">'.$Product_from_date.'</strong><br/>
            <strong style="font-size: 15px;color:#444444;">Product Expires on: </strong><strong style="font-size: 13px;color:#555555;">'.$Product_to_date.'</strong><br/>
        </td>
        <td width="20%">'. $get_Image.'</td>
   </tr>
   <tr>
      <td border:0;><strong style="font-size: 15px;word-wrap:break-word;color:#444444;">Description: </strong><strong style="font-size: 13px;color:#555555;">'.$Product_desc.'</strong><br/></td>
    </tr>
   <tr style="border:1px solid #EDEDED" width="100%"></tr>';

		}
		$mail3='</table>';
		$mail = $mail1.$mail2.$mail3;
	    //print_r($mail);die;
		if ($mail) {
			$postObject = new Varien_Object();
			$postObject->setData(array('product' => $mail,'message'=>$message));
			$mailTemplate = Mage::getModel('core/email_template');
			$mailTemplate->setSenderName($name);
			$mailTemplate->setSenderEmail(Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT));
			$mailTemplate->setTemplateSubject('Product QR Code'.Mage::getStoreConfig('design/head/default_title'));
			/* @var $mailTemplate Mage_Core_Model_Email_Template */
			if($mailTemplate)
			{
				$mailTemplate->setDesignConfig(array('area' => 'frontend'))
				->sendTransactional(
				Mage::getStoreConfig(self::XML_PATH_EMAIL_TEMPLATE),
				Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
				$mailid,
				$name,
				array('productlist' => $postObject)
				);
			}
			else
			{
				Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qrcode')->__('Please configure E-mail settings using system configuration'));
				$this->_redirect('qrcode/adminhtml_qrcode/');
			}
		}
		if ($mailTemplate->getSentSuccess()) {
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('qrcode')->__('E-mail has send Sucessfully'));
			$this->_redirect('qrcode/adminhtml_qrcode/');
		} else {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('qrcode')->__('Problem in sending mail'));
			$this->_redirect('qrcode/adminhtml_qrcode/');
		}

	}

	//QR Code Generation action
	public function massGenerateAction() {
		$qrcodeId = $this->getRequest()->getParam('qrcode');
		if (!is_array($qrcodeId)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {

			try {
				//calls productCollection function from Model inside QRcode Module
				Mage::getModel('qrcode/qrcode')->productCollection($qrcodeId);

			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	//Action to display QR Code in FrontEnd
	public function enableInFrontendAction() {

		$qrcodeId = $this->getRequest()->getParam('qrcode');
		//calls Display function from Model inside QRcode Module
		Mage::getModel('qrcode/QRCodeFrontend')->QR_FronendDisplay($qrcodeId);

	}

	//Action to Print QR Codes
	public function massPrintAction() {
		$qrcodeId = $this->getRequest()->getParam('qrcode');
		if (!is_array($qrcodeId)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		}
		else {

			try {
				$qrid = implode(',',$qrcodeId);
				$this->getRequest()->setParam('qrcodeid',$qrid);
				$this->loadLayout();
				$this->_addContent($this->getLayout()->createBlock('qrcode/adminhtml_qrcode_printstylegrid'));
				$this->renderLayout();
				 
				//        		 $this->loadLayout();
				//                 $this->getLayout()->getBlock('print')->setqrid($qrcodeId);
				//                 $this->renderLayout();
				 

			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
	}

	//Function to share QR Codes
	public function shareAction() {
		$qrcodeId = $this->getRequest()->getParam('qrcode');
		$qrid = implode(',',$qrcodeId);
		$this->getRequest()->setParam('qrcodeid',$qrid);
		if (!is_array($qrcodeId)) {
			Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select item(s)'));
		} else {
			try {
				Mage::registry('qrcode_data')->$qrid;
				$this->loadLayout();
				$this->_addContent($this->getLayout()->createBlock('qrcode/adminhtml_qrcode_edit'))
				->_addLeft($this->getLayout()->createBlock('qrcode/adminhtml_qrcode_edit_tabs')->setqrid($qrcodeId));
				$this->renderLayout();
			} catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
	}
	public function massStatusAction()
	{
		$qrcodeId = $this->getRequest()->getParam('qrcode');
		if(!is_array($qrcodeId)) {
			Mage::getSingleton('adminhtml/session')->addError($this->__('Please select item(s)'));
		} else {
			try {
				foreach ($qrcodeId as $qr_Id) {
					$qrcode = Mage::getSingleton('qrcode/qrcode')
					->load($qr_Id)
					->setStatus($this->getRequest()->getParam('status'))
					->setIsMassupdate(true)
					->save();
				}
				$this->_getSession()->addSuccess(
				$this->__('Total of %d record(s) were successfully updated', count($qr_Id))
				);
			} catch (Exception $e) {
				$this->_getSession()->addError($e->getMessage());
			}
		}
		$this->_redirect('*/*/index');
	}

	public function printAction() {
		   $layoutId = $this->getRequest()->getParam('printstyle');
           $qrid = $this->getRequest()->getParam('param1');
	       $qrcodeId = (explode(',', $qrid));
		  
           
        	if(count($layoutId) == 1) {

        			try {
        	     		switch($layoutId[0])
        	     		{
        	     			case 1:	$this->loadLayout();
                            		$this->getLayout()->getBlock('print')->setqrid($qrcodeId);
                            		$this->renderLayout();
                            		break;
                    		case 2:	$this->loadLayout();
                            		$this->getLayout()->getBlock('print1')->setqrid($qrcodeId);
                            		$this->renderLayout();
                            		break;
                    		case 3:	$this->loadLayout();
                            		$this->getLayout()->getBlock('print2')->setqrid($qrcodeId);
                            		$this->renderLayout();
                            		break;
        	     		}
        	     
					} 
					catch (Exception $e) {
                	Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            		}
        	}
	 	else {
		   		Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select any one style.'));
				$this->_redirect('*/*/');	   
		   }
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