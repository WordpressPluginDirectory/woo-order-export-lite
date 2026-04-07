<?php

/**
 * Core strategy composed of the big four strategies.
 */
class WOE_HTMLPurifier_Strategy_Core extends WOE_HTMLPurifier_Strategy_Composite
{
    public function __construct()
    {
        $this->strategies[] = new WOE_HTMLPurifier_Strategy_RemoveForeignElements();
        $this->strategies[] = new WOE_HTMLPurifier_Strategy_MakeWellFormed();
        $this->strategies[] = new WOE_HTMLPurifier_Strategy_FixNesting();
        $this->strategies[] = new WOE_HTMLPurifier_Strategy_ValidateAttributes();
    }
}

// vim: et sw=4 sts=4
