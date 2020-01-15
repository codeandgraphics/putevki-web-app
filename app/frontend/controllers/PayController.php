<?php

namespace Frontend\Controllers;

use Backend\Controllers\EmailController;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;
use Backend\Models\Payments;
use Backend\Plugins\Uniteller;
use Phalcon\Text;

class PayController extends BaseController
{
    public function indexAction()
    {
        $paymentId = $this->dispatcher->getParam('paymentId');

        $payment = Payments::findFirst('id="' . $paymentId . '"');

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

        $paymentId = $uniteller->getPaymentId($orderId);
        $payment = Payments::findFirst('id="' . $paymentId . '"');

        //$uniteller->getPaymentResult($orderId);

        $this->view->setVar('payment', $payment);

        $this->view->setRenderLevel(View::LEVEL_ACTION_VIEW);
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

        if ($this->request->isPost()) {
            $Order_ID = $this->request->getPost('Order_ID');
            $Status = $this->request->getPost('Status');
            $Signature = $this->request->getPost('Signature');

            $uniteller = new Uniteller();
            $localSignature = $uniteller->notifySignature($Order_ID, $Status);

            if ($localSignature === $Signature) {
                $payment = Payments::findFirst(
                    'id="' . $uniteller->getPaymentId($Order_ID) . '"'
                );

                if ($payment) {
                    $payment->status = $Status;
                    $payment->payDate = date('Y-m-d H:i:s');

                    $orderData = $uniteller->getPaymentResult($Order_ID);

                    if ($orderData) {
                        $payment->status = Text::lower(
                            $orderData[Uniteller::FIELDS_STATUS]
                        );
                        $payment->approvalCode =
                            $orderData[Uniteller::FIELDS_APPROVAL_CODE];
                        $payment->billNumber =
                            $orderData[Uniteller::FIELDS_BILL_NUMBER];
                        $payment->totalPaid =
                            $orderData[Uniteller::FIELDS_TOTAL];
                    }

                    if ($payment->save()) {
                        $email = new EmailController();

                        $email->sendPaymentNotification(
                            $orderData[Uniteller::FIELDS_EMAIL],
                            $payment
                        );
                        $email->sendManagerPaymentNotification($payment);

                        $response->setStatusCode(200);
                    } else {
                        $response->setStatusCode(500);
                        $response->setContent('Data save failed');
                    }
                } else {
                    $response->setStatusCode(404);
                    $response->setContent('Payment not found');
                }
            } else {
                $response->setStatusCode(401);
                $response->setContent('Request signature failure');
            }
        } else {
            $response->setStatusCode(405);
            $response->setContent('Method not allowed');
        }

        $response->send();
    }
}
