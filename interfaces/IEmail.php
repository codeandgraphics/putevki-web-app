<?php

namespace Interfaces;

interface IEmail
{
	public function send($to, $subject, $message);
}