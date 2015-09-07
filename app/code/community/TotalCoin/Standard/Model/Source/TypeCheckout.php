<?php

class TotalCoin_Standard_Model_Source_TypeCheckout extends Mage_Payment_Model_Method_Abstract
{
    public function toOptionArray()
    {
      	$types = array();
        $types[] = array('value' => "iframe", 'label' => "Iframe");
        $types[] = array('value' => "redirect", 'label' => "Redirect");
      	ksort($types);

        return $types;
    }
}
