<?php

class WOE_HTMLPurifier_URIFilter_DisableResources extends WOE_HTMLPurifier_URIFilter
{
    /**
     * @type string
     */
    public $name = 'DisableResources';

    /**
     * @param WOE_HTMLPurifier_URI $uri
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool
     */
    public function filter(&$uri, $config, $context)
    {
        return !$context->get('EmbeddedURI', true);
    }
}

// vim: et sw=4 sts=4
