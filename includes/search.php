<?php
function sitemap_includelink_search($args = array())
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $links = array();

    $links[] = array('name' => __('New search', $dom), 'url' => ModUtil::url('Search', 'user', 'main'));
    if (!SecurityUtil::checkPermission('Search::', '::', ACCESS_READ) || !UserUtil::isLoggedIn()) {
        $links[] = array('name' => __('Recent searches list', $dom), 'url' => ModUtil::url('Search', 'user', 'recent'));
    }

    return $links;
}
