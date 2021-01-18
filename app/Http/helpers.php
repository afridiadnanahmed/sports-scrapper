<?php

/**
 * Strip whitespace (or other characters) from  a string
 * @param string $str <p>
 * The string that will be trimmed.
 * </p>
 * @return string The trimmed string.
 */
    function sTrim($str = ''){
        return trim(preg_replace('/\s+/', ' ', $str));
    }


