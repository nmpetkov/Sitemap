<?php
function sitemap_includelink_cdb()
{
    $lang = ZLanguage::getLanguageCode();
    $dom   = ZLanguage::getModuleDomain('Sitemap');

    $links = array();
    $links[] = array('name' => $lang=='en' ? 'Climbing Places' : 'Катерачни обекти', 'url' => 'cdb.php?f=placeslist&lang=en');
    $links[] = array('name' => $lang=='en' ? 'Map' : 'Карта', 'url' => 'cdb.php?smap=1');
    $links[] = array('name' => $lang=='en' ? 'Guidebooks' : 'Гидовници', 'url' => ModUtil::url('Cdb', 'user', 'gbooklist'));
    $links[] = array('name' => $lang=='en' ? 'Pictures' : 'Снимки', 'url' => 'cdb.php?f=pictureslist');
    $links[] = array('name' => $lang=='en' ? 'Comments' : 'Коментари', 'url' => 'cdb.php?f=commentslist');
    $links[] = array('name' => $lang=='en' ? 'Climbs' : 'Изкачвания', 'url' => 'cdb.php?f=climbslist');
    $links[] = array('name' => $lang=='en' ? 'Peaks' : 'Върхове', 'url' => 'cdb.php?f=summits');
    $links[] = array('name' => $lang=='en' ? 'Routes' : 'Маршрути', 'url' => 'cdb.php?f=routeslist');
    $links[] = array('name' => $lang=='en' ? 'Statistics' : 'Статистика', 'url' => 'cdb.php?f=routeslist&stats=1');
    $links[] = array('name' => $lang=='en' ? 'Album' : 'Албум', 'url' => 'main.php?g2_itemId=4254');

    return $links;
}
