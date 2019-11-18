<?php 

namespace Suitcore\SocialMediaGrab\Contract;

interface SocialMediaGrabInterface
{
    public function fetchFacebookPost($post, $tag, &$savedPost);

    public function fetchTwitterPost($post, $tag, &$savedPost);

    public function fetchInstagramPost($post, $tag, &$savedPost);
}
