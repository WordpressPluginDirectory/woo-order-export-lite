<?php

/**
 * Concrete end token class.
 *
 * @warning This class accepts attributes even though end tags cannot. This
 * is for optimization reasons, as under normal circumstances, the Lexers
 * do not pass attributes.
 */
class WOE_HTMLPurifier_Token_End extends WOE_HTMLPurifier_Token_Tag
{
    /**
     * Token that started this node.
     * Added by MakeWellFormed. Please do not edit this!
     * @type WOE_HTMLPurifier_Token
     */
    public $start;

    public function toNode() {
        throw new Exception("WOE_HTMLPurifier_Token_End->toNode not supported!");
    }
}

// vim: et sw=4 sts=4
