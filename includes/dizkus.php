<?php
function sitemap_includelink_dizkus($args = array())
{
    $items = ModUtil::apiFunc('Dizkus', 'user', 'readuserforums');

    $links  = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Dizkus::', $item['cat_id'] . ':' . $item['forum_id'] . '::', ACCESS_OVERVIEW)) {
                $links[] = array('name' => $item['cat_title'] . " :: " . $item['forum_name'], 'url' => ModUtil::url('Dizkus', 'user', 'viewforum', array('forum' => $item['forum_id'])));
            }
        }
    }

    return $links;
}
