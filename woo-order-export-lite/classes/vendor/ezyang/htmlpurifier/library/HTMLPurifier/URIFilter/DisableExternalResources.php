<?php

class WOE_HTMLPurifier_URIFilter_DisableExternalResources extends WOE_HTMLPurifier_URIFilter_DisableExternal
{
    /**
     * @type string
     */
    public $name = 'DisableExternalResources';

    /**
     * @param WOE_HTMLPurifier_URI $uri
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool
     */
    public function filter(&$uri, $config, $context)
    {
        if (!$context->get('EmbeddedURI', true)) {
            return true;
        }
        return parent::filter($uri, $config, $context);
    }
}

// vim: et sw=4 sts=4
