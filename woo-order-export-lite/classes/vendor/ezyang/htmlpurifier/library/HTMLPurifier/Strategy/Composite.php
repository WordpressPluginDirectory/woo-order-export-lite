<?php

/**
 * Composite strategy that runs multiple strategies on tokens.
 */
abstract class WOE_HTMLPurifier_Strategy_Composite extends WOE_HTMLPurifier_Strategy
{

    /**
     * List of strategies to run tokens through.
     * @type WOE_HTMLPurifier_Strategy[]
     */
    protected $strategies = array();

    /**
     * @param WOE_HTMLPurifier_Token[] $tokens
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return WOE_HTMLPurifier_Token[]
     */
    public function execute($tokens, $config, $context)
    {
        foreach ($this->strategies as $strategy) {
            $tokens = $strategy->execute($tokens, $config, $context);
        }
        return $tokens;
    }
}

// vim: et sw=4 sts=4
