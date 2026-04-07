<?php

/**
 * Definition that disallows all elements.
 * @warning validateChildren() in this class is actually never called, because
 *          empty elements are corrected in WOE_HTMLPurifier_Strategy_MakeWellFormed
 *          before child definitions are parsed in earnest by
 *          WOE_HTMLPurifier_Strategy_FixNesting.
 */
class WOE_HTMLPurifier_ChildDef_Empty extends WOE_HTMLPurifier_ChildDef
{
    /**
     * @type bool
     */
    public $allow_empty = true;

    /**
     * @type string
     */
    public $type = 'empty';

    public function __construct()
    {
    }

    /**
     * @param WOE_HTMLPurifier_Node[] $children
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function validateChildren($children, $config, $context)
    {
        return array();
    }
}

// vim: et sw=4 sts=4
