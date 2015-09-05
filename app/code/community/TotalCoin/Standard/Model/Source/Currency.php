<?php

class TotalCoin_Standard_Model_Source_Currency extends Mage_Payment_Model_Method_Abstract
{
    public function toOptionArray()
    {
      	$currency = array();
        $currency[] = array('value' => "ARS", 'label' => "Pesos Argentinos");
      	ksort($currency);

        return $currency;
    }
}
