<?php

/**
 * A "safe" script module. No inline JS is allowed, and pointed to JS
 * files must match whitelist.
 */
class WOE_HTMLPurifier_HTMLModule_SafeScripting extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'SafeScripting';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        // These definitions are not intrinsically safe: the attribute transforms
        // are a vital part of ensuring safety.

        $allowed = $config->get('HTML.SafeScripting');
        $script = $this->addElement(
            'script',
            'Inline',
            'Optional:', // Not `Empty` to not allow to autoclose the <script /> tag @see https://www.w3.org/TR/html4/interact/scripts.html
            null,
            array(
                // While technically not required by the spec, we're forcing
                // it to this value.
                'type' => 'Enum#text/javascript',
                'src*' => new WOE_HTMLPurifier_AttrDef_Enum(array_keys($allowed), /*case sensitive*/ true)
            )
        );
        $script->attr_transform_pre[] =
        $script->attr_transform_post[] = new WOE_HTMLPurifier_AttrTransform_ScriptRequired();
    }
}

// vim: et sw=4 sts=4
