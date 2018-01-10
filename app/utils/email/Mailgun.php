<?php

namespace Utils\Email;

use \Interfaces\IEmail;

class Mailgun implements IEmail
{
	const MAILGUN_API_KEY = 'api:key-90023c0ce2969e63d05d080e32abec60';
	const MAILGUN_SEND_URL = 'https://api.mailgun.net/v3/mail.putevki.ru/messages';

	public function send($to, $subject, $message, $additionalEmails = null)
	{
		$query = [
			'from'		=> 'Путевки.ру <postmaster@mail.putevki.ru>',
			'to'		=> $to,
			'subject'	=> $subject,
			'html'		=> $message
		];

		if($additionalEmails)
		{
			$query['cc'] = $additionalEmails;
		}

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