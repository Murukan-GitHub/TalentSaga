<?php 

namespace Suitcore\Notification;

use Suitcore\Notification\Adapters\GcmAdapter\GcmNotifierAdapter as Adapter;
use Suitcore\Notification\Adapters\GcmAdapter\GcmRecipients as Recipients;
use Suitcore\Notification\Adapters\GcmAdapter\GcmMessage as Message;

class GcmNotifier extends Notifier 
{

	public function __construct(Adapter $adapter, Recipients $recipients, Message $message)
	{
		parent::__construct($adapter, $recipients, $message);
	}

} 
