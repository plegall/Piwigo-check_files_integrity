<?php
// usage
// php piwigo_file_sums.php /tmp/piwigo-2.8.6 > piwigo-2.8.x-sums.txt

function getDirContents($dir, &$results = array())
{
  global $base_dir;

  $files = scandir($dir);

  foreach($files as $key => $value)
  {
    $path = realpath($dir.DIRECTORY_SEPARATOR.$value);
    if (!is_dir($path))
    {
      if (substr($value, -4) == '.php' and $value != 'theme.lang.php')
      {
        $results[$path] = sha1(file_get_contents($path));
        echo $results[$path]." ".substr($path, strlen($base_dir)+1)."\n";
      }
    }
    else if ($value != "." and $value != ".." and $value != 'language')
    {
      getDirContents($path, $results);
    }
  }

  return $results;
}

$base_dir = realpath($argv[1]);

$files = getDirContents($base_dir);
