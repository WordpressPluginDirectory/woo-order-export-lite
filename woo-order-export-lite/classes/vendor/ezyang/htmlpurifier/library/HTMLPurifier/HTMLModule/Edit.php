<?php

/**
 * XHTML 1.1 Edit Module, defines editing-related elements. Text Extension
 * Module.
 */
class WOE_HTMLPurifier_HTMLModule_Edit extends WOE_HTMLPurifier_HTMLModule
{

    /**
     * @type string
     */
    public $name = 'Edit';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $contents = 'Chameleon: #PCDATA | Inline ! #PCDATA | Flow';
        $attr = array(
            'cite' => 'URI',
            // 'datetime' => 'Datetime', // not implemented
        );
        $this->addElement('del', 'Inline', $contents, 'Common', $attr);
        $this->addElement('ins', 'Inline', $contents, 'Common', $attr);
    }

    // HTML 4.01 specifies that ins/del must not contain block
    // elements when used in an inline context, chameleon is
    // a complicated workaround to achieve this effect

    // Inline context ! Block context (exclamation mark is
    // separator, see getChildDef for parsing)

    /**
     * @type bool
     */
    public $defines_child_def = true;

    /**
     * @param WOE_HTMLPurifier_ElementDef $def
     * @return WOE_HTMLPurifier_ChildDef_Chameleon
     */
    public function getChildDef($def)
    {
        if ($def->content_model_type != 'chameleon') {
            return false;
        }
        $value = explode('!', $def->content_model);
        return new WOE_HTMLPurifier_ChildDef_Chameleon($value[0], $value[1]);
    }
}

// vim: et sw=4 sts=4
