<?php

use Phalcon\Http\Response;
use Backend\Models\Payments;
use Backend\Plugins\Uniteller;

class PayController extends ControllerFrontend
{
	public function indexAction($paymentId = 0)
	{

		$payment = Payments::findFirst($paymentId);

		$uniteller = new Uniteller();

		$uniteller->setPayment($paymentId);
		$uniteller->setSum($payment->sum);

		$this->view->setVar('uniteller', $uniteller);
		$this->view->setVar('title', 'Перенаправление на оплату');

		$this->view->setRenderLevel(\Phalcon\Mvc\View::LEVEL_ACTION_VIEW);

	}

	public function successAction()
	{
		$this->view->disable();

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
				$payment = Payments::findFirst($paymentId);

				if($payment)
				{
					$payment->status = $status;
					$payment->save();
					echo 'Payment success';
				}
			}
			else
			{
				echo 'Payment failed';
			}
		}
		else
		{
			//Request payment result
			$uniteller->getPaymentResult($orderId);
		}


	}

	public function failAction()
	{
		$this->view->disable();
		echo 'Payment failed';
	}

	public function notifyAction()
	{
		$response = new Response();

		if($this->request->isPost())
		{
			$Order_ID	= $this->request->getPost('Order_ID');
			$Status		= $this->request->getPost('Status');
			$Signature	= $this->request->getPost('Signature');

			$uniteller = new Uniteller();
			$localSignature = $uniteller->notifySignature($Order_ID, $Status);

			if($localSignature == $Signature)
			{
				$payment = Payments::findFirst($uniteller->getPaymentId($Order_ID));

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
						$response->setContent('Ошибка сохранения данных');
					}
				}
				else
				{
					$response->setStatusCode(404);
					$response->setContent('Платеж не найден');
				}
			}
			else
			{
				$response->setStatusCode(401);
				$response->setContent('Ошибка подписи запроса');
			}
		}
		else
		{
			$response->setStatusCode(405);
		}

		$response->send();
	}




}
