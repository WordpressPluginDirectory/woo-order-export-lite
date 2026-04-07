<?php

/**
 * Writes default type for all objects. Currently only supports flash.
 */
class WOE_HTMLPurifier_AttrTransform_SafeObject extends WOE_HTMLPurifier_AttrTransform
{
    /**
     * @type string
     */
    public $name = "SafeObject";

    /**
     * @param array $attr
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['type'])) {
            $attr['type'] = 'application/x-shockwave-flash';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
