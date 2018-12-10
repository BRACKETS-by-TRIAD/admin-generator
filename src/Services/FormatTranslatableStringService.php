<?php

namespace Brackets\AdminGenerator\Services;

class FormatTranslatableStringService
{
    /**
     * Format string as value and remove 'id' if it's in the end.
     *
     * @param $string
     * @return string
     */
    public function valueWithoutId($string)
    {
        $pieces = explode('_', $string);
        $value = ucfirst(str_replace('_', ' ', $string));

        if (strtolower(array_pop($pieces)) === 'id') {
            return preg_replace('/\W\w+\s*(\W*)$/', '$1', $value);
        } else {
            return $value;
        }
    }
}
