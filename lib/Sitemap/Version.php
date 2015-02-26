<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Version extends Zikula_AbstractVersion
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']    = $this->__('Site map');
        $meta['url']            = $this->__(/*!module name that appears in URL*/'sitemap');
        $meta['description']    = $this->__('Generate a xml sitemap for the search engines and another one for the users.');
        $meta['version']        = '2.0.0';
        $meta['securityschema'] = array('Sitemap::' => '::');
        $meta['core_min']       = '1.3.0';

        return $meta;
    }
}