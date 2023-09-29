<?php
$auth_page = true;

$perchroot = __DIR__ . '/../../..';
include($perchroot . '/core/inc/pre_config.php');
include($perchroot . '/config/config.php');

include(PERCH_CORE . '/inc/loader.php');
$Perch  = PerchAdmin::fetch();
include(PERCH_CORE . '/inc/auth.php');
    
// Check for logout
if ($CurrentUser->logged_in() && isset($_GET['logout']) && is_numeric($_GET['logout'])) {
    $CurrentUser->logout();
}

// If the user's logged in, clone item and related indices. Then send them to edit the new item
if ($CurrentUser->logged_in()) {
    try {
        // Will need a form posting to this page with the page ID in a query string named: "id"
        if (!$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)):
            throw new Exception('No valid page ID passed though POST vars');
        endif;
        if (!$itm = filter_input(INPUT_GET, 'itm', FILTER_VALIDATE_INT)):
            throw new Exception('No valid item ID passed though POST vars');
        endif;
        if (!$moveto = filter_input(INPUT_GET, 'moveto', FILTER_VALIDATE_INT)):
            throw new Exception('There’s a problem with your moveto ID');
        endif;
        if (isset($_GET['unsetfields']) && !$unsetfieldsInput = filter_input(INPUT_GET, 'unsetfields', FILTER_SANITIZE_STRING)):
            throw new Exception('There’s a problem with your unsetfields');
        endif;
        if ($unsetfieldsInput):
            $unsetfields = explode(',', $unsetfieldsInput);
        else:
            $unsetfields = [];
        endif;
        
        $DB = PerchDB::fetch();
        
        $itemsFactory = new PerchContent_Items();
        /** @var PerchContent_Item $item */
        
        // get details for target region
        $targetdetails = $DB->get_row('SELECT pageID, regionRev FROM ' . PERCH_DB_PREFIX . 'content_regions WHERE regionID = ' . $moveto);
        $target = $itemsFactory->return_flattened_instance($targetdetails);
        // get item row related to the itemID/itemRev to move from {PERCH_DB_PREFIX}content_items
        $itemData = $DB->get_row('SELECT itemRowID, itemRev, itemJSON FROM ' . PERCH_DB_PREFIX . 'content_items WHERE itemID = ' . $itm . ' AND itemRev = ( SELECT max(itemRev) FROM ' . PERCH_DB_PREFIX . 'content_items WHERE itemID = ' . $itm . ' )');
        if ($itemData !== false):
            $item = $itemsFactory->return_flattened_instance($itemData);
        else:
            throw new Exception('Retrieving ' . PERCH_DB_PREFIX . 'content_items failed.');
        endif;
        // get all index rows related to the itemID/itemRev to move from {PERCH_DB_PREFIX}content_index
        $indexData = $DB->get_rows('SELECT indexID FROM ' . PERCH_DB_PREFIX . 'content_index WHERE itemID = ' . $itm . ' AND itemRev = ' . $item['itemRev']);
        if ($indexData !== false):
            $indexes = $itemsFactory->return_flattened_instance($indexData);
        else:
            throw new Exception('Retrieving ' . PERCH_DB_PREFIX . 'content_index (indexData) failed.');
        endif;
        $indexDataToDelete = $DB->get_rows('SELECT indexID FROM ' . PERCH_DB_PREFIX . 'content_index WHERE itemID = ' . $itm . ' AND itemRev NOT IN (' . $item['itemRev'] . ')');

        if ($indexDataToDelete !== false):
            $indexesToDelete = $itemsFactory->return_flattened_instance($indexDataToDelete);
        else:
            throw new Exception('Retrieving ' . PERCH_DB_PREFIX . 'content_index (indexDataToDelete) failed.');
        endif;
        // change field values as required and insert into {PERCH_DB_PREFIX}content_index
        $update['regionID'] = intval($moveto);
        $update['pageID'] = intval($target['pageID']);
        $update['itemRev'] = intval($target['regionRev']);
        foreach($indexes as $value) {
            // write altered index back to {PERCH_DB_PREFIX}content_index
            $DBupdate = $DB->update(PERCH_DB_PREFIX . 'content_index', $update, 'indexID', $value['indexID']);
            if($DBupdate === false):
                throw new Exception('Updating ' . PERCH_DB_PREFIX . 'content_index failed at indexID ' . $value['indexID'] . '.');
            endif;
        }
        if(count($unsetfields)):
            $itemJSON = json_decode($item['itemJSON'], true);
            // updates itemJSON in item if fields to unset were provided
            foreach( $unsetfields as $unsetfield):
                $unset = explode('|', $unsetfield);
                if($unset[1] != $itemJSON[$unset[0]]):
                    $itemJSON[$unset[0]] = $unset[1] ? $unset[1] : '';
                    $updatejson = true;
                endif;
            endforeach;
            if($updatejson): // only update JSON, if aone of the fields actuelly needs unsetting/altering
                $update['itemJSON'] = json_encode($itemJSON);
            endif;
        endif;
        $update['itemOrder'] = 1000;
        // write altered item back to {PERCH_DB_PREFIX}content_items
        $DBupdate = $DB->update(PERCH_DB_PREFIX . 'content_items', $update, 'itemRowID', $item['itemRowID']);
        if ($DBupdate !== false):
            header("Location: ../../../core/apps/content/edit/?id=" . $moveto);
        else:
            throw new Exception('Updating ' . PERCH_DB_PREFIX . 'content_items failed.');
        endif;

    } catch (Exception $e) {
        //Redirect to an error page, whatever you want if something doesn't work out.
        print '<div style="background-color: tomato; border-bottom: 20px solid teal; border-radius: 5px; color: white; display: inline-block; font-family: sans-serif; margin: 1em; letter-spacing: .025em; line-height: 1.4; max-width: 60ch; padding: .75em 1em;">⚠️';
        print '<p style="font-weight: bold;">';
        print $e;
        print '</p>';
        print '<p style="font-size: .8em;">Sorry for the fail.<br>Please contact your web developer or—if you are said web developer—raise an issue in the <a href="https://github.com/frwssr/frwssr_cloneitem/issues" target="_blank" rel="noopener noreferrer" style="color: teal;">GitHub repo</a>.<br>Thank you. 🙏</p>';
        print '</div>';
    }
}