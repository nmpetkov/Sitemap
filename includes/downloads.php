<?php
function sitemap_includecontent_downloads($args = array())
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
    $sql = "SELECT lid, title, ddate, cid FROM downloads_downloads WHERE status>0";
    $sql .= " ORDER BY ddate DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Downloads: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Downloads::Category', $item['cid'] . '::', ACCESS_OVERVIEW) 
                && SecurityUtil::checkPermission('Downloads::Link', $item['lid'] . '::', ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 'url' => ModUtil::url('Downloads', 'user', 'display', array('lid' => $item['lid'])), 'lastmod' => substr($item['ddate'], 0, 10));
            }
        }
    }

    return $content;
}

function sitemap_includelink_downloads()
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $lang = ZLanguage::getLanguageCode();
    $enablecategorization = ModUtil::getVar('Downloads', 'enablecategorization');
    $links = array();

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT `cid`, `title`, `description` FROM downloads_categories WHERE pid=0";
    $sql .= " ORDER BY cid";
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Downloads: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $links = array();
    if ($items) {
        $key = 0;
        $links[$key] = array('name' => __('Categories', $dom), 
            'url' => ModUtil::url('Pages', 'user', 'view'));
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Downloads::Category', $item['cid'] . '::', ACCESS_OVERVIEW)) {
                $links[$key]['sublinks'][] = array('name' => $item['title'], 
                    'url' => ModUtil::url('Downloads', 'user', 'view', array('category' => $item['cid'])), 'lastmod' => '');
            }
        }
    }

    return $links;
}
