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
$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('print')};
CREATE TABLE {$this->getTable('print')} (
  `print_id` int(11) unsigned NOT NULL auto_increment,
  `style` varchar(255) NOT NULL,
  `style_img` varchar(255) NOT NULL,
  `created_time` datetime NULL,
  `update_time` datetime NULL,
  PRIMARY KEY (`print_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->run("

INSERT INTO {$this->getTable('print')}(
`print_id`, `style`, `style_img`) VALUES
('1', 'default', 'default.JPG'), 
('2', 'style1', 'style1.JPG'), 
('3', 'style2', 'style2.JPG')");

$installer->endSetup(); 