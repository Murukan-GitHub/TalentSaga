<?php 

namespace Suitcore\Notification;

use Suitcore\Notification\Contracts\NotifierAdapterInterface as Adapter;
use Suitcore\Notification\Contracts\NotifierRecipientsInterface as Recipients;
use Suitcore\Notification\Contracts\NotifierMessageInterface as Message;


class Notifier 
{
	protected $adapter;
	protected $message;
	protected $recipients;

	public function __construct(Adapter $adapter, Recipients $recipients, Message $message)
	{
		$this->adapter = $adapter;
		$this->recipients = $recipients;
		$this->message = $message;
	}

	public function send($recipients, $message, $meta = [], $config = [])
	{
		$message = $this->message->setMessage($message, $meta, $config, $this->adapter);

		return $this->adapter->send($recipients, $message);
	}

	public function getRecipientsObj()
	{
	// 	return $this->recipients;
	}

	public function getMessageObj()
	{
	// 	return $this->message;;
	}
} 
