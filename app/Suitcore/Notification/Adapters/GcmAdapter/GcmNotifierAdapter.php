<?php 

namespace Suitcore\Notification\Adapters\GcmAdapter;

use Suitcore\Notification\Contracts\NotifierAdapterInterface as Adapter;
use Suitcore\Notification\Adapters\GcmAdapter\GcmRecipients as Recipients;
use Suitcore\Notification\Contracts\NotifierMessageInterface as Message;
use Illuminate\Foundation\Bus\DispatchesJobs;
use GuzzleHttp\Exception\BadResponseException;
use Suitcore\Jobs\SendingNotification;
use Illuminate\Support\Collection;

/**
* 
*/
class GcmNotifierAdapter implements Adapter
{
	use DispatchesJobs;

	protected $config;
	
	public function __construct()
	{
		$this->config = config('notification.gcm');
	}

	public function getConfig()
	{		
		return $this->config;
	}
	
	public function send($recipients, $message)
	{
		// if not arrayy, make it array
		if (! $recipients instanceof Collection) $recipients = collect([$recipients]);

		foreach ($recipients->chunk($this->config['chunk']) as $recipientCollection) {
			$recipients = (new Recipients)->setRecipients($recipientCollection);
			// send to queue as job
    		$this->dispatch(new SendingNotification($this, $recipients, $message));
		}

		// sending to queue
		// return $this->sendToQueue($recipients, $message);
		
	}

	public function sendToQueue(Recipients $recipients, Message $messageObj)
	{
    	$message = $messageObj->getMessage();

    	$message['registration_ids'] = $recipients->getRecipients();

    	if (count($message['registration_ids']) == 0) return;

    	// sending
    	$apiKey = $this->config['apiKey'];
		
		$client = new \GuzzleHttp\Client();
	    
	    try {
			    $res = $client->request( 
			            'POST',
			            'https://gcm-http.googleapis.com/gcm/send',
			            [
			                'headers' => [
			                    'Authorization' => 'key='.$apiKey,
			                    'Content-Type' => 'application/json',
			                ],
			                'body' => json_encode($message)
			            ]
			        );
		} catch (BadResponseException $e) {
		    $response = ['error' => true, 'response' => $e->getMessage()];
	    	$this->responseFromQueue($recipients, $response, $messageObj);
			return $response;
		}

	    $response = ['status' => 'ok', 'response' => json_decode($res->getBody())];

	    $this->responseFromQueue($recipients, $response, $messageObj);

	    return $response;
	}

	protected function changeRegistrationId($recipient, $newRegistrationId)
	{
		if ($recipient instanceof GcmRecipientInterface)
		{
			$recipient->changeGcmRegistrationId($newRegistrationId);
		}
	}

	public function responseFromQueue(Recipients $recipients, $response, $message)
	{

		$responses = $response['response'];

		if ($responses->failure == 0 && $responses->canonical_ids == 0) return;

		foreach ($responses->results as $index => $result) {
			
			$recipient = $recipients->getRecipientObj($index);
			
			if (isset($result->error)) {
				if ($result->error == 'NotRegistered' || $result->error == 'InvalidRegistration') {
					$recipient->delete();
					continue;
				}
			}
			
			if (isset($result->registration_id)) {

				$newRegistrationId = $result->registration_id;
				$recipient->changeGcmRegistrationId($newRegistrationId);
			} 
		}
	}
}