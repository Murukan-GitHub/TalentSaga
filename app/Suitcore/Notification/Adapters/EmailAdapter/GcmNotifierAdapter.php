<?php 

namespace Suitcore\Notification\Adapters\EmailAdapter;

use Suitcore\Notification\Contracts\NotifierAdapterInterface as Adapter;
use Suitcore\Notification\Adapters\EmailAdapter\EmailRecipients as Recipients;
use Suitcore\Notification\Contracts\NotifierMessageInterface as Message;
use Illuminate\Foundation\Bus\DispatchesJobs;
use GuzzleHttp\Exception\BadResponseException;
use Suitcore\Jobs\SendingNotification;
use Illuminate\Support\Collection;

/**
* 
*/
class EmailNotifierAdapter implements Adapter
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
		// if not array, make it array
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

    	$message['registration_ids'] = $recipients->getRecipients('EmailRegistrationId');

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
		}

	    if ('200' != ($status = $res->getStatusCode())) $response = ['error' => $status, 'response' => $res->getResponse];

	    $response = ['status' => 'ok', 'response' => json_decode($res->getBody())];

	    $this->responseFromQueue($recipients, $response);

	    return $response;
	}

	public function responseFromQueue(Recipients $recipients, $response)
	{
		var_dump($recipients);
		var_dump($response);
	}
}