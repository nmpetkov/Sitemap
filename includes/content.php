<?php
function sitemap_includecontent_content($args = array())
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

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT page_id, page_title, page_lu_date FROM content_page WHERE page_active";
    $sql .= " AND (page_language='' OR page_language='".$lang."')";
    $sql .= " ORDER BY page_id DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Content: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('content:page:', $item['page_id'] . '::', ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['page_title'], 'url' => ModUtil::url('content', 'user', 'view', array('pid' => $item['page_id'])), 'lastmod' => substr($item['page_lu_date'], 0, 10));
            }
        }
    }

    return $content;
}
