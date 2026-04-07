<?php

class WOE_HTMLPurifier_HTMLModule_Name extends WOE_HTMLPurifier_HTMLModule
{
    /**
     * @type string
     */
    public $name = 'Name';

    /**
     * @param WOE_HTMLPurifier_Config $config
     */
    public function setup($config)
    {
        $elements = array('a', 'applet', 'form', 'frame', 'iframe', 'img', 'map');
        foreach ($elements as $name) {
            $element = $this->addBlankElement($name);
            $element->attr['name'] = 'CDATA';
            if (!$config->get('HTML.Attr.Name.UseCDATA')) {
                $element->attr_transform_post[] = new WOE_HTMLPurifier_AttrTransform_NameSync();
            }
        }
    }
}

// vim: et sw=4 sts=4
