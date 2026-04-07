<?php

/**
 * @file
 * Defines a function wrapper for HTML Purifier for quick use.
 * @note ''WOE_HTMLPurifier()'' is NOT the same as ''new WOE_HTMLPurifier()''
 */

/**
 * Purify HTML.
 * @param string $html String HTML to purify
 * @param mixed $config Configuration to use, can be any value accepted by
 *        WOE_HTMLPurifier_Config::create()
 * @return string
 */
function WOE_HTMLPurifier($html, $config = null)
{
    static $purifier = false;
    if (!$purifier) {
        $purifier = new WOE_HTMLPurifier();
    }
    return $purifier->purify($html, $config);
}

// vim: et sw=4 sts=4
