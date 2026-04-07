<?php

class WOE_HTMLPurifier_AttrDef_HTML_ContentEditable extends WOE_HTMLPurifier_AttrDef
{
    public function validate($string, $config, $context)
    {
        $allowed = array('false');
        if ($config->get('HTML.Trusted')) {
            $allowed = array('', 'true', 'false');
        }

        $enum = new WOE_HTMLPurifier_AttrDef_Enum($allowed);

        return $enum->validate($string, $config, $context);
    }
}
