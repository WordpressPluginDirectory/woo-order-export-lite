<?php

/**
 * Module adds the target=blank attribute transformation to a tags.  It
 * is enabled by HTML.TargetBlank
 */
class WOE_HTMLPurifier_HTMLModule_TargetBlank extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'TargetBlank';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new WOE_HTMLPurifier_AttrTransform_TargetBlank();
    }
}

// vim: et sw=4 sts=4
