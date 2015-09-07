<?php

class TotalCoin_Standard_NotificationController extends Mage_Core_Controller_Front_Action{

	protected $_return = null;
	protected $_order = null;
	protected $_order_id = null;
	protected $_sendemail = false;
	protected $_hash = null;

	public function indexAction(){
		$core = Mage::getModel('totalcoin_standard/core');
		try {
			$params = $this->getRequest()->getParams();
			if(isset($params['reference']) && isset($params['merchant'])){
				$payment_info = $core->getOrderStatusAndOrderId($params['reference'], $params['merchant']);
				$payment_info['reference'] = $params['reference'];
				$this->setStatusOrder($payment_info);
			}
		} catch (Exception $e) {
			echo $e;
			header('', true, 400);
			exit;
		}

	}

	function setStatusOrder($payment_info){
		$message = "";
		$status = "";
		$order = Mage::getModel('sales/order')->loadByIncrementId($payment_info["order_id"]);
		$tc_status = $payment_info['order_status'];
		/*
		pending = InProcess
		canceled = Rejected
		processing = Approved
		complete = Available*/
		switch ($tc_status) {
			case 'Approved':
				$status = 'processing';
				$message = 'La orden ha sido autorizada, se está esperando la liberación del pago.';
				$invoice = $order->prepareInvoice();
				$invoice->register()->pay();
				Mage::getModel('core/resource_transaction')
				->addObject($invoice)
				->addObject($invoice->getOrder())
				->save();

				$invoice->sendEmail(true, $message);
			break;
			case 'Available':
				$status = 'complete';
				$message = 'La orden ha sido pagada. El dinero ya se encuentra disponible.';
				break;
			case 'Rejected':
				$status = 'canceled';
				$message = 'Pago rechazado por TotalCoin, contactar al cliente.';
				break;
			default:
				$status = 'pending';
				$message = 'La orden está siendo procesada.';
		}

		$message .= '<br/> TotalCoin Reference ID: ' . $payment_info['reference'];
		$message .= '<br/> TotalCoin Status: ' . $tc_status;

		$order->addStatusToHistory($status, $message, true);
		$order->sendOrderUpdateEmail(true, $message);

		$status_save = $order->save();

		echo $message;
	}

}
