<?php

namespace Backend\Controllers;

use Backend\Plugins\Uniteller;
use Phalcon\Http\Response;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Backend\Models\Payments;
use Utils\Text;

class PaymentsController extends ControllerBase
{

	public function indexAction()
	{
		$requestId = false;
		if ($this->request->has('request')) {
			$requestId = $this->request->get('request', 'int');
		}


		if ($this->request->isPost()) {
			$paymentSum = $this->request->getPost('paymentSum');
			$paymentSum = (float) str_replace(',', '.', $paymentSum);

			if ($paymentSum) {
				$payment = new Payments();
				$payment->sum = $paymentSum;

				if ($requestId) {
					$payment->requestId = $requestId;
				}

				if ($payment->save()) {
					$this->flashSession->success('Создан платеж на ' . Text::humanize('price', $paymentSum) . ' руб.');
				} else {
					foreach ($payment->getMessages() as $message) {
						$this->flashSession->error($message);
					}
					$this->flashSession->error('Невозможно создать платеж');
				}

			} else {
				$this->flashSession->error('Неправильная сумма платежа');
			}
		}

		$query = [
			'order' => 'creationDate DESC'
		];

		if ($requestId) {
			$query[] = 'requestId = ' . $requestId;
		}

		$payments = Payments::find($query);

		$paginator = new PaginatorModel(
			array(
				'data' => $payments,
				'limit' => 10,
				'page' => $this->request->get('page')
			)
		);

		$this->view->setVar('publicUrl', $this->frontendConfig->publicURL);
		$this->view->setVar('page', $paginator->getPaginate());

	}

	public function paymentAction() {
		$paymentId = $this->dispatcher->getParam(0);
		$payment = Payments::findFirst('id="' . $paymentId . '"');

		if ($this->request->has('confirmPayment')) {

			$uniteller = new Uniteller();
			$data = $uniteller->confirmPayment(trim($payment->bill_number));

			if(is_array($data)) {
				$payment->auth_confirmed = 1;
				$payment->save();
				$this->flashSession->success('Авторизация платежа успешно подтверждена');

			} else {
				$this->flashSession->error('Авторизация платежа не подтверждена. Ошибка: ' . $data);
			}
		}

		if ($this->request->has('getOrderCode')) {
			$uniteller = new Uniteller();
			$orderData = $uniteller->getPaymentResult($payment->getOrder());

			if($orderData) {
				$payment->status = \Phalcon\Text::lower($orderData['Status']);
				$payment->approval_code = $orderData['ApprovalCode'];
				$payment->bill_number = $orderData['BillNumber'];
				$payment->save();
			} else {
				$this->flashSession->error('Невозможно получить код авторизации');
			}
		}

		$this->view->setVar('payment', $payment);
	}

	public function deleteAction()
	{
		$this->view->disable();

		if ($this->request->isPost()) {
			$response = new Response();

			$paymentId = $this->request->getPost('paymentId');

			$payment = Payments::findFirst($paymentId);
			$payment->delete();

			$response->setJsonContent(['paymentId' => $paymentId]);
			$response->send();
		}

	}
}