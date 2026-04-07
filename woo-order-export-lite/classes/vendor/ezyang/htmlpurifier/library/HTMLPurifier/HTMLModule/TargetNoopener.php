<?php

/**
 * Module adds the target-based noopener attribute transformation to a tags.  It
 * is enabled by HTML.TargetNoopener
 */
class WOE_HTMLPurifier_HTMLModule_TargetNoopener extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'TargetNoopener';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config) {
        $a = $this->addBlankElement('a');
        $a->attr_transform_post[] = new WOE_HTMLPurifier_AttrTransform_TargetNoopener();
    }
}
