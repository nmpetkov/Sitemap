<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Controller_Admin extends Zikula_AbstractController
{
    /**
     * Main administration function
     */
    public function main()
    {
        return $this->view();
    }

    public function view()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $smmods  = ModUtil::getVar('Sitemap', 'sm_mods');
        $languages = ZLanguage::getInstalledLanguageNames();

        // Generate xml sitemap links per language
        $smurls    = array();
        foreach($languages as $key => $language) {
            $smurls[$key]['short']  = ModUtil::url('Sitemap', 'user', 'xml', array(), null, null, null, false, $key);
            $smurls[$key]['full']   = System::getBaseUrl() . $smurls[$key]['short'];
        }

        // Generate xml sitemap links per language for all enable modules separately
        $smmodurls    = array();
        foreach($smmods as $modname => $tab) {
            foreach($languages as $key => $language) {
                $modinfo = ModUtil::getInfo(ModUtil::getIdFromName($modname));
                $smmodurls[$modname][$key]['short']  = ModUtil::url('Sitemap', 'user', 'xml', array('curmod' => $modinfo['url']), null, null, null, false, $key);
                $smmodurls[$modname][$key]['full']   = System::getBaseUrl() . $smmodurls[$modname][$key]['short'];
                $smmodurls[$modname][$key]['lgname'] = $language;
            }
        }

        // Set search engines list
        /*$searcheng = array('Google'   => 'http://www.google.com/ping?sitemap=',
                             'Bing'     => 'http://www.bing.com/docs/submit.aspx?url=',
                             'Ask'      => 'http://submissions.ask.com/ping?sitemap=',
                             'MoreOver' => 'http://api.moreover.com/ping?u=');*/
        $searcheng = array('Google'     => 'http://www.google.com/webmasters/sitemaps/ping?sitemap=',
                             'Bing'     => 'http://www.bing.com/webmaster/ping.aspx?siteMap=');

        // Format arguments for display
        $smurls     = DataUtil::formatForDisplay($smurls);
        $smmodurls  = DataUtil::formatForDisplay($smmodurls);
        $languages  = DataUtil::formatForDisplay($languages);

        // Assign vars and generate the template
        $this->view->assign('smurls', $smurls);
        $this->view->assign('smmodurls', $smmodurls);
        $this->view->assign('languages', $languages);
        $this->view->assign('searcheng', $searcheng);

        return $this->view->fetch('sitemap_admin_view.tpl');
    }

    public function contentdisplay()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $smitems = ModUtil::apiFunc('Sitemap', 'admin', 'getmods', 'display');

        // Check arguments
        if (!$smitems) {
            return LogUtil::registerArgsError();
        }

        // Format arguments for display
        $smitems = DataUtil::formatForDisplay($smitems);

        // Assign vars and generate the template
        $this->view->assign('smitems', $smitems);
        $this->view->assign('languages', ZLanguage::getInstalledLanguageNames());

        return $this->view->fetch('sitemap_admin_contentdisplay.tpl');
    }

    public function contentorder()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $smitems = ModUtil::apiFunc('Sitemap', 'admin', 'getmods', 'order');
        $smconf  = ModUtil::getVar('Sitemap', 'sm_conf');

        // Check arguments
        if (!$smitems || !$smconf) {
            return LogUtil::registerArgsError();
        }

        // Format arguments for display
        $smitems = DataUtil::formatForDisplay($smitems);

        // Assign vars and generate the template
        $this->view->assign('smitems', $smitems);
        $this->view->assign('smconf', $smconf);

        return $this->view->fetch('sitemap_admin_contentorder.tpl');
    }

    public function settings()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $smconf  = ModUtil::getVar('Sitemap', 'sm_conf');

        // Check arguments
        if (!$smconf) {
            return LogUtil::registerArgsError();
        }

        // Format arguments for display
        $smconf = DataUtil::formatForDisplay($smconf);

        // If empty layout
        if (!isset($smconf['layout'])) {
            $smconf['layout'] = 1;
        }

        // If empty modules per lines for slickmap layout
        if (!isset($smconf['layout_mpl'])) {
            $smconf['layout_mpl'] = 5;
        }

        // If empty cache state
        if (!isset($smconf['cachest'])) {
            $smconf['cachest'] = 1;
        }

        // If empty cache lifetime
        if (!isset($smconf['cachelt'])) {
            $smconf['cachelt'] = 86400;
        }

        // Assign vars and generate the template
        $this->view->assign('smconf', $smconf);

        return $this->view->fetch('sitemap_admin_settings.tpl');
    }

    public function updatedisplay()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Sitemap', 'admin', 'contentdisplay'));
        }

        // Clear the cache
        $theme = Zikula_View_Theme::getInstance();
        $theme->clear_all_cache();

        // Registrer the update
        ModUtil::apiFunc('Sitemap', 'admin', 'updatedisplay');

        // The module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Done! Module configuration updated.'));

        // This function generated no output
        return System::redirect(ModUtil::url('Sitemap', 'admin', 'contentdisplay'));
    }

    public function updateorder()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Sitemap', 'admin', 'contentorder'));
        }

        // Clear the cache
        $theme = Zikula_View_Theme::getInstance();
        $theme->clear_all_cache();

        // Registrer the update
        ModUtil::apiFunc('Sitemap', 'admin', 'updateorder');

        // The module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Done! Module configuration updated.'));

        // This function generated no output
        return System::redirect(ModUtil::url('Sitemap', 'admin', 'contentorder'));
    }

    public function updatesettings()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('Sitemap', 'admin', 'settings'));
        }

        // Clear the cache
        $theme = Zikula_View_Theme::getInstance();
        $theme->clear_all_cache();

        // Register the update
        ModUtil::apiFunc('Sitemap', 'admin', 'updatesettings');

        // The module configuration has been updated successfuly
        LogUtil::registerStatus($this->__('Done! Module configuration updated.'));

        // This function generated no output
        return System::redirect(ModUtil::url('Sitemap', 'admin', 'settings'));
    }
}
