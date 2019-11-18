<?php

namespace Suitcore\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Input;
use HTML;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	/*
	 * Method to strip tags globally.
	 */
	public static function globalXssClean()
	{
	    // Recursive cleaning for array [] inputs, not just strings.
	    $sanitized = static::arrayStripTags(Input::get());
	    Input::merge($sanitized);
	}

    public static function arrayStripTags($array)
    {
        $result = array();

        foreach ($array as $key => $value) {
            // Don't allow tags on key either, maybe useful for dynamic forms.
	        $key = HTML::entities($key);

	        // If the value is an array, we will just recurse back into the
	        // function to keep stripping the tags out of the array,
	        // otherwise we will set the stripped value.
	        if (is_array($value)) {
	            $result[$key] = static::arrayStripTags($value);
	        } else {
	            // I am using strip_tags(), you may use htmlentities(),
	            // also I am doing trim() here, you may remove it, if you wish.
	            $result[$key] = trim(HTML::entities($value));
	        }
        }

        return $result;
    }
}
