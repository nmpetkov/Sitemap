<?php
function sitemap_includecontent_weblinks($args = array())
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

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT lid, title, ddate FROM links_links WHERE status>0";
    $sql .= " ORDER BY ddate DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Weblinks: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Weblinks::Category', $item['cat_id'] . '::', ACCESS_OVERVIEW) && SecurityUtil::checkPermission('Weblinks::Link', $item['lid'] . '::', ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 'url' => ModUtil::url('Weblinks', 'user', 'viewlinkdetails', array('lid' => $item['lid'])), 'lastmod' => substr($item['ddate'], 0, 10));
            }
        }
    }

    return $content;
}
