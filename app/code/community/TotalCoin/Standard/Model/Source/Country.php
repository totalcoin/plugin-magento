<?php

class TotalCoin_Standard_Model_Source_Country extends Mage_Payment_Model_Method_Abstract
{
    public function toOptionArray()
    {
      	$country = array();
      	$country[] = array('value' => "ARG", 'label' => "Argentina");
      	ksort($country);

        return $country;
    }
}
