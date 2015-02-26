<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Controller_User extends Zikula_AbstractController
{
    /**
     * Main user function
     */
    public function main($args)
    {
        return $this->view($args);
    }

    public function view()
    {
        // Get arguments
        $smitems       = ModUtil::apiFunc('Sitemap', 'user', 'getmods', 'default');
        $smitems_total = count($smitems);
        $smconf        = ModUtil::getVar('Sitemap', 'sm_conf');
        $homeurl       = System::getHomepageUrl();
        $registerurl   = ModUtil::url('Users', 'user', 'register');
        $loginurl      = ModUtil::url('Users', 'user', 'loginscreen');

        // Check arguments
        if (!$smconf || !$smitems) {
            return LogUtil::registerArgsError();
        }

        // Format arguments for display
        $smitems     = DataUtil::formatForDisplay($smitems);
        $smconf      = DataUtil::formatForDisplay($smconf);
        $homeurl     = DataUtil::formatForDisplay($homeurl);
        $registerurl = DataUtil::formatForDisplay($registerurl);
        $loginurl    = DataUtil::formatForDisplay($loginurl);

        // Set layout information
        switch($smconf['layout']) {
            case '1':
                $layout    = 'sitemap_user_view.tpl';
                $layoutcss = ThemeUtil::getModuleStylesheet('Sitemap', 'default.css');
                break;
            case '2':
                $layout    = 'sitemap_user_view_slickmap.tpl';
                $layoutcss = ThemeUtil::getModuleStylesheet('Sitemap', 'slickmap.css');
                break;
            default:
                $layout    = 'sitemap_user_view.tpl';
                $layoutcss = ThemeUtil::getModuleStylesheet('Sitemap', 'default.css');
        }

        // Set cache state/information and check cache
        $cachest_default = ModUtil::getVar('Theme', 'cache_lifetime');

        if ($smconf['cachest'] == 1) {
            if ($cachest_default == 0) {
                // Force caching
                $this->view->caching    = 1;
            }
            // Set cache lifetime
            $this->view->cache_lifetime = $smconf['cachelt'];
        }

        if ($smconf['cachest'] == 1 || $cachest_default == 1) {
            Loader::loadClass('UserUtil');
            // Set cache_id for having a different cache file
            $cache_id = UserUtil::getGroupListForUser(UserUtil::getVar('uid')); // per group
            $cache_id .= '_' . ZLanguage::getLanguageCode(); // per language
            $this->view->cache_id = $cache_id;

            if ($this->view->is_cached($layout)) { // Check cache
                $return = $this->view->fetch($layout);

                // Recover cache state/information
                if ($smconf['cachest'] == 1 && $cachest_default == 0) {
                    $this->view->caching = 0;
                }

                return $return;
            }
        }

        // Apply max links to content
        foreach ($smitems as $modname => $mod)
        {
            if (!empty($mod['contextmax'])) {
                $numcontcur = count($mod['contentext']);

                if ($numcontcur > $mod['contextmax']) {
                    $numcontcur--;
                    $smitems[$modname]['contentext']              = array_slice($mod['contentext'], 0, $mod['contextmax'], true);
                    $numcontcur++;
                    $smitems[$modname]['contentext'][$numcontcur] = array('name' => $this->__('And more...'), 'url' => $smitems[$modname]['url']);
                }
            }
        }

        // Separate modules from left and right column -- default layout
        if ($smconf['layout'] != 2) {
            $smitems_left = array();
            $smitems_right = array();
            $random = 1;
            foreach ($smitems as $modname => $mod)
            {
                switch ($mod['column']) {
                    case '2': $smitems_left[$modname] = $smitems[$modname];
                                    break;
                    case '4':
                        $smitems_right[$modname] = $smitems[$modname];
                        break;
                    default:
                        if ($random % 2 == 0) {
                            $smitems_right[$modname] = $smitems[$modname];
                        } else {
                            $smitems_left[$modname] = $smitems[$modname];
                        }
                }
                $random++;
            }

            // Assign vars
            $this->view->assign('smitems_left', $smitems_left);
            $this->view->assign('smitems_right', $smitems_right);
        }

        // Separate modules per line -- slickmap layout
        if ($smconf['layout'] == 2) {
            if ($smitems_total <= $smconf['layout_mpl'])
            {
                foreach ($smitems as $modname => $mod) {
                    $smitems_lines[0][$modname] = $smitems[$modname];
                }

                // Assign vars
                $this->view->assign('smitems_lines', $smitems_lines);
                $this->view->assign('smlayout_mpl', $smitems_total);
            } else
            {
                $i=0;
                $j=0;
                foreach ($smitems as $modname => $mod) {
                    $smitems_lines[$i][$modname] = $smitems[$modname];
                    $j++;
                    if ($j % $smconf['layout_mpl'] == 0) { $i++; }
                }

                // Assign vars
                $this->view->assign('smitems_lines', $smitems_lines);
                $this->view->assign('smlayout_mpl', $smconf['layout_mpl']);
            }
        }

        // Assign vars and generate the template
        $this->view->assign('smlayout_css', $layoutcss);
        $this->view->assign('homeurl', $homeurl);
        $this->view->assign('registerurl', $registerurl);
        $this->view->assign('loginurl', $loginurl);

        $return = $this->view->fetch($layout);

        // Recover cache state/information
        if ($smconf['cachest'] == 1 && $cachest_default == 0) {
            $this->view->caching = 0;
        }

        return $return;
    }

    public function xml()
    {
        // Get arguments
        $smitems     = ModUtil::apiFunc('Sitemap', 'user', 'getmods', 'xml');
        $smconf      = ModUtil::getVar('Sitemap', 'sm_conf');
        $curmod      = FormUtil::getPassedValue('curmod', 'MainZK', 'GET');
        $baseurl     = System::getBaseUrl();
        $homepageurl = System::getHomepageUrl();

        // Check arguments
        if (!$smconf || !$smitems) {
            return LogUtil::registerArgsError();
        }

        // Format arguments for display
        $smitems     = DataUtil::formatForDisplay($smitems);
        $smconf      = DataUtil::formatForDisplay($smconf);
        $curmod      = DataUtil::formatForDisplay($curmod);
        $baseurl     = DataUtil::formatForDisplay($baseurl);
        $homepageurl = DataUtil::formatForDisplay($homepageurl);

        // Set cache state/information and check cache
        $cachest_default = ModUtil::getVar('Theme', 'cache_lifetime');

        if ($smconf['cachest'] == 1) {
            if ($cachest_default == 0) {
                // Force caching
                $this->view->caching = 1;
            }
            // Set cache lifetime
            $this->view->cache_lifetime = $smconf['cachelt'];
        }

        if ($smconf['cachest'] == 1 || $cachest_default == 1) {
            Loader::loadClass('UserUtil');
            // Set cache_id for having a different cache file
            $cache_id = UserUtil::getGroupListForUser(UserUtil::getVar('uid')); // per group
            $cache_id .= '_' . ZLanguage::getLanguageCode(); // per language
            $cache_id .= '_' . $curmod; // per module
            $this->view->cache_id = $cache_id;

            if ($this->view->is_cached('sitemap_user_xml.tpl')) { // Check cache
                $this->view->display('sitemap_user_xml.tpl');
                exit;
            }
        }

        // Sitemap per module
        if ($curmod != 'MainZK') {
            $modinfo = ModUtil::getInfo(ModUtil::getIdFromName($curmod));
            $curmod  = $modinfo['name'];
            if (isset($smitems[$curmod])) {
                $smitems = array($curmod => $smitems[$curmod]);
            } else {
                $smitems = '';
            }
        }

        // Extract content for xml format
        $xmlitems    = array();

        if (isset($smitems) && is_array($smitems) && count($smitems) > 0) {
            foreach ($smitems as $curitem) 
            {
                if ($curitem['displaymodxml']) {

                    // Main link to the module
                    $xmlitems[] = array('url' => $baseurl.$curitem['url'],
                                        'changefreq' => $curitem['changefreq'],
                                        'priority' => $curitem['priority']);

                    // Links to the sections
                    if (isset($curitem['urlext']) && is_array($curitem['urlext']) && count($curitem['urlext']) > 0) {
                        foreach ($curitem['urlext'] as $cururlext)
                        {
                            if ($curitem['url'] != $cururlext['url']) {
                                $xmlitems[] = array('url' => $baseurl.$cururlext['url'],
                                                    'changefreq' => $curitem['changefreq'],
                                                    'priority' => $curitem['priority']);
                            }
                        }
                    }
                    // Links to the content
                    if (isset($curitem['contentext']) && is_array($curitem['contentext']) && count($curitem['contentext']) > 0) {
                        foreach ($curitem['contentext'] as $curcontentext)
                        {
                            if (!empty($curcontentext['lastmod'])) {
                                $curlastmod = $curcontentext['lastmod'];
                            }
                            if (empty($curcontentext['changefreq'])) {
                                $curchangefreq = $curitem['changefreq'];
                            } else {
                                $curchangefreq = $curcontentext['changefreq'];
                            }
                            if (empty($curcontentext['priority'])) {
                                $curpriority = $curitem['priority'];
                            } else {
                                $curpriority = $curcontentext['priority'];
                            }

                            $xmlitems[] = array('url' => $baseurl.$curcontentext['url'],
                                                'lastmod' => $curlastmod,
                                                'changefreq' => $curchangefreq,
                                                'priority' => $curpriority);
                        }
                    }
                } // end if
            } // end foreach
        }

        // Replace changefreq and priority by their values
        foreach ($xmlitems as $curid => $curitem)
        {
            switch ($curitem['changefreq'])
            {
                case '1': $xmlitems[$curid]['changefreq'] = 'always';
                        break;
                case '2': $xmlitems[$curid]['changefreq'] = 'hourly';
                        break;
                case '3': $xmlitems[$curid]['changefreq'] = 'daily';
                        break;
                case '4': $xmlitems[$curid]['changefreq'] = 'weekly';
                        break;
                case '5': $xmlitems[$curid]['changefreq'] = 'monthly';
                        break;
                case '6': $xmlitems[$curid]['changefreq'] = 'yearly';
                        break;
                case '7': $xmlitems[$curid]['changefreq'] = 'never';
                        break;
            }
            switch ($curitem['priority'])
            {
                case '1': $xmlitems[$curid]['priority'] = '0.0';
                        break;
                case '2': $xmlitems[$curid]['priority'] = '0.1';
                        break;
                case '3': $xmlitems[$curid]['priority'] = '0.2';
                        break;
                case '4': $xmlitems[$curid]['priority'] = '0.3';
                        break;
                case '5': $xmlitems[$curid]['priority'] = '0.4';
                        break;
                case '6': $xmlitems[$curid]['priority'] = '0.5';
                        break;
                case '7': $xmlitems[$curid]['priority'] = '0.6';
                        break;
                case '8': $xmlitems[$curid]['priority'] = '0.7';
                        break;
                case '9': $xmlitems[$curid]['priority'] = '0.8';
                        break;
                case '10': $xmlitems[$curid]['priority'] = '0.9';
                        break;
                case '11': $xmlitems[$curid]['priority'] = '1.0';
                        break;
            }
        }

        // Assign vars and generate the template
        $shorturls     = System::getVar('shorturls');
        $shorturlstype = System::getVar('shorturlstype');
        if ($shorturls == 1 && $shorturlstype == 0) {
            $this->view->assign('homepageurl', $homepageurl);
        } else {
            $this->view->assign('homepageurl', $baseurl . $homepageurl);
        }
        $this->view->assign('curdatetime', DateUtil::getDatetime_Date());
        $this->view->assign('smitems', $xmlitems);

        $this->view->display('sitemap_user_xml.tpl');
        exit;
    }
}
