<?php

/**
 * Supertype for classes that define a strategy for modifying/purifying tokens.
 *
 * While WOE_HTMLPurifier's core purpose is fixing HTML into something proper,
 * strategies provide plug points for extra configuration or even extra
 * features, such as custom tags, custom parsing of text, etc.
 */


abstract class WOE_HTMLPurifier_Strategy
{

    /**
     * Executes the strategy on the tokens.
     *
     * @param WOE_HTMLPurifier_Token[] $tokens Array of WOE_HTMLPurifier_Token objects to be operated on.
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return WOE_HTMLPurifier_Token[] Processed array of token objects.
     */
    abstract public function execute($tokens, $config, $context);
}

// vim: et sw=4 sts=4
