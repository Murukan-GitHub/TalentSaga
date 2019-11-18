<?php 

namespace Suitcore\Notification;

use Suitcore\Notification\Adapters\EmailAdapter\EmailNotifierAdapter as Adapter;
use Suitcore\Notification\Adapters\EmailAdapter\EmailRecipients as Recipients;
use Suitcore\Notification\Adapters\EmailAdapter\EmailMessage as Message;

class EmailNotifier extends Notifier 
{

	public function __construct(Adapter $adapter, Recipients $recipients, Message $message)
	{
		parent::__construct($adapter, $recipients, $message);
	}

} 
