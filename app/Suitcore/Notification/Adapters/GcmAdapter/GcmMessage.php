<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

use Suitcore\Notification\Adapters\GcmAdapter\GcmNotifierAdapter as Adapter;
use Suitcore\Notification\Contracts\NotifierMessageInterface as Message;
use Illuminate\Support\Collection;

/**
* 
*/
class GcmMessage implements Message
{
	protected $message;

	public function setMessage($message, $meta = [], $config = [], Adapter $adapter)
	{
		// message factory

		// if message is GcmSendable then grab the message only
		if ($message instanceof GcmSendable) $message = $message->getGcmMessage();

		// if has additional config
		$config = array_merge($adapter->getConfig(), $config);
		
		// wrapping message
		
		// get default messsage
    	$messageDefault = $config['message'];

    	// you can use meta to override 
    	$messageWrapper = array_merge($messageDefault, $meta);
	
		// the content of messsage wrapped in data
    	$messageWrapper['data'] = $message;

    	// save as message
    	$this->message = $messageWrapper;

    	return $this;
	}

	public function getMessage()
	{
		return $this->message;
	}

}