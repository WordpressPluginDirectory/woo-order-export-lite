<?php

// It's not clear to me whether or not Punycode means that hostnames
// do not have canonical forms anymore. As far as I can tell, it's
// not a problem (punycoding should be identity when no Unicode
// points are involved), but I'm not 100% sure
class WOE_HTMLPurifier_URIFilter_HostBlacklist extends WOE_HTMLPurifier_URIFilter
{
    /**
     * @type string
     */
    public $name = 'HostBlacklist';

    /**
     * @type array
     */
    protected $blacklist = array();

    /**
     * @param WOE_HTMLPurifier_Config $config
     * @return bool
     */
    public function prepare($config)
    {
        $this->blacklist = $config->get('URI.HostBlacklist');
        return true;
    }

    /**
     * @param WOE_HTMLPurifier_URI $uri
     * @param WOE_HTMLPurifier_Config $config
     * @param WOE_HTMLPurifier_Context $context
     * @return bool
     */
    public function filter(&$uri, $config, $context)
    {
        foreach ($this->blacklist as $blacklisted_host_fragment) {
            if ($uri->host !== null && strpos($uri->host, $blacklisted_host_fragment) !== false) {
                return false;
            }
        }
        return true;
    }
}

// vim: et sw=4 sts=4
