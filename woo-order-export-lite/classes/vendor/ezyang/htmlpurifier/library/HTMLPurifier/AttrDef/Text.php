<?php

/**
 * Validates arbitrary text according to the HTML spec.
 */
class WOE_HTMLPurifier_AttrDef_Text extends WOE_HTMLPurifier_AttrDef
{

    /**
     * @param string $string
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool|string
     */
    public function validate($string, $config, $context)
    {
        return $this->parseCDATA($string);
    }
}

// vim: et sw=4 sts=4
