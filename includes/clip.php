<?php
function sitemap_includecontent_clip($args = array())
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

    $content = array();

    $pubtypes = sitemap_clip_getPubtypes();
    foreach ($pubtypes as $pubtype) {
        if (SecurityUtil::checkPermission('Clip::' . $pubtype['tid'] . ':display', '::', ACCESS_OVERVIEW)) {
            $result = ModUtil::apiFunc('Clip', 'user', 'getall', array('tid' => $pubtype['tid'], 'itemsperpage' => $numitems));
            if ($result) {
                $items = $result['publist']->toArray();
            } else {
                $items = array();
            }
            foreach ($items as $item) {
                if (SecurityUtil::checkPermission('Clip:' . $pubtype['tid'] . ':display', $item['id'] . '::', ACCESS_OVERVIEW)) {
                    $content[] = array('name' => $item['title'], 'url' => ModUtil::url('Clip', 'user', 'viewpub', array('tid' => $pubtype['tid'], 'pid' => $item['core_pid'])), 'lastmod' => substr($item['core_publishdate'], 0, 10));
                }
            }
        }
    }

    return $content;
}

function sitemap_includelink_clip()
{
    $items = sitemap_clip_getPubtypes();

    $links = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Clip::' . $item['tid'] . ':display', '::', ACCESS_OVERVIEW)) {
                $links[] = array('name' => $item['title'], 'url' => ModUtil::url('Clip', 'user', 'list', array('tid' => $item['tid'])));
            }
        }
    }

    return $links;
}

function sitemap_clip_getPubtypes()
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT tid, title, urltitle, description FROM clip_pubtypes WHERE 1 ORDER BY tid";
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Clip: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);
    
    return $items;
}