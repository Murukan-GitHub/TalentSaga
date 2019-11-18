<?php

use Suitcore\Models\SuitSetting;
use App\Config\BaseConfig;
use App\Repositories\TalentCategoryRepository;
use App\Repositories\UserBookingRepository;
use App\Repositories\CityRepository;


if (! function_exists('latestBookings')) {
    function latestBookings($nbFetch = 10)
    {
        $bookingRepo = new UserBookingRepository;
        return $bookingRepo->getLatestCandidates($nbFetch, 1, 1, 1, 1, 1, 5);
    }
}

if (! function_exists('getTalentCategories')) {

    function getTalentCategories($parentId = 0)
    {
        $talentCatRepo = new TalentCategoryRepository;
        return $talentCatRepo->cachedList(false, $parentId);
    }
}

if (! function_exists('getCities')) {

    function getCities($countryId = 0)
    {
        $cityRepo = new CityRepository;
        return $cityRepo->cachedList(false, $countryId);
    }
}

if (! function_exists('settings')) {

    function settings($key, $default = null)
    {
        $setting = app(SuitSetting::class)->getValue($key, null);
        return $setting ?: array_get(BaseConfig::$data, $key, $default);
    }
}

if (! function_exists('updatesetting')) {

    function updatesetting($key, $value)
    {
        SuitSetting::updateByKey($key, $value);
    }
}

if (! function_exists('isEmptyDate')) {

    function isEmptyDate($date)
    {
        return ($date == null || empty($date) || $date == "0000-00-00 00:00:00");
    }
}

if (! function_exists('generateRandomString')) {

    function generateRandomString($length = 5)
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        return substr(str_shuffle(str_repeat($characters, $length)), 0, $length);
    }
}

if (! function_exists('startsWith')) {
    function startsWith($haystack, $needle) {
        // search backwards starting from haystack length characters from the end
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
    }
}

if (! function_exists('endsWith')) {
    function endsWith($haystack, $needle) {
        // search forward starting from end minus needle length characters
        return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
    }
}

if (! function_exists('asCurrency')) {

    function asCurrency($number)
    {
        return 'Rp. '.number_format($number, 2, '.', ',');
    }
}

if (! function_exists('asPhoneNumberDestination')) {

    function asPhoneNumberDestination($text)
    {
        return str_replace(['-', ' '], '', $text);
    }
}

if (! function_exists('getUsernameByEmail')) {

    function getUsernameByEmail($email, $useDot = false) {
        $username = str_replace(substr($email, strpos($email, '@')), '', $email);

        if ($useDot) {
            return $username;
        }

        return str_replace('.', '', $username);
    }
}

if (! function_exists('nav_link')) {
    function nav_link($routes, $text) {
        $link = explode('/', Request::path());
        foreach($routes as $route){
            if ($link[1] == $route){
                $active = "class = 'active'";
                break;
            } else
                $active = '';
        }
        return '<li '.$active.'><a href="'.url('admin/'.$route.'').'"><i class="fa fa-folder"></i><span>'.$text.'</span></a></li>';
    }
}

if (! function_exists('nav_menu')) {
    function nav_menu($url, $text, $iconString = null, $class = "btn btn--blue", $title = "") {
        return '<a class="'.$class.'" href="' . $url . '" title="'.$title.'">'.($iconString != null ? '<i class="' . $iconString . '"></i>' : '').' '.$text.'</a>';
    }
}

if (! function_exists('post_nav_menu')) {
    function post_nav_menu($url, $text, $token, $confirmText = null, $iconString = null, $class = "btn btn--red", $title = "") {
        return '<form style="display:inline;" method="post" action="' . $url . '"><input type="hidden" name="_token" value="' . $token . '"><button type="submit" class="'.$class.'" '.(!empty($confirmText) ? 'onClick="return confirm(\''. $confirmText .'\');"' : '').' title="'.$title.'">'.($iconString != null ? '<i class="' . $iconString . '"></i>' : '').' '.$text.'</button></form>';
    }
}

if (! function_exists('menu_items_by_type')) {
    function menu_items_by_type($type) {
        $result = "";
        foreach (MenuItem::getActiveMenus($type) as $menu) {
            $result .= "<li><a href='".url($menu->url)."'>".$menu->title."</a></li>";
        }
        return $result;
    }
}

if (!function_exists('str_text_beautifier')) {
    /**
     * [str_text_beautifier description]
     * @param  [type] $text [description]
     * @return [type]       [description]
     */
    function str_text_beautifier($text)
    {
        if (strpos($text, '-')) {

            $texts = explode('-', $text);

            while (list($key, $val) = each($texts)) {
                $texts[$key] = ucfirst(strtolower(str_replace('_', ' ', $val)));
            }

            return implode(' & ', $texts);
        } else {
            return ucfirst(strtolower(str_replace('_', ' ', $text)));
        }
    }
}

