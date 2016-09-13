<?php

namespace Models;

class ApiError
{
	public $code;
	public $text;

	public function __construct($code)
	{
		
		$errors = [
			-1		=> 'Not implemented',
			
			0		=> 'OK',
			
			1000	=> 'API Error',
			1001	=> 'API Authentification error',
			1002	=> 'Method not found',
			
			2001	=> 'User creation error',
			2002	=> 'User update error',
			2003	=> 'User not found',
			2004	=> 'Wrong user token',
			2005	=> 'Wrong password',
			
			3001	=> 'Request creation error',
			3002	=> 'Request update error',
			3003	=> 'Request not found',
			3004	=> 'Address creation error',
			3005	=> 'Comment creation error',
		];
		
		$this->code = $code;
		$this->text = $errors[$code];
	}
}
