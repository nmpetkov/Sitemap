<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Api_Admin extends Zikula_AbstractApi
{
    public function getlinks()
    {
        $links = array();

        if (SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            $links[] = array('url' => ModUtil::url('Sitemap', 'admin', 'view'), 
                'text' => $this->__('Overview'),
                'class' => 'z-icon-es-home');
            $links[] = array('url' => ModUtil::url('Sitemap', 'user', 'main'), 
                'text' => $this->__('Frontend'),
                'class' => 'z-icon-es-display');
            $links[] = array('url' => ModUtil::url('Sitemap', 'admin', 'contentdisplay'), 
                'text' => $this->__('Content to display'),
				'class' => 'z-icon-es-cubes');
            $links[] = array('url' => ModUtil::url('Sitemap', 'admin', 'contentorder'), 
                'text' => $this->__('Order to display'),
            'class' => 'z-icon-es-gears');
            $links[] = array('url' => ModUtil::url('Sitemap', 'admin', 'settings'), 
            'text' => $this->__('Settings'),
            'class' => 'z-icon-es-config');
        }

        return $links;
    }

    public function getmods($gettype)
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $core_mods    = ModUtil::getUserMods();
        $core_mods_cl = array();
        $sm_mods      = ModUtil::getVar('Sitemap', 'sm_mods');
        $sm_conf      = ModUtil::getVar('Sitemap', 'sm_conf');
        $return_mods  = array();
        $returnb_mods = array();

        // Check arguments
        if (!$sm_mods || ($gettype != 'display' && $gettype != 'order')) {
            return false;
        }

        // Hide some system modules
        foreach ($core_mods as $coremod)
        {
            if (!preg_match('#^(?:Blocks|ZikulaBlocksModule|Categories|ZikulaCategoriesModule|Errors|Gettext|Profile|ZikulaProfileModule|Scribite|Sitemap|Theme|ZikulaThemeModule|Thumbnail|Tour|Users|ZikulaUsersModule|Banners|Ephemerides|FConnect|Quotes|Avatar)$#', $coremod['name'])) {
                $core_mods_cl[] = $coremod;
            }
        }

        // Get content for each module
        
        foreach ($core_mods_cl as $coremod)
        {
            $modname    = $coremod['name'];
            $modlowname = strtolower($modname);

            //// Content for display
            if ($gettype == 'display') {
                $displaymod = false;
                $displaymodxml = false;
                $urlext     = 0;
                $contentext = 0;
                $contextmax = null;
                $contextmaxxml = null;
                $changefreq = null;
                $priority   = null;

                // Get content from modvar
                if (array_key_exists($modname, $sm_mods)) {
                        $displaymod = true;
                        // start langs
                        foreach (ZLanguage::getInstalledLanguageNames() as $code => $langname) {
                            if (!isset($sm_mods[$modname]['sitemapname'][$code])) {
                                $sm_mods[$modname]['sitemapname'][$code] = $coremod['displayname'];
                            }
                        } // end langs
                        if (isset($sm_mods[$modname]['displaymodxml']) && $sm_mods[$modname]['displaymodxml']) {
                            $displaymodxml = 1;
                        }
                        if (isset($sm_mods[$modname]['urlext']) && $sm_mods[$modname]['urlext']) {
                            $urlext = $sm_mods[$modname]['urlext'];
                        }
                        if (isset($sm_mods[$modname]['contentext']) && $sm_mods[$modname]['contentext']) {
                            $contentext = $sm_mods[$modname]['contentext'];
                        }
                        if (isset($sm_mods[$modname]['contextmax']) && $sm_mods[$modname]['contextmax']) {
                            $contextmax = $sm_mods[$modname]['contextmax'];
                        }
                        if (isset($sm_mods[$modname]['contextmaxxml']) && $sm_mods[$modname]['contextmaxxml']) {
                            $contextmaxxml = $sm_mods[$modname]['contextmaxxml'];
                        }
                        if (isset($sm_mods[$modname]['changefreq']) && $sm_mods[$modname]['changefreq']) {
                            $changefreq = $sm_mods[$modname]['changefreq'];
                        }
                        if (isset($sm_mods[$modname]['priority']) && $sm_mods[$modname]['priority']) {
                            $priority = $sm_mods[$modname]['priority'];
                        }
                }

                // Test if there is a function for displaying content/links to this module
                $includefile = 'modules/Sitemap/includes/' . DataUtil::formatForOS($modlowname) . '.php';
                if (file_exists($includefile)) {
                    include_once($includefile);
                    if(!function_exists('sitemap_includelink_' . $modlowname)) {
                        $urlext     = 4;
                    }
                    if(!function_exists('sitemap_includecontent_' . $modlowname)) {
                        $contentext = 4;
                    }
                } else {
                    $urlext     = 4;
                    $contentext = 4;
                }
                $return_mods[$modname] = array('displaymod'  => $displaymod,
                                               'displaymodxml' => $displaymodxml,
                                               'displayname' => $coremod['displayname'],
                                               'sitemapname' => isset($sm_mods[$modname]['sitemapname']) ? $sm_mods[$modname]['sitemapname'] : $coremod['displayname'],
                                               'urlext'      => $urlext,
                                               'contentext'  => $contentext,
                                               'contextmax'  => $contextmax,
                                               'contextmaxxml' => $contextmaxxml,
                                               'changefreq'  => $changefreq,
                                               'priority'    => $priority,
                                               'column'      => isset($sm_mods[$modname]['column']) ? $sm_mods[$modname]['column'] : 1,
                                               'order'       => isset($sm_mods[$modname]['order']) ? $sm_mods[$modname]['order'] : 0);
            }

            //// Content for order
            if ($gettype == 'order') {
                $displaymod = false;
                $order      = null;
                $column     = null;

                // Get content from modvar
                if (array_key_exists($modname, $sm_mods)) {
                    $displaymod = true;
                    $order      = isset($sm_mods[$modname]['order']) ? $sm_mods[$modname]['order'] : 0;
                    $column     = isset($sm_mods[$modname]['column']) ? $sm_mods[$modname]['column'] : 1;
                }

                if ($displaymod) {
                    $return_mods[$modname] = array('displayname' => $coremod['displayname'],
                                                   'column'      => $column,
                                                   'order'       => $order);
                }
            }
        }

        // Order the modules to display
        $order_mods = array();
        if ($sm_conf['layout'] != 2)
        {
            $order_mods_left = array();
            $order_mods_right = array();
            foreach ($return_mods as $modname => $smmod)
            {
                // Attribute module to the left/right column array and value to order array
                if (!empty($smmod['column'])) {
                    if($smmod['column'] == 2) {
                        $order_mods_left[$modname] = $smmod['order'];
                    } else {
                        $order_mods_right[$modname] = $smmod['order'];
                    }
                } else {
                    $order_mods[$modname] = 99;
                }
            }

            // Order left/right column array
            asort($order_mods_left);
            asort($order_mods_right);

            // Merge left/right column array into one array
            $order_mods = array_merge($order_mods_left, $order_mods_right, $order_mods);
        }
        else
        {
            foreach ($return_mods as $modname => $smmod)
            {
                // Attribute value to order array
                if (!empty($smmod['order'])) {
                    $order_mods[$modname] = $smmod['order'];
                } else {
                    $order_mods[$modname] = 99;
                }
            }

            // Order column array
            asort($order_mods);
        }

        foreach ($order_mods as $modname => $modorder)
        {
            $returnb_mods[$modname] = $return_mods[$modname];
        }

        // Return the output
        return $returnb_mods;
    }

    public function updatedisplay()
    {
        // Get arguments
        $core_mods    = ModUtil::getUserMods();
        $core_mods_cl = array();
        $sm_mods      = ModUtil::getVar('Sitemap', 'sm_mods');

        // Hide some system modules
        foreach ($core_mods as $coremod)
        {
            if (!preg_match('#^(?:Blocks|Categories|Errors|Gettext|Header_Footer|Profile|scribite|Sitemap|Theme|Thumbnail|Tour|Users)$#', $coremod['name'])) {
                $core_mods_cl[] = $coremod;
            }
        }

        // Get display information for each module
        foreach ($core_mods_cl as $coremod)
        {
            // Get arguments
            $modname       = $coremod['name'];
            $displaymod    = (int)FormUtil::getPassedValue('displaymod_' . $modname, '', 'POST');
            $displaymodxml = (int)FormUtil::getPassedValue('displaymodxml_' . $modname, '', 'POST');
            $urlext        = (int)FormUtil::getPassedValue('displayurl_' . $modname, '', 'POST');
            $contentext    = (int)FormUtil::getPassedValue('displaycont_' . $modname, '', 'POST');
            $contextmax    = (int)FormUtil::getPassedValue('contextmax_' . $modname, '', 'POST');
            $contextmaxxml = (int)FormUtil::getPassedValue('contextmaxxml_' . $modname, '', 'POST');
            $changefreq    = (int)FormUtil::getPassedValue('changefreq_' . $modname, '', 'POST');
            $priority      = (int)FormUtil::getPassedValue('priority_' . $modname, '', 'POST');
            // start langs
            foreach (ZLanguage::getInstalledLanguageNames() as $code => $langname) {
                $sm_mods[$modname]['sitemapname'][$code] = FormUtil::getPassedValue('sitemapname_' . $code . '_' . $modname, '', 'POST');;
            } // end langs

            // Remove a module
            if (($displaymod == 0 || empty($displaymod)) && isset($sm_mods[$modname])) {
                unset($sm_mods[$modname]);
            }

            // Add a module and add/remove display information for this module
            if ($displaymod == 1) {
                if (empty($sm_mods[$modname])) {
                    $sm_mods[$modname] = array();
                }

                if (!empty($urlext)) {
                    $sm_mods[$modname]['urlext']     = 1;
                } else if (!empty($sm_mods[$modname]['urlext'])) {
                    unset($sm_mods[$modname]['urlext']);
                }

                if (!empty($contentext)) {
                    $sm_mods[$modname]['contentext'] = 1;
                } else if (!empty($sm_mods[$modname]['contentext'])) {
                    unset($sm_mods[$modname]['contentext']);
                }

                if (!empty($contextmax) && !empty($contentext)) {
                    $sm_mods[$modname]['contextmax'] = $contextmax;
                } else if (!empty($sm_mods[$modname]['contextmax'])) {
                    unset($sm_mods[$modname]['contextmax']);
                }

                if (!empty($contextmaxxml) && !empty($contentext)) {
                    $sm_mods[$modname]['contextmaxxml'] = $contextmaxxml;
                } else if (!empty($sm_mods[$modname]['contextmaxxml'])) {
                    unset($sm_mods[$modname]['contextmaxxml']);
                }

                if (!empty($displaymodxml)) {
                    $sm_mods[$modname]['displaymodxml'] = 1;
                } else if (!empty($sm_mods[$modname]['displaymodxml'])) {
                    $sm_mods[$modname]['displaymodxml'] = 0;
                }

                if (!empty($changefreq)) {
                    $sm_mods[$modname]['changefreq'] = $changefreq;
                } else if (!empty($sm_mods[$modname]['changefreq'])) {
                    unset($sm_mods[$modname]['changefreq']);
                }

                if (!empty($priority)) {
                    $sm_mods[$modname]['priority'] = $priority;
                } else if (!empty($sm_mods[$modname]['priority'])) {
                    unset($sm_mods[$modname]['priority']);
                }
            }
        }

        // Save the information into modvar
        ModUtil::setVar('Sitemap', 'sm_mods', $sm_mods);

        // This function generated no output
        return true;
    }

    public function updateorder()
    {
        // Get arguments
        $sm_mods = ModUtil::getVar('Sitemap', 'sm_mods');
        $sm_conf = ModUtil::getVar('Sitemap', 'sm_conf');

        // Get order/column information for each module
        foreach ($sm_mods as $modname => $smmod)
        {
            // Get arguments
            $modorder  = (int)FormUtil::getPassedValue('order_' . $modname, '', 'POST');
            $modcolumn = (int)FormUtil::getPassedValue('column_' . $modname, '', 'POST');

            // Add/remove column information
            if (!empty($modcolumn)) {
                $sm_mods[$modname]['column'] = $modcolumn;
            } else if (!empty($sm_mods[$modname]['column']) && $sm_conf['layout'] != 2) {
                unset($sm_mods[$modname]['column']);
                $modorder = null;
            }

            // Add/remove order information
            if (!empty($modorder)) {
                $sm_mods[$modname]['order']  = $modorder;
            } else if (!empty($sm_mods[$modname]['order'])) {
                unset($sm_mods[$modname]['order']);
            }
        }

        // Save the information into modvar
        ModUtil::setVar('Sitemap', 'sm_mods', $sm_mods);

        // This function generated no output
        return true;
    }

    public function updatesettings()
    {
        // Security check
        if (!SecurityUtil::checkPermission('Sitemap::', '::', ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Get arguments
        $sm_conf        = ModUtil::getVar('Sitemap', 'sm_conf');
        $layout         = (int)FormUtil::getPassedValue('layout', '', 'POST');
        $layout_mpl     = (int)FormUtil::getPassedValue('layout_mpl', '', 'POST');
        $cachestate     = (int)FormUtil::getPassedValue('cachestate', '', 'POST');
        $cachelifetime  = (int)FormUtil::getPassedValue('cachelifetime', '', 'POST');

        // Layout information
        if (!empty($layout)) {
            $sm_conf['layout'] = $layout;
        } else {
            $sm_conf['layout'] = 1;
        }

        // Modules per line for slickmap layout
        if ($sm_conf['layout'] == 2 && !empty($layout_mpl)) {
            if ($layout_mpl > 0 && $layout_mpl < 11) {
                $sm_conf['layout_mpl'] = $layout_mpl;
            } else {
                $sm_conf['layout_mpl'] = 5;
                LogUtil::registerError($this->__('Error: the modules per line value should be set between 1 and 10.'));
            }
        } elseif ($sm_conf['layout'] == 2) {
            $sm_conf['layout_mpl'] = 5;
        }

        // Cache state information
        if (!empty($cachestate)) {
            $sm_conf['cachest'] = 1;
        } else {
            $sm_conf['cachest'] = 0;
        }

        // Cache lifetime information
        if ($sm_conf['cachest'] == 1 && !empty($cachelifetime)) {
            $sm_conf['cachelt']  = $cachelifetime;
        } elseif ($sm_conf['cachest'] == 1) {
            $sm_conf['cachelt']  = 86400;
        }

        // Save the information into modvar
        ModUtil::setVar('Sitemap', 'sm_conf', $sm_conf);

        // This function generated no output
        return true;
    }
}