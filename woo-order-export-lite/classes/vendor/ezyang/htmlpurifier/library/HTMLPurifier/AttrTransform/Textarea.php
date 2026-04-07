<?php

/**
 * Sets height/width defaults for <textarea>
 */
class WOE_HTMLPurifier_AttrTransform_Textarea extends WOE_HTMLPurifier_AttrTransform
{
    /**
     * @param array $attr
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        // Calculated from Firefox
        if (!isset($attr['cols'])) {
            $attr['cols'] = '22';
        }
        if (!isset($attr['rows'])) {
            $attr['rows'] = '3';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
