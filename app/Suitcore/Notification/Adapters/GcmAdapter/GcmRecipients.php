<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

use Suitcore\Notification\Adapters\GcmAdapter\GcmNotifierAdapter as Adapter;
use Suitcore\Notification\Contracts\NotifierRecipientsInterface as Recipients;
use Suitcore\Notification\Adapters\GcmAdapter\GcmRecipientInterface;
use Illuminate\Support\Collection;

/**
* 
*/
class GcmRecipients implements Recipients
{
	protected $recipients = [];	

	public function setRecipients($recipients)
	{		
		// if not array recipients and not collection, then make them array
		if (!is_array($recipients) && ! $recipients instanceof Collection) $recipients = [$recipients];

    	foreach ($recipients as $recipient) {
    		// make sure they implement GcmRecipientInterface interface
    		if ($recipient instanceof GcmRecipientInterface)
    		{
    			if (count($recipient->gcmIds) == 0) continue;
    			foreach ($recipient->gcmIds as $gcmId) {
	    			$this->recipients[] = $gcmId;
	    			$gcmId->updateLastUsed();
    			}

    		}	
    	}
    
    	return $this;
	}

	/**
	 * [getRecipientObj description]
	 * @param  integer $index [description]
	 * @param  [type]  $limit [description]
	 * @return [type]         [description]
	 */
	public function getRecipientObj($index = 0)
	{
		return $this->recipients[$index];
	}

	/**
	 * [getRecipientObjs description]
	 * @param  integer $index [description]
	 * @param  [type]  $limit [description]
	 * @return [type]         [description]
	 */
	public function getRecipientObjs($index = 0, $limit = null)
	{
		return array_slice($this->recipients, $index, $limit);
	}

	/**
	 * [getRecipients description]
	 * @param  integer $index [description]
	 * @param  [type]  $limit [description]
	 * @return [type]         [description]
	 */
	public function getRecipients($index = 0, $limit = null)
	{
		$result = [];

		foreach ($this->getRecipientObjs($index, $limit) as $item) {
			$result[] = $item->gcm_registration_id;
		}	

		return $result;
	}

	public function toArray()
	{
		return $this->getRecipients();
	}
}