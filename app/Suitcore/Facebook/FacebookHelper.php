<?php

namespace Suitcore\Facebook;

use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;
use Illuminate\Support\Collection;
use Log;
use Config;
use CURLFile;

class FacebookHelper
{
    public function getStatusFeedback($accessToken)
    {
        $session = new FacebookSession($accessToken);

        if($session) {

            try {
                $feed = [];

                $request = new FacebookRequest(
                    $session,
                    'GET',
                    '/me/statuses',
                    ['fields' => 'sharedposts,likes,comments']
                );
                $response = $request->execute();
                $response = $this->decodeResponse($response->getRawResponse());
                $feed += $response['data'];

                $i = 0;

                while((count($feed) < 500) && ($i < 20) && isset($response['paging']['next']))
                {
                    $response = file_get_contents($response['paging']['next']);
                    $response = $this->decodeResponse($response);
                    $feed = array_merge($feed, $response['data']);
                    $i++;
                }

                return $this->formatFeedback($feed);

            } catch(FacebookRequestException $e) {

                Log::error($e->getMessage());
                return false;
            }

        }

        return false;
    }

    public function getFriends($accessToken)
    {
        $session = new FacebookSession($accessToken);

        if($session) {

            try {

                $request = new FacebookRequest(
                    $session,
                    'GET',
                    '/me/taggable_friends',
                    ['fields' => 'id, name, picture.width(200).height(200)']
                );
                $response = $request->execute()->getRawResponse();
                $response = json_decode($response, true);
                $graph['data'] = $response['data'];

                $friends = [];

                while(isset($response['paging']['next']))
                {
                    $response = file_get_contents($response['paging']['next']);
                    $response = json_decode($response, true);
                    $graph['data'] = array_merge($graph['data'], $response['data']) ;
                }

                foreach($graph['data'] as $item)
                {
                    $friends[] = [
                        'id'        => $item['id'],
                        'name'      => $item['name'],
                        'avatar'    => $item['picture']['data']['url']
                    ];
                }

                return new Collection($friends);

            } catch(FacebookRequestException $e) {

                Log::error($e->getMessage());
                return new Collection();
            }
        }

        return new Collection();

    }

    public function getProfile($accessToken)
    {
        $session = new FacebookSession($accessToken);

        if($session) {

            try {

                $profile = (new FacebookRequest(
                    $session, 'GET', '/me'
                ))->execute()->getGraphObject(GraphUser::className());

                return $profile->asArray();

            } catch(FacebookRequestException $e) {

                Log::error($e->getMessage());
                return false;
            }
        }

        return false;
    }

    public function postPhoto($accessToken, $url, $message, $ids)
    {
        $session = new FacebookSession($accessToken);

        $tags = [];
        foreach ($ids as $tagId)
        {
            $tags[] = ['tag_uid' => $tagId];
        }

        if($session) {

            try {

                $request = new FacebookRequest(
                    $session,
                    'POST',
                    '/me/photos',
                    [
                        'url'    => $url,
                        'message'   => $message,
                        'tags'      => json_encode($tags)
                    ]
                );
                $response = $request->execute()->getGraphObject();

                return $response;

            } catch(FacebookRequestException $e) {
                Log::error($e->getMessage());
                return false;
            }
        }

        return false;

    }

    public function isLiked($accessToken, $pageId)
    {
        $session = new FacebookSession($accessToken);

        if($session) {

            try {

                $response = (new FacebookRequest(
                    $session, 'GET', '/me/likes/' . $pageId
                ))->execute()->getGraphObject()->asArray();

                return !empty($response['data']);

            } catch(FacebookRequestException $e) {
                Log::error($e->getMessage());
                return false;
            }
        }

        return false;
    }

    public function parseSignedRequest($signedRequest)
    {
        list($encoded_sig, $payload) = explode('.', $signedRequest, 2);

        $secret = Config::get('facebook.secret');

        // decode the data
        $sig  = $this->base64UrlDecode($encoded_sig);
        $data = json_decode($this->base64UrlDecode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public function trimName($name)
    {
        return preg_replace("/[^A-Za-z0-9 ]/", "", $name);
    }

    protected function base64UrlDecode($input)
    {
        return base64_decode(strtr($input, '-_', '+/'));
    }

    protected function decodeResponse($response)
    {
        return json_decode($response, true);
    }

    protected function formatFeedback($feed)
    {
        $data = [];
        foreach($feed as $post)
        {
            $comments = $likes = $sharedposts = [];

            if(isset($post['comments']['data']))
            {
                $r = $post['comments'];
                $temp = $r['data'];
                while(isset($r['paging']['next']))
                {
                    $r = $this->decodeResponse(file_get_contents($post['comments']['paging']['next']));
                    if(isset($r['data']) && !empty($r['data']))
                    {
                        $temp = array_merge($temp, $r['data']);
                    }
                }
                $post['comments']['data'] = $temp;

                foreach($post['comments']['data'] as $comment)
                {
                    if(isset($comment['from']['name']))
                    {
                        if(!isset($comments[$comment['from']['name']]))
                        {
                            $comments[$comment['from']['name']] = 0;
                        }
                        $comments[$comment['from']['name']]++;
                    }
                }
            }

            if(isset($post['likes']['data']))
            {
                $r = $post['likes'];
                $temp = $r['data'];
                while(isset($r['paging']['next']))
                {
                    $r = $this->decodeResponse(file_get_contents($post['likes']['paging']['next']));
                    if(isset($r['data']) && !empty($r['data']))
                    {
                        $temp = array_merge($temp, $r['data']);
                    }
                }
                $post['likes']['data'] = $temp;

                foreach($post['likes']['data'] as $like)
                {
                    if(!isset($likes[$like['name']]))
                    {
                        $likes[$like['name']] = 0;
                    }
                    $likes[$like['name']]++;
                }
            }

//            if(isset($post['sharedposts']['data']))
//            {
//                $r = $post['sharedposts'];
//                $temp = $r['data'];
//                while(isset($r['paging']['next']))
//                {
//                    $r = $this->decodeResponse(file_get_contents($post['sharedposts']['paging']['next']));
//                    if(isset($r['data']) && !empty($r['data']))
//                    {
//                        $temp = array_merge($temp, $r['data']);
//                    }
//                }
//                $post['sharedposts']['data'] = $temp;
//
//                foreach($post['sharedposts']['data'] as $share)
//                {
//                    if(!isset($sharedposts[$share['from']['name']]))
//                    {
//                        $sharedposts[$share['from']['name']] = 0;
//                    }
//                    $sharedposts[$share['from']['name']]++;
//                }
//            }

            $data[] = ['comments' => $comments, 'likes' => $likes, 'sharedposts' => $sharedposts];
        }

        return $data;
    }

    protected function isCurlFileExist()
    {
        return class_exists('CurlFile', false);
    }

    protected function getMimeType($image)
    {
        return image_type_to_mime_type(exif_imagetype($image));
    }
}