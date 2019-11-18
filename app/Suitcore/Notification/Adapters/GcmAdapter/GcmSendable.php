<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

interface GcmSendable
{
	public function pushTo($recipient, $meta, $config);
	public function getGcmMessage();
}