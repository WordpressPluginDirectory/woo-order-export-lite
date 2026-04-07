<?php

/**
 * Module adds the nofollow attribute transformation to a tags.  It
 * is enabled by HTML.Nofollow
 */
class WOE_HTMLPurifier_HTMLModule_Nofollow extends WOE_HTMLPurifier_HTMLModule
{

    /**
     * @type string
     */
    public $name = 'Nofollow';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new WOE_HTMLPurifier_AttrTransform_Nofollow();
    }
}

// vim: et sw=4 sts=4
