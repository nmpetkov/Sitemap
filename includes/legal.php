<?php
function sitemap_includelink_legal($args = array())
{
    $dom   = ZLanguage::getModuleDomain('Sitemap');
    $links = array();

    if (SecurityUtil::checkPermission('Legal::', 'legalNoticeActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'legalNoticeActive')) {
        $links[] = array('name' => __('Legal notice', $dom), 'url' => ModUtil::url('Legal', 'user', 'legalNotice'));
    }
    if (SecurityUtil::checkPermission('Legal::', 'termsOfUseActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'termsOfUseActive')) {
        $links[] = array('name' => __('Terms of use', $dom), 'url' => ModUtil::url('Legal', 'user', 'termsOfUse'));
    }
    if (SecurityUtil::checkPermission('Legal::', 'privacyPolicyActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'privacyPolicyActive')) {
        $links[] = array('name' => __('Privacy policy', $dom), 'url' => ModUtil::url('Legal', 'user', 'privacyPolicy'));
    }
    if (SecurityUtil::checkPermission('Legal::', 'tradeConditionsActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'tradeConditionsActive')) {
        $links[] = array('name' => __('Trade conditions', $dom), 'url' => ModUtil::url('Legal', 'user', 'tradeConditions'));
    }
    if (SecurityUtil::checkPermission('Legal::', 'cancellationRightPolicyActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'cancellationRightPolicyActive')) {
        $links[] = array('name' => __('Cancellation right', $dom), 'url' => ModUtil::url('Legal', 'user', 'cancellationrightpolicy'));
    }
    if (SecurityUtil::checkPermission('Legal::', 'accessibilityStatementActive::', ACCESS_OVERVIEW) && ModUtil::getVar('Legal', 'accessibilityStatementActive')) {
        $links[] = array('name' => __('Accessibility statement', $dom), 'url' => ModUtil::url('Legal', 'user', 'accessibilitystatement'));
    }

    return $links;
}
