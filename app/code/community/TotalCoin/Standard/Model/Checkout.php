<?php

require_once(Mage::getBaseDir('lib') . '/totalcoin/totalcoin.php');

class TotalCoin_Standard_Model_Checkout extends Mage_Payment_Model_Method_Abstract{

	protected $_formBlockType = 'totalcoin_standard/form';
	protected $_infoBlockType = 'totalcoin_standard/info';

	protected $_code = 'totalcoin_standard';

	protected $_isGateway                   = true;
	protected $_canOrder                    = true;
	protected $_canAuthorize                = true;
	protected $_canCapture                  = true;
	protected $_canCapturePartial           = true;
	protected $_canRefund                   = true;
	protected $_canRefundInvoicePartial     = true;
	protected $_canVoid                     = true;
	protected $_canUseInternal              = true;
	protected $_canUseCheckout              = true;
	protected $_canUseForMultishipping      = true;
	protected $_canFetchTransactionInfo     = true;
	protected $_canCreateBillingAgreement   = true;
	protected $_canReviewPayment            = true;

	protected function _construct(){
		$this->_init('totalcoin_standard/checkout');
	}

	public function getUrl(){
		$data = Array();
		$url = "/";

		$order_id = Mage::getSingleton('checkout/session')->getLastRealOrderId();
		$order = Mage::getModel('sales/order')->loadByIncrementId($order_id);
		$customer = Mage::getSingleton('customer/session')->getCustomer();
		$model = Mage::getModel('catalog/product');
		$email = Mage::getStoreConfig('payment/totalcoin_configuration/client_email');
		$apikey = Mage::getStoreConfig('payment/totalcoin_configuration/client_apikey');
		$merchant_id = Mage::getStoreConfig('payment/totalcoin_configuration/merchant_id');
		$country = Mage::getStoreConfig('payment/totalcoin_configuration/country');
		$currency = Mage::getStoreConfig('payment/totalcoin_configuration/currency');
		$methods = Mage::getStoreConfig('payment/totalcoin_configuration/methods');

		$description = '';
		foreach ($order->getAllVisibleItems() as $item) {
			$product = $model->loadByAttribute('sku', $item->getSku());
			$description .= $item->getName() . ' - Precio por Unidad: ' . (int) number_format($product->getFinalPrice(), 2, '.', '');
			$description .= ' - Cantidad: ' . (int) number_format($item->getQtyOrdered(), 0, '.', '') . ' | ';
		}
		$data['Description'] = rtrim($description, ' | ');
		$data['Reference'] = $order_id;
		$data['Site'] = "Magento";
		$data['Country'] = $country;
		$data['Currency'] = $currency;
		$data['Amount'] = number_format($order->subtotal, 2, '.', '');
		$data['Quantity'] = 1;
		$data['MerchantId'] = $merchant_id;
		$data['PaymentMethods'] = str_replace(",", "|", $methods);
		$tc = new TCApi($email, $apikey);
		$results = $tc->perform_checkout($data);
		if ($results) {
			$url = $results['URL'];
		}
		return $url;
	}

	public function getOrderPlaceRedirectUrl() {
		return Mage::getUrl('totalcoin_standard/pay', array('_secure' => true));
	}


}

?>
