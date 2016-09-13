<?php

namespace Utils\Email;

use Interfaces\IEmail as IEmail;

class Mailgun implements IEmail
{
	const MAILGUN_API_KEY = 'api:key-ec3c86a1517e940537f6f1e643f87ddf';
	const MAILGUN_SEND_URL = 'https://api.mailgun.net/v3/mg.and.graphics/messages';

	public function send($to, $subject, $message)
	{
		$query = [
			'from'		=> 'No-reply <postmaster@mg.and.graphics>',
			'to'		=> $to,
			'subject'	=> $subject,
			'html'		=> $message
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, self::MAILGUN_SEND_URL);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_USERPWD, self::MAILGUN_API_KEY);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));

		curl_exec ($ch);
		curl_close ($ch);
	}
}