<?php
function sitemap_includecontent_reviews($args = array())
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

    $lang = ZLanguage::getLanguageCode();
    $dom = ZLanguage::getModuleDomain('Sitemap');

    $items = ModUtil::apiFunc('Reviews', 'user', 'getall', 
        array('numitems' => $numitems, 'language' => $lang));

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Reviews::', $item['id'] . '::', ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 'url' => ModUtil::url('Reviews', 'user', 'display', array('id' => $item['id'])), 'lastmod' => substr($item['lu_date'], 0, 10));
            }
        }
    }

    return $content;
}

function sitemap_includelink_reviews()
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $links = array();

    $links[] = array('name' => __('Submit a review', $dom), 'url' => ModUtil::url('Reviews', 'user', 'newreview'));

    return $links;
}

