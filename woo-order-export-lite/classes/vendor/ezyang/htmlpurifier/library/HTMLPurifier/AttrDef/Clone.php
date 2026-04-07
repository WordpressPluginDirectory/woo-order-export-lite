<?php

/**
 * Dummy AttrDef that mimics another AttrDef, BUT it generates clones
 * with make.
 */
class WOE_HTMLPurifier_AttrDef_Clone extends WOE_HTMLPurifier_AttrDef
{
    /**
     * What we're cloning.
     * @type WOE_HTMLPurifier_AttrDef
     */
    protected $clone;

    /**
     * @param WOE_HTMLPurifier_AttrDef $clone
     */
    public function __construct($clone)
    {
        $this->clone = $clone;
    }

    /**
     * @param string $v
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool|string
     */
    public function validate($v, $config, $context)
    {
        return $this->clone->validate($v, $config, $context);
    }

    /**
     * @param string $string
     * @return WOE_HTMLPurifier_AttrDef
     */
    public function make($string)
    {
        return clone $this->clone;
    }
}

// vim: et sw=4 sts=4
