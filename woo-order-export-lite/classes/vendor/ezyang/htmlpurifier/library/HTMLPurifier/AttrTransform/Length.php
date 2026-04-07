<?php

/**
 * Class for handling width/height length attribute transformations to CSS
 */
class WOE_HTMLPurifier_AttrTransform_Length extends WOE_HTMLPurifier_AttrTransform
{

    /**
     * @type string
     */
    protected $name;

    /**
     * @type string
     */
    protected $cssName;

    public function __construct($name, $css_name = null)
    {
        $this->name = $name;
        $this->cssName = $css_name ? $css_name : $name;
    }

    /**
     * @param array $attr
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return array
     */
    public function transform($attr, $config, $context)
    {
        if (!isset($attr[$this->name])) {
            return $attr;
        }
        $length = $this->confiscateAttr($attr, $this->name);
        if (ctype_digit($length)) {
            $length .= 'px';
        }
        $this->prependCSS($attr, $this->cssName . ":$length;");
        return $attr;
    }
}

// vim: et sw=4 sts=4
