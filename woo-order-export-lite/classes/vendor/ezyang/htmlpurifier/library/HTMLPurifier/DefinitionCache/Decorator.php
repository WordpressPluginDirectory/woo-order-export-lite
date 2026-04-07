<?php

class WOE_HTMLPurifier_DefinitionCache_Decorator extends WOE_HTMLPurifier_DefinitionCache
{

    /**
     * Cache object we are decorating
     * @type WOE_HTMLPurifier_DefinitionCache
     */
    public $cache;

    /**
     * The name of the decorator
     * @var string
     */
    public $name;

    public function __construct()
    {
    }

    /**
     * Lazy decorator function
     * @param WOE_HTMLPurifier_DefinitionCache $cache Reference to cache object to decorate
     * @return WOE_HTMLPurifier_DefinitionCache_Decorator
     */
    public function decorate(&$cache)
    {
        $decorator = $this->copy();
        // reference is necessary for mocks in PHP 4
        $decorator->cache =& $cache;
        $decorator->type = $cache->type;
        return $decorator;
    }

    /**
     * Cross-compatible clone substitute
     * @return WOE_HTMLPurifier_DefinitionCache_Decorator
     */
    public function copy()
    {
        return new WOE_HTMLPurifier_DefinitionCache_Decorator();
    }

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function add($def, $config)
    {
        return $this->cache->add($def, $config);
    }

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function set($def, $config)
    {
        return $this->cache->set($def, $config);
    }

    /**
     * @param WOE_HTMLPurifier_Definition $def
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function replace($def, $config)
    {
        return $this->cache->replace($def, $config);
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function get($config)
    {
        return $this->cache->get($config);
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function remove($config)
    {
        return $this->cache->remove($config);
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function flush($config)
    {
        return $this->cache->flush($config);
    }

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return mixed
     */
    public function cleanup($config)
    {
        return $this->cache->cleanup($config);
    }
}

// vim: et sw=4 sts=4
