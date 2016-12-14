<?php
/*
Plugin Name: Check Files Integrity
Version: auto
Description: Check Piwigo files for unexpected modifications
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=
Author: plg
Author URI: http://piwigo.org
*/

if (!defined('PHPWG_ROOT_PATH'))
{
  die('Hacking attempt!');
}

/* Plugin admin */
add_event_handler('get_admin_plugin_menu_links', 'cfi_admin_menu');
function cfi_admin_menu($menu)
{
  array_push(
    $menu,
    array(
      'NAME' => 'Check Files Integrity',
      'URL'  => get_root_url().'admin.php?page=plugin-check_files_integrity',
      )
    );

  return $menu;
}
?>
