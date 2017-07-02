<?php

namespace Backend\Controllers;

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