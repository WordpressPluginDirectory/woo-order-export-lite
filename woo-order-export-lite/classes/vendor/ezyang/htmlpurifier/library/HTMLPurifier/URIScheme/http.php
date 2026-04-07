<?php

/**
 * Validates http (HyperText Transfer Protocol) as defined by RFC 2616
 */
class WOE_HTMLPurifier_URIScheme_http extends WOE_HTMLPurifier_URIScheme
{
    /**
     * @type int
     */
    public $default_port = 80;

    /**
     * @type bool
     */
    public $browsable = true;

    /**
     * @type bool
     */
    public $hierarchical = true;

    /**
     * @param WOE_HTMLPurifier_URI $uri
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool
     */
    public function doValidate(&$uri, $config, $context)
    {
        $uri->userinfo = null;
        return true;
    }
}

// vim: et sw=4 sts=4
