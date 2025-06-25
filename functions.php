<?php

/**
 * Sanitize a string for security before template use.
 * This is in addition to twig default sanitizinf for cases
 * where we may want to disable it.
 */
function secureText(string $string): string
{
    // CRLF XSS
    $string = str_replace(['%0D', '%0A'], '', $string);
    // We want to convert line breaks into spaces
    $string = str_replace("\n", ' ', $string);
    // Escape HTML tags and remove ASCII characters below 32
    return filter_var(
        $string,
        FILTER_SANITIZE_SPECIAL_CHARS,
        FILTER_FLAG_STRIP_LOW
    );
}

function getJson(string $url): array
{
    $data = @file_get_contents($url);

    return empty($data)
        ? []
        : json_decode($data, true, 512, JSON_THROW_ON_ERROR);
}
