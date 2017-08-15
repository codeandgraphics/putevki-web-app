<?php

namespace Backend\Plugins;

use Backend\Models\Payments;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Text;

class Uniteller extends Plugin
{

	const PAY_URL = 'https://wpay.uniteller.ru/pay/';
	const RESULTS_URL = 'https://wpay.uniteller.ru/results/';
	const UNBLOCK_URL = 'https://wpay.uniteller.ru/unblock/';
	const CONFIRM_URL = 'https://wpay.uniteller.ru/confirm/';

	private $Login;
	private $Password;

	public $orderPrefix;

	//Обязательные параметры
	public $Shop_IDP;
	public $Order_IDP;
	public $Subtotal_P;
	public $URL_RETURN_OK;
	public $URL_RETURN_NO;
	public $Currency;

	//Необязательные параметры
	public $Lifetime;
	public $OrderLifetime;
	public $Customer_IDP = '';
	public $Card_IDP = '';
	public $PT_Code = '';
	public $MeanType = '';
	public $EMoneyType = '';
	public $BillLifetime;
	public $Language = 'ru';
	public $FirstName;
	public $LastName;
	public $MiddleName;
	public $Phone;
	public $Email;
	public $Address;
	public $IData = '';
	public $Preauth = '1';

	public function __construct()
	{
		$this->orderPrefix = $this->config->uniteller->orderPrefix;

		$this->Preauth = $this->config->uniteller->preAuth;

		$this->Shop_IDP = str_replace('\'', '', $this->config->uniteller->shopId);
		$this->Lifetime = $this->config->uniteller->lifeTime;
		$this->MeanType = $this->config->uniteller->meanType;
		$this->EMoneyType = $this->config->uniteller->moneyType;

		$this->Login = $this->config->uniteller->login;
		$this->Password = $this->config->uniteller->password;

		$this->URL_RETURN_OK = $this->url->get($this->config->uniteller->urlOk);
		$this->URL_RETURN_NO = $this->url->get($this->config->uniteller->urlNo);
	}

	public function setPayment($paymentId)
	{
		$this->Order_IDP = $this->orderPrefix . $paymentId;
	}

	public function setSum($sum)
	{
		$this->Subtotal_P = $sum;
	}

	public function getPaymentSignature()
	{
		return strtoupper(
			md5(
				md5($this->Shop_IDP) . '&' .
				md5($this->Order_IDP) . '&' .
				md5($this->Subtotal_P) . '&' .
				md5($this->MeanType) . '&' .
				md5($this->EMoneyType) . '&' .
				md5($this->Lifetime) . '&' .
				md5($this->Customer_IDP) . '&' .
				md5($this->Card_IDP) . '&' .
				md5($this->IData) . '&' .
				md5($this->PT_Code) . '&' .
				md5($this->Password)
			)
		);
	}

	public function notifySignature($Order_ID, $Status)
	{
		return strtoupper(
			md5(
				$Order_ID . $Status . $this->Password
			)
		);
	}

	public function getPaymentId($Order_ID)
	{
		return str_replace($this->orderPrefix, '', $Order_ID);
	}

	public function getPaymentResult($Order_ID)
	{
		$sPostFields =
			'Shop_ID=' . $this->Shop_IDP .
			'&Login=' . $this->Login .
			'&Password=' . $this->Password .
			'&Preauth=' . $this->Preauth .
			'&Format=1' .
			'&ShopOrderNumber=' . $Order_ID .
			'&S_FIELDS=Status;ApprovalCode;BillNumber';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::RESULTS_URL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $sPostFields);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

		$curl_response = curl_exec($ch);
		$curl_error = curl_error($ch);

		if (!$curl_error) {
			$arr = explode(';', $curl_response);

			if (count($arr) === 3) {
				$data = array(
					'Status' => $arr[0],
					'ApprovalCode' => $arr[1],
					'BillNumber' => $arr[2]
				);

				return $data;
			}
		}

		return false;
	}

	public function confirmPayment($billNumber) {
		$postFields =
			'Shop_ID=' . $this->Shop_IDP .
			'&Login=' . $this->Login .
			'&Password=' . $this->Password .
			'&Billnumber=' . $billNumber .
			'&S_FIELDS=Status;ApprovalCode;BillNumber';

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::CONFIRM_URL);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);

		$curl_response = curl_exec($ch);
		$curl_error = curl_error($ch);

		if (!$curl_error) {
			$arr = explode(';', $curl_response);

			if ($arr[0] !== 'ErrorCode') {
				$data = array(
					'Status' => $arr[0],
					'ApprovalCode' => $arr[1],
					'BillNumber' => $arr[2]
				);

				return $data;
			}

			return $arr[2];
		}

		return false;
	}
}