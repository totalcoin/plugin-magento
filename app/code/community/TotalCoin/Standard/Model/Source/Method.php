<?php

//http://stackoverflow.com/questions/18650487/magento-system-config-add-custom-value-in-multiselect

class TotalCoin_Standard_Model_Source_Method extends Mage_Payment_Model_Method_Abstract
{
    public function toOptionArray()
    {
      	$methods = array();
        $methods[] = array('value' => 'CREDITCARD', 'label' => 'Tarjeta de Crédito');
        $methods[] = array('value' => 'CASH', 'label' => 'Efectivo');
        $methods[] = array('value' => 'TOTALCOIN', 'label' => 'Saldo en cuenta TotalCoin');
        ksort($methods);

        return $methods;
    }

    public function toArray()
    {
        return array(
          'CREDITCARD' => 'Tarjeta de Crédito',
          'CASH' => 'Efectivo',
          'TOTALCOIN' => 'Saldo en cuenta TotalCoin',
        );
    }
}
