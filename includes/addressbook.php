<?php
function sitemap_includecontent_addressbook($args = array())
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
    $sql = "SELECT adr_id, adr_company, adr_sortname, adr_cr_date FROM addressbook_address WHERE adr_private=0";
    $sql .= " AND (adr_language='' OR adr_language='".$lang."')";
    $sql .= " ORDER BY adr_id DESC";
    if ($numitems > 0) {
        $sql .= " LIMIT ".$numitems;
    }
    $stmt = $connection->prepare($sql);
    try {
        $stmt->execute();
    } catch (Exception $e) {
        return LogUtil::registerError(__('Error in plugin', $dom).' Addressbook: ' . $e->getMessage());
    }
    $items = $stmt->fetchAll(Doctrine_Core::FETCH_ASSOC);

    $content = array();
    if ($items) {
        foreach ($items as $item) {
            $title = trim($item['adr_fname'].' '.$item['adr_name']);
            if (empty($title)) {
                $title = $item['adr_company'];
            }
            $content[] = array('name' => $title, 'url' => ModUtil::url('Addressbook', 'user', 'display', array('id' => $item['adr_id'])), 'lastmod' => substr($item['adr_cr_date'], 0, 10));
        }
    }

    return $content;
}

function sitemap_includelink_addressbook()
{
    $lang = ZLanguage::getLanguageCode();
    $enablecategorization = ModUtil::getVar('AddressBook', 'enablecategorization');
    $links = array();

    if ($enablecategorization && SecurityUtil::checkPermission('AddressBook::', '::', ACCESS_READ)) {
        // get the categories registered for the AddressBook
        $catregistry = CategoryRegistryUtil::getRegisteredModuleCategories('AddressBook', 'addressbook_address');
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
                'url' => ModUtil::url('AddressBook', 'user', 'categories'));
            if (isset($item['subcategories']) && is_array($item['subcategories'])) {
                foreach ($item['subcategories'] as $subcategory) {
                    $links[$key]['sublinks'][] = array('name' => $subcategory['display_name'][$lang], 
                        'url' => ModUtil::url('AddressBook', 'user', 'view', array('category' => $subcategory['id'])));
                }
            }
        }
    }

    return $links;
}
