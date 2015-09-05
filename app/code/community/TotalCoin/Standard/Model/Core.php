<?php
require_once(Mage::getBaseDir('lib') . '/totalcoin/totalcoin.php');

class TotalCoin_Standard_Model_Core extends Mage_Payment_Model_Method_Abstract{
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

	public function log($message, $file = "totalcoin.log", $array = null){

		if(!is_null($array))
		$message .= " - " . json_encode($array);

		Mage::log($message, null, $file, $action_log);
	}

  public function getOrderStatusAndOrderId($tc_id){
    $email = Mage::getStoreConfig('payment/totalcoin_configuration/client_email');
    $apikey = Mage::getStoreConfig('payment/totalcoin_configuration/client_apikey');
    $tc = new TCApi($email, $apikey);
    $data = $tc->get_ipn_info($tc_id);
    $order_detail = Array();
    if ($data['IsOk']) {
        $order_detail['order_status'] = $this->get_last_order_status_from_transaction_history($data['Response']['TransactionHistories']);
        $order_detail['order_id'] = $data['Response']['MerchantReference'];
    }

    return $order_detail;
  }

  private function get_last_order_status_from_transaction_history($transaction_histories) {
      $ordered_transaction_histories = Array();
      foreach ($transaction_histories as $transaction_history) {
        $date_created = date_create($transaction_history['Date']);
        $history = Array();
        $history['date'] = date_format($date_created, 'Y-m-d H:i:s');
        $history['status'] = $transaction_history['TransactionState'];
        $ordered_transaction_histories[] = $history;
      }

      if (count($ordered_transaction_histories) > 1) {
        usort($ordered_transaction_histories, function($a, $b) {
            return strtotime($a['date']) - strtotime($b['date']);
        });
        $ordered_transaction_histories = end($ordered_transaction_histories);
        $last_status = $ordered_transaction_histories['status'];
      } else {
        $last_status = $ordered_transaction_histories[0]['status'];
      }

      return $last_status;
  }
}

?>
