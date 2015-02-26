<?php
function sitemap_includecontent_news($args = array())
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

    $modvars = ModUtil::getVar('News');
    $storyorder = $modvars['storyorder'];
    switch ($storyorder)
    {
        case 0:
            $sort = "sid DESC";
            break;
        case 2:
            $sort = "weight ASC";
            break;
        case 1:
        default:
            $sort = "ffrom DESC";
    }
    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT sid, cr_uid, published_status, title, lu_date FROM news WHERE published_status=0";
    $sql .= " AND (language='' OR language='".$lang."')";
    $sql .= " ORDER BY ".$sort;
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' News: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Stories::Story', $item['cr_uid'] . '::' . $item['sid'], ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 'url' => ModUtil::url('News', 'user', 'display', array('sid' => $item['sid'])), 'lastmod' => substr($item['lu_date'], 0, 10));
            }
        }
    }

    return $content;
}

function sitemap_includelink_news()
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $links = array();

    $links[] = array('name' => __('Categories', $dom), 'url' => ModUtil::url('News', 'user', 'categorylist'));
    $links[] = array('name' => __('Archives', $dom), 'url' => ModUtil::url('News', 'user', 'archives'));
    if (SecurityUtil::checkPermission('Stories::Story', '::', ACCESS_COMMENT)) {
        $links[] = array('name' => __('Submit article', $dom), 'url' => ModUtil::url('News', 'user', 'new'));
    }

    return $links;
}
