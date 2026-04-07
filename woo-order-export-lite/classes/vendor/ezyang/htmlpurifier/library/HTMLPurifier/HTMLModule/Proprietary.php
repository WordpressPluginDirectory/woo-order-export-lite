<?php

/**
 * Module defines proprietary tags and attributes in HTML.
 * @warning If this module is enabled, standards-compliance is off!
 */
class WOE_HTMLPurifier_HTMLModule_Proprietary extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'Proprietary';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $this->addElement(
            'marquee',
            'Inline',
            'Flow',
            'Common',
            array(
                'direction' => 'Enum#left,right,up,down',
                'behavior' => 'Enum#alternate',
                'width' => 'Length',
                'height' => 'Length',
                'scrolldelay' => 'Number',
                'scrollamount' => 'Number',
                'loop' => 'Number',
                'bgcolor' => 'Color',
                'hspace' => 'Pixels',
                'vspace' => 'Pixels',
            )
        );
    }
}

// vim: et sw=4 sts=4
