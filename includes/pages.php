<?php
function sitemap_includecontent_pages($args = array())
{
    $numitems = -1; // get all items by default
    if (isset($args['gettype']) && $args['gettype'] == 'xml') {
        // XML map
        if (isset($args['contextmaxxml']) && $args['contextmaxxml'] > 0) {
            $numitems = $args['contextmaxxml'];
        }
    } else {
        // user map
        if (isset($args['contextmax']) && $args['contextmax'] > 0) {
            $numitems = $args['contextmax'];
        }
    }

    $lang = ZLanguage::getLanguageCode();
    $dom = ZLanguage::getModuleDomain('Sitemap');

    $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
    $sql = "SELECT pageid, title, lu_date FROM pages WHERE 1";
    $sql .= " AND (language='' OR language='".$lang."')";
    $sql .= " ORDER BY pageid DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Pages: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            if (SecurityUtil::checkPermission('Pages::', "{$item['title']}::{$item['pageid']}", ACCESS_OVERVIEW)) {
                $content[] = array('name' => $item['title'], 'url' => ModUtil::url('Pages', 'user', 'display', array('pageid' => $item['pageid'])), 'lastmod' => substr($item['lu_date'], 0, 10));
            }
        }
    }

    return $content;
}

function sitemap_includelink_pages()
{
    $lang = ZLanguage::getLanguageCode();
    $enablecategorization = ModUtil::getVar('Pages', 'enablecategorization');
    $links = array();

    if ($enablecategorization && SecurityUtil::checkPermission('Pages::', '::', ACCESS_READ)) {
        // get the categories registered for the Pages
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('Pages', 'pages');
        $properties  = array_keys($catregistry);
        $propertiesdata = array();
        foreach ($properties as $property) {
            $rootcat = CategoryUtil::getCategoryByID($catregistry[$property]);
            if (!empty($rootcat)) {
                $rootcat['path'] .= '/';
                $subcategories = CategoryUtil::getCategoriesByParentID($rootcat['id']);
                $propertiesdata[] = array('name' => $property,
                        'rootcat' => $rootcat,
                        'subcategories' => $subcategories);
            }
        }

        foreach ($propertiesdata as $key => $item) {
            $links[$key] = array('name' => $item['rootcat']['display_name'][$lang], 
                'url' => ModUtil::url('Pages', 'user', 'view', array('prop' => $item['name'])));
            if (isset($item['subcategories']) && is_array($item['subcategories'])) {
                foreach ($item['subcategories'] as $subcategory) {
                    $links[$key]['sublinks'][] = array('name' => $subcategory['display_name'][$lang], 
                        'url' => ModUtil::url('Pages', 'user', 'view', array('prop' => $item['name'], 'cat' => $subcategory['id'])));
                }
            }
        }
    }

    return $links;
}
