<?php
function sitemap_includecontent_faq($args = array())
{
    $numitems = -1; // get all items by default
    if (isset($args['gettype']) && $args['gettype'] == 'xml') {
        // XML map
        if (isset($args['contextmaxxml']) && $args['contextmaxxml'] > 0) {
            $numitems = $args['contextmaxxml'];
        }
    } else {
        // user map
        if (isset($args['contextmax']) && $args['contextmax'] > 0) {
            $numitems = $args['contextmax'];
        }
    }

    $dom = ZLanguage::getModuleDomain('Sitemap');

    $faqs    = ModUtil::apiFunc('FAQ', 'user', 'getall', array('numitems' => $numitems));

    $content = array();
    if ($items) {
        foreach ($faqs as $faq) {
            if (SecurityUtil::checkPermission('FAQ::', $faq['faqid'] . '::', ACCESS_OVERVIEW)) {
                $content[] = array('name' => $faq['question'], 'url' => ModUtil::url('FAQ', 'user', 'display', array('faqid' => $faq['faqid'])), 'lastmod' => substr($faq['lu_date'], 0, 10));
            }
        }
    }

    return $content;
}

function sitemap_includelink_faq()
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $links = array();

    $links[] = array('name' => __('Submit a question', $dom), 'url' => ModUtil::url('FAQ', 'user', 'ask'));

    return $links;
}
