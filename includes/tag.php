<?php
function sitemap_includecontent_tag($args = array())
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
    // SQL from Tag module
    $sql = "SELECT t.id, t.tag, t.slug, count(j.tag_entity_tag_id) freq" .
            " FROM tag_entity_object_tag_entity_tag j" .
            " LEFT JOIN tag_tag t ON j.tag_entity_tag_id = t.id" .
            " GROUP BY j.tag_entity_tag_id ORDER BY freq DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Tag: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            $content[] = array('name' => $item['tag'], 'url' => ModUtil::url('Tag', 'user', 'view', array('tag' => $item['slug'])), 'lastmod' => '');
        }
    }

    return $content;
}
