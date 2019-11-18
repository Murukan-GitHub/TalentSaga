<?php

namespace Suitcore\Models;

/**
 * This class aim to remove null value from array value.
 *
 * Note: If you think I put this trait in wrong place, feel free to move it elsewhere, cheers ;)
 */
trait TransformableToApi
{
    /**
     * This method is simply remove null value from array data.
     *
     * @return array
     */
    public function toApi()
    {
        $output = [];

        foreach ($this->toArray() as $key => $value) {
            $output[$key] = $value !== null ? $value : '';
        }

        return $output;
    }
}