<?php

/**
 * Processes an entire attribute array for corrections needing multiple values.
 *
 * Occasionally, a certain attribute will need to be removed and popped onto
 * another value.  Instead of creating a complex return syntax for
 * WOE_HTMLPurifier_AttrDef, we just pass the whole attribute array to a
 * specialized object and have that do the special work.  That is the
 * family of WOE_HTMLPurifier_AttrTransform.
 *
 * An attribute transformation can be assigned to run before or after
 * WOE_HTMLPurifier_AttrDef validation.  See WOE_HTMLPurifier_HTMLDefinition for
 * more details.
 */

abstract class WOE_HTMLPurifier_AttrTransform
{

    /**
     * Abstract: makes changes to the attributes dependent on multiple values.
     *
     * @param array $attr Assoc array of attributes, usually from
     *              WOE_HTMLPurifier_Token_Tag::$attr
     * @param WOE_HTMLPurifier_Config $config Mandatory WOE_HTMLPurifier_Config object.
     * @param WOE_HTMLPurifier_Context $context Mandatory WOE_HTMLPurifier_Context object
     * @return array Processed attribute array.
     */
    abstract public function transform($attr, $config, $context);

    /**
     * Prepends CSS properties to the style attribute, creating the
     * attribute if it doesn't exist.
     * @param array &$attr Attribute array to process (passed by reference)
     * @param string $css CSS to prepend
     */
    public function prependCSS(&$attr, $css)
    {
        $attr['style'] = isset($attr['style']) ? $attr['style'] : '';
        $attr['style'] = $css . $attr['style'];
    }

    /**
     * Retrieves and removes an attribute
     * @param array &$attr Attribute array to process (passed by reference)
     * @param mixed $key Key of attribute to confiscate
     * @return mixed
     */
    public function confiscateAttr(&$attr, $key)
    {
        if (!isset($attr[$key])) {
            return null;
        }
        $value = $attr[$key];
        unset($attr[$key]);
        return $value;
    }
}

// vim: et sw=4 sts=4
