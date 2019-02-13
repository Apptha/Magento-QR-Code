<?php

class Apptha_Qrcode_Model_Shareqrcode extends Mage_Catalog_Block_Product_View
{
//Function to display QRCode in FrontEnd
    
    public function shareproduct($qrcodeId)
    {
      $qrcodearray = implode($qrcodeId);;
      $qrcodearray[] = $qrcodeId;
    }
    public  function sendmail($name,$mailid,$message)
    {
        echo $name.$mailid.$message;
        echo($this->$qrcodearray);die;
    }
}?>