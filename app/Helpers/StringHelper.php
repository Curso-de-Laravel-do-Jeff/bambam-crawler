<?php

namespace App\Helpers;

class StringHelper
{
    public static function doRegex(string $text, $rule)
    {
        preg_match_all($rule, $text, $result);

        return $result;
    }

    public static function clearPageContent(string $text)
    {
        $rules = [
            "\r\n",
            "\n",
            "\r",
            "\t",
            '&nbsp;',
        ];

        return trim(str_replace($rules, ' ', $text));
    }

    public static function replaceRegex(string $text, $regex, string $replace)
    {
        return preg_replace($regex, $replace, $text);
    }
}
