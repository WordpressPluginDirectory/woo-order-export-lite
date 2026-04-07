<?php

/**
 * Validates https (Secure HTTP) according to http scheme.
 */
class WOE_HTMLPurifier_URIScheme_https extends WOE_HTMLPurifier_URIScheme_http
{
    /**
     * @type int
     */
    public $default_port = 443;
    /**
     * @type bool
     */
    public $secure = true;
}

// vim: et sw=4 sts=4
