<?php
echo "Start update process \r\n";
$userconf = parse_ini_file("/usr/local/directadmin/data/users/admin/user.conf");
$SKIN =explode('/',$userconf['docsroot'])[3];
$remotefile = 'https://github.com/Terrorhawk/Capri/archive/master.zip';
$file = "/usr/local/directadmin/data/skins/$SKIN/upgrade.zip";
$dir = "/usr/local/directadmin/data/skins/$SKIN";
preg_match('~SVERSION=(.*)\|~',@file_get_contents("$dir/inc/version.html"),$SVERSION);
$SVERSION = $SVERSION[1];

if($versions = @file_get_contents("https://raw.githubusercontent.com/Terrorhawk/Capri/master/version.txt")) {
    list($ava_version, $da_version) = explode('-', $versions);
  } else {
    $ava_version = 'n/a';
    $da_version = 'n/a';
  }

 if ($SVERSION < $ava_version ) {
echo "New version found $ava_version \r\n";
    file_put_contents($file, file_get_contents($remotefile));
        if (!file_exists($file)){die("Error downloading file");}
    $zip = zip_open($file);
     if(!$zip){ die('Error opening Zip file');}
     while($zip_entry = zip_read($zip)) {
              $entry = zip_entry_open($zip,$zip_entry);
              $filename = str_replace('Capri-master/','/',zip_entry_name($zip_entry));
              $target_dir = $dir.dirname($filename);
              $filesize = zip_entry_filesize($zip_entry);
              if (is_dir($target_dir) || mkdir($target_dir,0777)) {
                if ($filesize > 0) {
                      $contents = zip_entry_read($zip_entry, $filesize);
                      file_put_contents($dir.$filename,$contents);
                      @chmod($dir.$filename, 0777);
                  }
              }
          }
        @unlink($file);
        echo "Done installing \r\n";
 }

else {
echo "No updates found \r\n";
}
?>
