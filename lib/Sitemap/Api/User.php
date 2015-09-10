<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Api_User extends Zikula_AbstractApi
{
    /**
     * Get modules to display
     */
    public function getmods($gettype)
    {
        // Get arguments
        $core_mods = ModUtil::getUserMods();
        $sm_mods   = ModUtil::getVar('Sitemap', 'sm_mods');
        $sm_conf   = ModUtil::getVar('Sitemap', 'sm_conf');
        $ret_mods  = array();
        $retb_mods = array();

        // Check arguments
        if (!$sm_mods || !$sm_conf || ($gettype != 'default' && $gettype != 'xml')) {
            return false;
        }

        // Get informations to display from the modules list
        foreach ($core_mods as $mod)
        {
            $modname    = $mod['name'];
            $modlowname = strtolower($modname);
            
            if (array_key_exists($modname, $sm_mods) && SecurityUtil::checkPermission("$modname::", '::', ACCESS_OVERVIEW))
            {
                // Links depend of the module type
                switch ($mod['type']) {
                    case 1:
                        $modurl = System::getVar('entrypoint', 'index.php') . '?name=' . DataUtil::formatForDisplay($mod['directory']);
                        break;
                    case 2:
                    case 3:
                        $modurl = ModUtil::url($modname, 'user', 'main');
                        break;
                }

                // Load additional informations for the current module
                $modurlext = null;
                $modcontentext = null;
                $includefile = 'modules/Sitemap/includes/' . DataUtil::formatForOS($modlowname) . '.php';
                if (file_exists($includefile)) {
                    include_once($includefile);
                    // Display more links
                    if (function_exists('sitemap_includelink_' . $modlowname) && isset($sm_mods[$modname]['urlext']) && $sm_mods[$modname]['urlext'] == 1) {
                        $modurlext = call_user_func('sitemap_includelink_' . $modlowname);
                    }

                    // Display more content
                    $sm_mods[$modname]["gettype"] = $gettype;
                    if (function_exists('sitemap_includecontent_' . $modlowname) && isset($sm_mods[$modname]['contentext']) && $sm_mods[$modname]['contentext'] == 1) {
                        $modcontentext = call_user_func('sitemap_includecontent_' . $modlowname, $sm_mods[$modname]);
                    }
                }

                // Multilingual names
                $lang = ZLanguage::getLanguageCode();
                $sitemap_name = $mod['displayname'];
                if (isset($sm_mods[$modname]['sitemapname'][$lang]) && !empty($sm_mods[$modname]['sitemapname'][$lang])) {
                    $sitemap_name = $sm_mods[$modname]['sitemapname'][$lang];
                }

                //// Default content
                if ($gettype == 'default') {
                    // Set the order number for modules which don't already have a one
                    if (empty($sm_mods[$modname]['order']) || (empty($sm_mods[$modname]['column']) && $sm_conf['layout'] != 2)) {
                        $sm_mods[$modname]['order'] = 99;
                    }

                    // Save module informations
                    $ret_mods[$modname] = array('name'       => $sitemap_name,
                                                'url'        => $modurl,
                                                'urlext'     => $modurlext,
                                                'contentext' => $modcontentext,
                                                'contextmax' => isset($sm_mods[$modname]['contextmax']) ? $sm_mods[$modname]['contextmax'] : '',
                                                'contextmaxxml' => isset($sm_mods[$modname]['contextmaxxml']) ? $sm_mods[$modname]['contextmaxxml'] : '',
                                                'order'      => isset($sm_mods[$modname]['order']) ? $sm_mods[$modname]['order'] : '',
                                                'column'     => isset($sm_mods[$modname]['column']) ? $sm_mods[$modname]['column'] : '');
                }

                //// Xml content
                if ($gettype == 'xml') {
                    // Save module informations
                    $ret_mods[$modname] = array('name'       => $sitemap_name,
                                                'url'        => $modurl,
                                                'urlext'     => $modurlext,
                                                'contentext' => $modcontentext,
                                                'displaymodxml' => isset($sm_mods[$modname]['displaymodxml']) ? $sm_mods[$modname]['displaymodxml'] : 0,
                                                'changefreq' => isset($sm_mods[$modname]['changefreq']) ? $sm_mods[$modname]['changefreq'] : '',
                                                'priority'   => isset($sm_mods[$modname]['priority']) ? $sm_mods[$modname]['priority'] : '');
                }
            }
        }

        // Order default content
        if ($gettype == 'default') {
            // Order the modules to display
            $order_mods = array();
            foreach ($ret_mods as $modname => $smmod)
            {
                $order_mods[$modname] = $smmod['order'];
            }
            asort($order_mods);
            foreach ($order_mods as $modname => $modorder)
            {
                $retb_mods[$modname] = $ret_mods[$modname];
            }
        } else {
            $retb_mods = $ret_mods;
        }

        // Return
        return $retb_mods;
    }

    public function getlinks()
    {
        if (SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            $links[] = array('url'   => ModUtil::url('Sitemap', 'admin', 'main'),
                     'text'  => $this->__('Backend'),
                     'class' => 'z-icon-es-options');
        }

        return $links;
    }
}