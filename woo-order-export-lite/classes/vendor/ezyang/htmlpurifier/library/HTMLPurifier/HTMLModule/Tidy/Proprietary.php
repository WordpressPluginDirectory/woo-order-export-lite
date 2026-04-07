<?php

class WOE_HTMLPurifier_HTMLModule_Tidy_Proprietary extends WOE_HTMLPurifier_HTMLModule_Tidy
{

    /**
     * @type string
     */
    public $name = 'Tidy_Proprietary';

    /**
     * @type string
     */
    public $defaultLevel = 'light';

    /**
     * @return array
     */
    public function makeFixes()
    {
        $r = array();
        $r['table@background'] = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['td@background']    = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['th@background']    = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['tr@background']    = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['thead@background'] = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['tfoot@background'] = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['tbody@background'] = new WOE_HTMLPurifier_AttrTransform_Background();
        $r['table@height']     = new WOE_HTMLPurifier_AttrTransform_Length('height');
        return $r;
    }
}

// vim: et sw=4 sts=4
