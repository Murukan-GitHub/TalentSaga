<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

use Suitcore\Notification\GcmNotifier;

trait GcmPusherTrait
{
	public function pushTo($recipient, $meta = [], $config = [])
	{
		app(GcmNotifier::class)->send($recipient, $this, $meta, $config);
		
		return $this;
	}

	// Gcm Message format
    public function getGcmMessage()
    {
        return $this->toArray();
    }

}