<?php

namespace Suitcore\FileGrabber;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Collection;
use Suitcore\File\DummyFile;
use Suitcore\File\File;

class FileGrabber
{
    protected static $temp;

    protected static $chmod = 0755;

    protected static $prefix = 'grabbed';

    public function setTemp($temp, $chmod = null)
    {
        if (! file_exists($temp)) {
            mkdir($temp, $chmod ?: static::$chmod, true);
        }

        static::$temp = $temp;
    }

    public function setPrefix($prefix)
    {
        static::$prefix = $prefix;
    }

    public function setChmod($chmod)
    {
        static::$chmod = $chmod;
    }

    public function from($url, $isStream = false)
    {
        $tmpfile = tempnam(static::$temp, static::$prefix);
        $resource = fopen($tmpfile, 'w');
        $stream = Psr7\stream_for($resource);

        try {
            $client = new Client;
            $res = $client->request('GET', $url, ['sink' => $stream]);
            return new File($tmpfile);
        } catch (RequestException $e) {
            return false;
        }
    }
}
