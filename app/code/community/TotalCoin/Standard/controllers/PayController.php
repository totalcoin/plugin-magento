<?php

class TotalCoin_Standard_PayController extends Mage_Core_Controller_Front_Action{

    public function indexAction() {

      $url = Mage::getModel('totalcoin_standard/checkout')->getUrl();

    	$this->loadLayout();

    	$block = $this->getLayout()->createBlock(
    	    'Mage_Core_Block_Template',
    	    'totalcoin_standard/pay',
    	     array('template' => 'totalcoin/standard/pay.phtml')
    	);

    	$block->assign(
    	    array(
    		        "url" => $url,
    		        "type_checkout" => Mage::getStoreConfig('payment/totalcoin_standard/type_checkout'),
    	    )
    	);

    	$this->getLayout()->getBlock('content')->append($block);
    	$this->_initLayoutMessages('core/session');

    	$this->renderLayout();
    }

}
