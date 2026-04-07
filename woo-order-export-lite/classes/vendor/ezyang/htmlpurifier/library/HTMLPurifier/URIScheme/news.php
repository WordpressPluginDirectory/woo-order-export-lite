<?php

/**
 * Validates news (Usenet) as defined by generic RFC 1738
 */
class WOE_HTMLPurifier_URIScheme_news extends WOE_HTMLPurifier_URIScheme
{
    /**
     * @type bool
     */
    public $browsable = false;

    /**
     * @type bool
     */
    public $may_omit_host = true;

    /**
     * @param WOE_HTMLPurifier_URI $uri
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool
     */
    public function doValidate(&$uri, $config, $context)
    {
        $uri->userinfo = null;
        $uri->host = null;
        $uri->port = null;
        $uri->query = null;
        // typecode check needed on path
        return true;
    }
}

// vim: et sw=4 sts=4
