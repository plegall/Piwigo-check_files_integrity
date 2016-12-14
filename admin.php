<?php
// +-----------------------------------------------------------------------+
// | Piwigo - a PHP based photo gallery                                    |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2008-2016 Piwigo Team                  http://piwigo.org |
// | Copyright(C) 2003-2008 PhpWebGallery Team    http://phpwebgallery.net |
// | Copyright(C) 2002-2003 Pierrick LE GALL   http://le-gall.net/pierrick |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if( !defined("PHPWG_ROOT_PATH") )
{
  die ("Hacking attempt!");
}

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+

check_status(ACCESS_WEBMASTER);

// +-----------------------------------------------------------------------+
// |                            add permissions                            |
// +-----------------------------------------------------------------------+

if (isset($_POST['submit']))
{
  $file_sums = dirname(__FILE__).'/data/piwigo-'.PHPWG_VERSION.'-sums.txt';

  if (!is_file($file_sums))
  {
    $page['errors'][] = 'unsupported version of Piwigo';
  }
  else
  {
    $starttime = get_moment();
    $lines = file($file_sums);

    foreach ($lines as $line)
    {
      list($sum_ref, $relative_path) = explode(' ', trim($line));

      $fullpath = './'.$relative_path;

      if (!is_file($fullpath))
      {
        array_push($page['errors'], $relative_path.' is missing');
        continue;
      }

      $sum_local = sha1(file_get_contents($fullpath));

      if ($sum_local != $sum_ref)
      {
        array_push($page['errors'], $relative_path.' has been modified');
      }
    }

    $endtime = get_moment();
    $elapsed = ($endtime - $starttime);
    array_push(
      $page['infos'],
      sprintf(
        'Piwigo %s, %u files scanned in %.3f seconds',
        PHPWG_VERSION,
        count($lines),
        $elapsed
        )
      );
  }

  if (count($page['errors']) == 0)
  {
    array_push($page['infos'], 'Well done! Everything seems good :-)');
  }
}


// +-----------------------------------------------------------------------+
// |                             template init                             |
// +-----------------------------------------------------------------------+

$template->set_filenames(
  array(
    'plugin_admin_content' => dirname(__FILE__).'/admin.tpl'
    )
  );

// +-----------------------------------------------------------------------+
// |                           sending html code                           |
// +-----------------------------------------------------------------------+

$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
?>
