<?php

/**
 * Pre-transform that changes deprecated bgcolor attribute to CSS.
 */
class WOE_HTMLPurifier_AttrTransform_BgColor extends WOE_HTMLPurifier_AttrTransform
{
    /**
     * @param array $attr
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['bgcolor'])) {
            return $attr;
        }

        $bgcolor = $this->confiscateAttr($attr, 'bgcolor');
        // some validation should happen here

        $this->prependCSS($attr, "background-color:$bgcolor;");
        return $attr;
    }
}

// vim: et sw=4 sts=4
