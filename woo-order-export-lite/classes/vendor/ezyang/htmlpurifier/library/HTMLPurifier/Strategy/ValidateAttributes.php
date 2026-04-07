<?php

/**
 * Validate all attributes in the tokens.
 */

class WOE_HTMLPurifier_Strategy_ValidateAttributes extends WOE_HTMLPurifier_Strategy
{

    /**
     * @param WOE_HTMLPurifier_Token[] $tokens
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return WOE_HTMLPurifier_Token[]
     */
    public function execute($tokens, $config, $context)
    {
        // setup validator
        $validator = new WOE_HTMLPurifier_AttrValidator();

        $token = false;
        $context->register('CurrentToken', $token);

        foreach ($tokens as $key => $token) {

            // only process tokens that have attributes,
            //   namely start and empty tags
            if (!$token instanceof WOE_HTMLPurifier_Token_Start && !$token instanceof WOE_HTMLPurifier_Token_Empty) {
                continue;
            }

            // skip tokens that are armored
            if (!empty($token->armor['ValidateAttributes'])) {
                continue;
            }

            // note that we have no facilities here for removing tokens
            $validator->validateToken($token, $config, $context);
        }
        $context->destroy('CurrentToken');
        return $tokens;
    }
}

// vim: et sw=4 sts=4
