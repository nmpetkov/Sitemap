<?php
function sitemap_includecontent_postcalendar($args = array())
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

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT eid, title, ttime FROM postcalendar_events WHERE eventstatus>0 AND sharing>0";
    $sql .= " ORDER BY eid DESC";
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
            if (SecurityUtil::checkPermission('PostCalendar::Event', $item['title'] . "::" . $item['eid'], ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 
                    'url' => ModUtil::url('PostCalendar', 'user', 'display', array('viewtype' => 'event', 'eid' => $item['eid'])), 
                    'lastmod' => substr($item['ttime'], 0, 10));
            }
        }
    }

    return $content;
}
