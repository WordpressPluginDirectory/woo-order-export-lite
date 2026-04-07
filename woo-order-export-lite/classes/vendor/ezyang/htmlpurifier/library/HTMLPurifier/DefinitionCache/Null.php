<?php

/**
 * Null cache object to use when no caching is on.
 */
class WOE_HTMLPurifier_DefinitionCache_Null extends WOE_HTMLPurifier_DefinitionCache
{

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function add($def, $config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function set($def, $config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function replace($def, $config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function remove($config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function get($config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function flush($config)
    {
        return false;
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function cleanup($config)
    {
        return false;
    }
}

// vim: et sw=4 sts=4
