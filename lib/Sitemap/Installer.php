<?php
/**
 * Sitemap Zikula Module
 */
class Sitemap_Installer extends Zikula_AbstractInstaller
{
    /**
     * Initializes a new install
     */
    public function install()
    {
        // Set default modules vars
        $sm_mods           = array();
        if (Zikula_Core::VERSION_NUM < '1.4.0') {
            $sm_mods['Groups'] = array('displaymod' => 1, 'contentext' => 1);
            $sm_mods['Legal']  = array('displaymod' => 1, 'urlext' => 1);
            $sm_mods['Search'] = array('displaymod' => 1, 'urlext' => 1);
        } else {
            $sm_mods['ZikulaGroupsModule'] = array('displaymod' => 1, 'contentext' => 1);
            $sm_mods['ZikulaLegalModule']  = array('displaymod' => 1, 'urlext' => 1);
            $sm_mods['ZikulaSearchModule'] = array('displaymod' => 1, 'urlext' => 1);
        }
        ModUtil::setVar('Sitemap', 'sm_mods', $sm_mods);

        // Set default config: cachest = Cache state, cachelt = Cache lifetime, layout = Layout to use, layout_mpl = Modules per line for layout
        $sm_conf = array('cachest' => 1, 'cachelt' => 86400, 'layout' => 1, 'layout_mpl' => 5);
        ModUtil::setVar('Sitemap', 'sm_conf', $sm_conf);

        // Initialization successful
        return true;
    }

    /**
     * Upgrade module
     */
    public function upgrade($oldversion)
    {
        // upgrade dependent on old version number
        switch ($oldversion)
        {
            case '1.1':
            case '1.2':
            case '2.0.0':
				// future upgrade routines
        }

		// upgrade success
        return true;
    }

    /**
     * Delete module
     */
    public function uninstall()
    {
        $this->delVars();

        return true;
    }
}