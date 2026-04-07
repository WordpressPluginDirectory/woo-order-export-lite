<?php

/**
 * Injector that displays the URL of an anchor instead of linking to it, in addition to showing the text of the link.
 */
class WOE_HTMLPurifier_Injector_DisplayLinkURI extends WOE_HTMLPurifier_Injector
{
    /**
     * @type string
     */
    public $name = 'DisplayLinkURI';

    /**
     * @type array
     */
    public $needed = array('a');

    /**
     * @param $token
     */
    public function handleElement(&$token)
    {
    }

    /**
     * @param WOE_HTMLPurifier_Token $token
     */
    public function handleEnd(&$token)
    {
        if (isset($token->start->attr['href'])) {
            $url = $token->start->attr['href'];
            unset($token->start->attr['href']);
            $token = array($token, new WOE_HTMLPurifier_Token_Text(" ($url)"));
        } else {
            // nothing to display
        }
    }
}

// vim: et sw=4 sts=4
