<?php

/**
 * Implements required attribute stipulation for <script>
 */
class WOE_HTMLPurifier_AttrTransform_ScriptRequired extends WOE_HTMLPurifier_AttrTransform
{
    /**
     * @param array $attr
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr['type'])) {
            $attr['type'] = 'text/javascript';
        }
        return $attr;
    }
}

// vim: et sw=4 sts=4
