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

class Apptha_Qrcode_Block_Adminhtml_Qrcode_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct() 
  {
      parent::__construct();
      $this->setId('qrcodeGrid');
      $this->setDefaultSort('qrcode_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
     }
//Get product collection from catalog
  protected function _prepareCollection()
  {
    $collection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('entity_id')
                ->addAttributeToSelect('special_from_date')
                ->addAttributeToSelect('special_to_date')
                ->addAttributeToSelect('Price');
    $this->setCollection($collection);
    return parent::_prepareCollection();
  }

//Listing the available products in catalog
  protected function _prepareColumns()
  {
      $this->addColumn('qrcode_id', array(
          'header'    => Mage::helper('qrcode')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'entity_id',
      ));

      $this->addColumn('special_from_date', array(
          'header'    => Mage::helper('qrcode')->__('From'),
          'align'     =>'left',
          'index' => 'special_from_date',
          'type' => 'date',
      ));

       $this->addColumn('special_to_date', array(
          'header'    => Mage::helper('qrcode')->__('Expires'),
          'align'     =>'left',
          'index'     => 'special_to_date',
          'type' => 'date',
      ));

      $this->addColumn('name', array(
          'header'    => Mage::helper('qrcode')->__('Product'),
          'align'     =>'left',
          'index'     => 'name',

      ));

       $this->addColumn('Price', array(
          'header'    => Mage::helper('qrcode')->__('Price'),
          'width'     =>'120px',
          'index'     => 'Price',
        ));

       $this->addColumn('qrcode', array(
            'header' => Mage::helper('qrcode')->__('QRCODE'),
            'index' => 'entity_id',
            'type' => 'options',
            'options' => Mage::getSingleton('qrcode/qrimage')->QR_Image(),//calls QR_Image function in qrimage class from model
            'filter' => false,
       ));

    	$this->addExportType('*/*/exportCsv', Mage::helper('qrcode')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('qrcode')->__('XML'));

      return parent::_prepareColumns();
  }
//Actions called by Submit
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('qrcode_id');
        $this->getMassactionBlock()->setFormFieldName('qrcode');
        $this->getMassactionBlock()->addItem('Generate QRcode', array(
            'label' => Mage::helper('qrcode')->__('Generate QRcode'),
            'url' => $this->getUrl('*/*/massGenerate'),
        ));
        $this->getMassactionBlock()->addItem('Print QR Codes', array(
            'label' => Mage::helper('qrcode')->__('Print QR Codes'),
            'url' => $this->getUrl('*/*/massPrint'),        
        ));
        $this->getMassactionBlock()->addItem('Share QR Codes', array(
            'label' => Mage::helper('qrcode')->__('Share QR Codes'),
            'url' => $this->getUrl('*/*/share'),
        ));
        return $this;
    }
}