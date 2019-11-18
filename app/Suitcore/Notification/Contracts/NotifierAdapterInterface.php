<?php

namespace Suitcore\Notification\Contracts;

/**
*  	
*/
interface  NotifierAdapterInterface
{
	public function send($recipients, $message);
}