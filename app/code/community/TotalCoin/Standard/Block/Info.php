<?php

class TotalCoin_Standard_Block_Info extends Mage_Payment_Block_Info_Cc {

    protected function _construct(){
      parent::_construct();
      $this->setTemplate('totalcoin/standard/info.phtml');
      $this->setModuleName('Mage_Payment');
    }

    public function getOrder() {
	     return $this->getInfo();
    }
}

?>
