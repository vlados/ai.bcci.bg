<?php

namespace App\Concerns;

/*
| Rich-text fields carry markup and line breaks; RSS descriptions, llms.txt
| bullets and meta tags all need the same thing — one clean line of prose.
*/
trait FlattensText
{
    protected function oneLine(?string $text): string
    {
        return trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)));
    }
}
