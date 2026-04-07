<?php

/**
 * XHTML 1.1 Target Module, defines target attribute in link elements.
 */
class WOE_HTMLPurifier_HTMLModule_Target extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'Target';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $elements = array('a');
        foreach ($elements as $name) {
            $e = $this->addBlankElement($name);
            $e->attr = array(
                'target' => new WOE_HTMLPurifier_AttrDef_HTML_FrameTarget()
            );
        }
    }
}

// vim: et sw=4 sts=4
