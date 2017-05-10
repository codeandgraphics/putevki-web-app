<?php

use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Backend\Models\Payments;
use Backend\Plugins\Uniteller;

class PayController extends ControllerFrontend
{
	public function indexAction($paymentId = 0)
	{
		$payment = Payments::findFirstById($paymentId);

		$uniteller = new Uniteller();

		$uniteller->setPayment($paymentId);
		$uniteller->setSum($payment->sum);

		$this->view->setVar('uniteller', $uniteller);
		$this->view->setVar('title', 'Перенаправление на оплату');

		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);

	}

	public function successAction()
	{
		$uniteller = new Uniteller();

		$orderId = $this->request->get('Order_ID');
		$status = $this->request->getPost('Status');
		$signature = $this->request->getPost('Signature');

		if($status && $signature)
		{
			//Check payment status
			if($signature === $uniteller->notifySignature($orderId, $status))
			{
				$paymentId = $uniteller->getPaymentId($orderId);
				$payment = Payments::findFirstById($paymentId);

				if($payment)
				{
					$payment->status = $status;
					$payment->save();

					$this->view->setVar('title', 'Успешный платеж');
					$this->view->setVar('success', true);
				}
			}
			else
			{
				$this->view->setVar('title', 'Ошибка оплаты');
				$this->view->setVar('success', false);
			}

			$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
		}
		else
		{
			$this->view->disable();
			//Request payment result
			$uniteller->getPaymentResult($orderId);
		}


	}

	public function failAction()
	{
		$this->view->setVar('title', 'Ошибка оплаты');
		$this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
	}

	public function notifyAction()
	{
		$response = new Response();
		$this->view->disable();

		if($this->request->isPost())
		{
			$Order_ID	= $this->request->getPost('Order_ID');
			$Status		= $this->request->getPost('Status');
			$Signature	= $this->request->getPost('Signature');

			$uniteller = new Uniteller();
			$localSignature = $uniteller->notifySignature($Order_ID, $Status);

			if($localSignature == $Signature)
			{
				$payment = Payments::findFirstById($uniteller->getPaymentId($Order_ID));

				if($payment)
				{
					$payment->status = $Status;
					$payment->payDate = date('Y-m-d H:i:s');

					if($payment->save())
					{
						$response->setStatusCode(200);
					}
					else
					{
						$response->setStatusCode(500);
						$response->setContent('Data save failed');
					}
				}
				else
				{
					$response->setStatusCode(404);
					$response->setContent('Payment not found');
				}
			}
			else
			{
				$response->setStatusCode(401);
				$response->setContent('Request signature failure');
			}
		}
		else
		{
			$response->setStatusCode(405);
			$response->setContent('Method not allowed');
		}

		$response->send();
	}




}
