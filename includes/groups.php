<?php
function sitemap_includecontent_groups($args = array())
{
    $content = array();
    $groups  = ModUtil::apiFunc('Groups', 'user', 'getallgroups');

    foreach ($groups as $group) {
        if (SecurityUtil::checkPermission('Groups::', $group['gid'] . '::', ACCESS_OVERVIEW)) {
            $content[] = array('name' => $group['name'], 'url' => ModUtil::url('Groups', 'user', 'memberslist', array('gid' => $group['gid'])));
        }
    }

    return $content;
}
