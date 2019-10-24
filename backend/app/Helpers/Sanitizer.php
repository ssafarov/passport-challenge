<?php

    namespace App\Helpers;

    class Sanitizer
    {
        public static function cleanArray($array)
        {
            $result = [];
            foreach ($array as $key => $value) {
                $key = strip_tags($key);
                if (is_array($value)) {
                    $result[$key] = static::cleanArray($value);
                } else {
                    $result[$key] = trim(strip_tags($value));
                }
            }
            return $result;
        }

    }
