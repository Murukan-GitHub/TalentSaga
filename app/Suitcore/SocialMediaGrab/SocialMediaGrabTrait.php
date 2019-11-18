<?php

namespace Suitcore\SocialMediaGrab;

use \datetime as datetime;
use Twitter;

trait SocialMediaGrabTrait {

    protected $socialMediaGrabDebug = false;

	/**
	 * Fetch Facebook Media
	 * $object should implements SocialMediaGrabInterface
	 **/
	public function fetchFacebookMedia($tag, $timeLimit, $object)
    {
        // FACEBOOK
        $access_token = env('FACEBOOK_ACCESS_TOKEN','');
        $grandTotalSavedPost = 0;
        if (!empty($access_token)) {
	        $url = 'https://api.facebook.com/v1/search/?q='.$tag.'&access_token='.$access_token;
	        $continueProcess = true;
	        $lastPostTime = $timeLimit;
	        while ($continueProcess && !empty($url)) {
	            $ch = curl_init();
	            curl_setopt_array($ch, [
	                CURLOPT_URL => $url,
	                CURLOPT_RETURNTRANSFER => true,
	                CURLOPT_SSL_VERIFYPEER => false,
	                CURLOPT_SSL_VERIFYHOST => 2
	            ]);
	            $result = curl_exec($ch);
	            curl_close($ch);

	            $decoded_results = json_decode($result, true);
	            if ($this->socialMediaGrabDebug) echo "FACEBOOK - URL : " . $url . "\n";
	            try {
	                if (isset($decoded_results['pagination']) && isset($decoded_results['pagination']['next_url'])) {
	                    $tmpUrl = $decoded_results['pagination']['next_url'];
	                    if ($url != $tmpUrl) $url = $tmpUrl;
	                    else $url = "";
	                } else $url = "";
	            } catch(Exception $e) { $url = ""; }
	            $allPost = 0;
	            $savedPost = 0;
	            if (isset($decoded_results['data']) && is_array($decoded_results['data'])) {
	                foreach ($decoded_results['data'] as $item) {
	                    try {
	                        if ($item['type'] == "image") {
	                        	if ($object != null) {
	                        		$object->fetchFacebookPost($item, $tag, $savedPost);
	                        	}
	                        }
                            // -- Save last post time
                            $lastPostTime = $item['created_time'];
	                    } catch (Exception $e) { }
	                    $allPost++;
	                }
                    $grandTotalSavedPost += $savedPost;
	            }
	            if ($this->socialMediaGrabDebug) echo "FACEBOOK - POST SAVED / TOTAL / GRAND TOTAL : " . $savedPost . " / " . $allPost . " / " . $grandTotalSavedPost . "\n";
	            $continueProcess = ($allPost != 0 && ($lastPostTime >= $timeLimit));
	            if (!$continueProcess) {
	            	if ($this->socialMediaGrabDebug) echo "FACEBOOK - LAST RESULT : \n";
	            	if ($this->socialMediaGrabDebug) echo $result;
	            	if ($this->socialMediaGrabDebug) echo "\nFACEBOOK - END -----\n";
	            }
	            usleep(4000000); // delay for 4 second(s) for better network load performance
	        }
        }
    }

	/**
	 * Fetch Instagram Media
	 * $object should implements SocialMediaGrabInterface
	 **/
	public function fetchInstagramMedia($tag, $timeLimit, $object)
    {
        // INSTAGRAM
        $access_token = env('INSTAGRAM_ACCESS_TOKEN','');
        $grandTotalSavedPost = 0;
        if (!empty($access_token)) {
	        $url = 'https://api.instagram.com/v1/tags/'.$tag.'/media/recent?access_token='.$access_token;
	        $continueProcess = true;
	        $lastPostTime = $timeLimit;
	        while ($continueProcess && !empty($url)) {
	            $ch = curl_init();
	            curl_setopt_array($ch, [
	                CURLOPT_URL => $url,
	                CURLOPT_RETURNTRANSFER => true,
	                CURLOPT_SSL_VERIFYPEER => false,
	                CURLOPT_SSL_VERIFYHOST => 2
	            ]);
	            $result = curl_exec($ch);
	            curl_close($ch);

	            $decoded_results = json_decode($result, true);
	            if ($this->socialMediaGrabDebug) echo "INSTAGRAM - URL : " . $url . "\n";
	            try {
	                if (isset($decoded_results['pagination']) && isset($decoded_results['pagination']['next_url'])) {
	                    $tmpUrl = $decoded_results['pagination']['next_url'];
	                    if ($url != $tmpUrl) $url = $tmpUrl;
	                    else $url = "";
	                } else $url = "";
	            } catch(Exception $e) { $url = ""; }
	            $allPost = 0;
	            $savedPost = 0;
	            if (isset($decoded_results['data']) && is_array($decoded_results['data'])) {
	                foreach ($decoded_results['data'] as $item) {
	                    try {
	                        if ($item['type'] == "image") {
	                        	if ($object != null) {
	                        		$object->fetchInstagramPost($item, $tag, $savedPost);
	                        	}
	                        }
                            // -- Save last post time
                            $lastPostTime = $item['created_time'];
	                    } catch (Exception $e) { }
	                    $allPost++;
	                }
                    $grandTotalSavedPost += $savedPost;
	            }
	            if ($this->socialMediaGrabDebug) echo "INSTAGRAM - POST SAVED / TOTAL / GRAND TOTAL : " . $savedPost . " / " . $allPost . " / " . $grandTotalSavedPost . "\n";
	            $continueProcess = ($allPost != 0 && ($lastPostTime >= $timeLimit));
	            if (!$continueProcess) {
	            	if ($this->socialMediaGrabDebug) echo "INSTAGRAM - LAST RESULT : \n";
	            	if ($this->socialMediaGrabDebug) echo $result;
	            	if ($this->socialMediaGrabDebug) echo "\nINSTAGRAM - END -----\n";
	            }
	            usleep(4000000); // delay for 4 second(s) for better network load performance
	        }
        }
    }

    /**
     * Fetch Twitter Media
	 * $object should implements SocialMediaGrabInterface
	 **/
	public function fetchTwitterMedia($tag, $timeLimit, $object)
    {
        // TWITTER
        $continueProcess = true;
        $nextId = 1;
        $lastPostTime = $timeLimit;
        $grandTotalSavedPost = 0;
        while ($continueProcess && ($nextId > 0)) {
            $param = ['q'=>"#".$tag, 'count' => 20, 'format' => 'json'];
            if ($nextId > 1) {
                $param['since_id'] = $nextId;
            }
            $result = Twitter::getSearch($param);
            $decoded_results = json_decode($result, true);
            if ($this->socialMediaGrabDebug) echo "TWITTER - NEXT ID : " . $nextId . "\n";
            try {
                if (isset($decoded_results['search_metadata']) && isset($decoded_results['search_metadata']['max_id'])) {
                    $tmpNextId = $decoded_results['search_metadata']['max_id'];
                    if ($nextId != $tmpNextId) $nextId = $tmpNextId;
                    else $nextId = 0;
                } else $nextId = 0;
            } catch(Exception $e) { $nextId = 0; }
            $allPost = 0;
            $savedPost = 0;
            if (isset($decoded_results['statuses']) && is_array($decoded_results['statuses'])) {
                foreach ($decoded_results['statuses'] as $item) {
                    try {
                        if (!$item['retweeted'] && isset($item['entities']) 
                            && isset($item['entities']['user_mentions']) 
                            && is_array($item['entities']['user_mentions'])
                            && count($item['entities']['user_mentions']) > 0 
                            && isset($item['entities']['media']) 
                            && is_array($item['entities']['media']) 
                            && count($item['entities']['media']) > 0) {

                            // && $item['entities']['user_mentions'][0]['screen_name'] == "SomeID"

                        	if ($object != null) {
                        		$object->fetchTwitterPost($item, $tag, $savedPost);
                        	}
                        }
                        // -- Save last post time
                        $lastPostTime = strtotime($item['created_at']);
                    } catch (Exception $e) { }
                    $allPost++;
                }
                $grandTotalSavedPost += $savedPost;
            }
            if ($this->socialMediaGrabDebug) echo "TWITTER - POST SAVED / TOTAL / GRAND TOTAL : " . $savedPost . " / " . $allPost . " / " . $grandTotalSavedPost . "\n";
			$continueProcess = ($allPost != 0 && ($lastPostTime >= $timeLimit));
            if (!$continueProcess) {
            	if ($this->socialMediaGrabDebug) echo "TWITTER - LAST RESULT : \n";
            	if ($this->socialMediaGrabDebug) echo $result;
            	if ($this->socialMediaGrabDebug) echo "\nTWITTER - END -----\n";
            }
            usleep(4000000); // delay for 4 second(s) for better network load performance
        }
    }
}
