<?php

class TotalCoin_Standard_Block_Form extends Mage_Payment_Block_Form_Cc {

    protected function _construct(){
      parent::_construct();
      $this->setTemplate('totalcoin/standard/form.phtml');
    }
}

?>
