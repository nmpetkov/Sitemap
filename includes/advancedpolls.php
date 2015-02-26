<?php
function sitemap_includecontent_advancedpolls($args = array())
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

    $modinfo = ModUtil::getInfoFromName('AdvancedPolls');

    //$modinfo['version'] = '2.0.1' pn field prefix exist
    //$modinfo['version'] = '3.0.0'

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    if ($modinfo['version'] >= '3.0.0') {
        $sql = "SELECT * FROM advancedpolls_polls WHERE 1";
    } else {
        $prefix = System::getVar('prefix');
        $prefix = $prefix ? $prefix.'_' : '';
        $sql = "SELECT * FROM ".$prefix."advanced_polls_desc WHERE 1";
    }
    if ($modinfo['version'] >= '3.0.0') {
        $sql .= " AND (language='' OR language='".$lang."')";
    } else {
        $sql .= " AND (pn_language='' OR pn_language='".$lang."')";
    }
    if ($modinfo['version'] >= '3.0.0') {
        $sql .= " ORDER BY pollid DESC";
    } else {
        $sql .= " ORDER BY pn_pollid DESC";
    }
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin').' AdvancedPolls: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if ($modinfo['version'] >= '3.0.0') {
                $cr_date = $item['cr_date'];
                $title = $item['title'];
                $pollid = $item['pollid'];
            } else {
                $cr_date = $item['pn_cr_date'];
                $title = $item['pn_title'];
                $pollid = $item['pn_pollid'];
            }
            if (SecurityUtil::checkPermission('AdvancedPolls::item', $item['title'] . "::" . $pollid, ACCESS_OVERVIEW)) {
                $content[] = array('name' => $title, 'url' => ModUtil::url('AdvancedPolls', 'user', 'display', array('pollid' => $pollid)), 'lastmod' => substr($cr_date, 0, 10));
            }
        }
    }

    return $content;
}
