<?php


namespace App\Service;


class Slugify
{
    public function generate(string $input) : string
    {
        $dash = str_replace(" ", "-", $input);
        $dash = preg_replace('/[-]{2,}/', '-', $dash);
        $blanck = preg_replace('/\s{1,}/', '', $dash);

        // à, ç... => a, c
        $utf8 = array(
            '/[áàâãªä]/u' => 'a',
            '/[ÁÀÂÃÄ]/u' => 'A',
            '/[ÍÌÎÏ]/u' => 'I',
            '/[íìîï]/u' => 'i',
            '/[éèêë]/u' => 'e',
            '/[ÉÈÊË]/u' => 'E',
            '/[óòôõºö]/u' => 'o',
            '/[ÓÒÔÕÖ]/u' => 'O',
            '/[úùûü]/u' => 'u',
            '/[ÚÙÛÜ]/u' => 'U',
            '/ç/' => 'c',
            '/Ç/' => 'C',
            '/ñ/' => 'n',
            '/Ñ/' => 'N'
        );
        $utf = preg_replace(array_keys($utf8), array_values($utf8), $blanck);

        // !, ? ... => delete
        $pattern = '/[\.,\/#!$%\^&\*;:{}=\_`~()@\+\?><\[\]\+]/';
        $punctuation = preg_replace($pattern, "", $utf);

        // string to lower case & trim dash start and end string
        return strtolower(rtrim(ltrim($punctuation, '-'), '-'));
    }

}