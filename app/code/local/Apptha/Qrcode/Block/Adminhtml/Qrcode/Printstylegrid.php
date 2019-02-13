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

class Apptha_Qrcode_Block_Adminhtml_Qrcode_Printstylegrid extends Mage_Adminhtml_Block_Widget_Grid
{
	public function __construct()
	{
		parent::__construct();
		$this->setId('printGrid');
		$this->setDefaultSort('print_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}
	//Get product collection from catalog
	protected function _prepareCollection()
	{
		$collection = Mage::getModel('qrcode/print')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	//Listing the available products in catalog
	protected function _prepareColumns()
	{
		?>
		<span style="color:red; font-weight:bold;"><?php echo Mage::helper('qrcode')->__('Please select any one style.') ?></span>
		<?php 
		$this->addColumn('print_id', array(
          'header'    => Mage::helper('qrcode/print')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'print_id',
		));

		$this->addColumn('style', array(
          'header'    => Mage::helper('qrcode/print')->__('Style'),
          'align'     =>'left',
		  'width'     =>'400px',
          'index' => 'style',
		));
     
		$this->addColumn('style_img', array(
            'header' => Mage::helper('qrcode/print')->__('Style Image'),
            'index' => print_id,
            'type' => 'options',
            'options' => Mage::getSingleton('qrcode/printstyle')->getStyleImage(),//calls QR_Image function in qrimage class from model
            'filter' => false,
		));


		$this->addExportType('*/*/exportCsv', Mage::helper('qrcode')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('qrcode')->__('XML'));

		return parent::_prepareColumns();
	}
	//Actions called by Submit
	protected function _prepareMassaction()
	{
		$qrid = $this->getRequest()->getParam('qrcodeid');
//	    $this->getRequest()->setParam('qrcodeid',$qrid);
	    $this->setMassactionIdField('print_id');
        $this->getMassactionBlock()->setFormFieldName('printstyle');
        $this->getMassactionBlock()->addItem('Print', array(
            'label' => Mage::helper('qrcode/print')->__('Print'),
            'url' => $this->getUrl('*/*/print',array('param1'=>$qrid)),
        ));
        return $this;
    }
}