<?php
function sitemap_includecontent_wikula($args = array())
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

    $items   = ModUtil::apiFunc('wikula', 'user', 'LoadAllPages', array('numitems' => $numitems));

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (is_object($item['time'])) {
                $datetime = $item['time']->date;
            } else {
                $datetime = $item['time'];
            }
            if (SecurityUtil::checkPermission('wikula::', 'page::' . $item['tag'], ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['tag'], 'url' => ModUtil::url('wikula', 'user', 'main', array('tag' => $item['tag'])), 'lastmod' => substr($datetime, 0, 10));
            }
        }
    }

    return $content;
}
