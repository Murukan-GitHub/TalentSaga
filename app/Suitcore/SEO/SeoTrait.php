<?php

namespace Suitcore\SEO;

// use it in any controllers
trait SeoTrait
{
    public function seo($object, array $attributes = [], $isArray = false)
    {
        $metas = [];
        foreach ($attributes as $key => $value) {
            $metas[$key] = $isArray ? $object[$value] : $object->{$value};
            if ($metas[$key] == null) {
                unset($metas[$key]);
            }
        }

        view()->share($metas);
    }

    public function seoRaw(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if ($value == null) {
                unset($attributes[$key]);
            }
        }

        view()->share($attributes);
    }
}
