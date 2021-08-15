<?php

date_default_timezone_set ("Pacific/Auckland");
$date=date('Ymd');
file_put_contents('./grapher/version.php', "<?php \$v=".$date."; ?>");
file_put_contents('./grapherversion.php', $date);
file_put_contents('./grapher/sw.js',str_replace("VERSION",$date,file_get_contents('sw.js')));

function Zip($source, $destination, $include_dir = false, $exclude)
{

    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    if (file_exists($destination)) {
        unlink ($destination);
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }
    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true)
    {

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        if ($include_dir) {

            $arr = explode("/",$source);
            $maindir = $arr[count($arr)- 1];

            $source = "";
            for ($i=0; $i < count($arr) - 1; $i++) {
                $source .= '/' . $arr[$i];
            }

            $source = substr($source, 1);

            $zip->addEmptyDir($maindir);

        }

        foreach ($files as $file)
        {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
                continue;


            if (is_dir($file) === true)
            {
								$include=1;
								foreach ($exclude as $string) {
									if(strpos($file, $string)>-1){
										$include=0;
									}
								}
								if($include==1){
                  $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
								}
            }
            else if (is_file($file) === true)
            {
								$include=1;
								foreach ($exclude as $string) {
									if(strpos($file, $string)>-1){
										$include=0;
									}
								}
								if(basename($file)=="index.html"){$include=1;}
								if($include==1){
									$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
								}
            }
            $uri = str_replace($source . '/', '', $file);
            $zip->renameName(basename($uri), str_replace('_', '-', basename($uri)));
            if($include==1){
                echo $file."\r\n";
            }
        }
    }
    else if (is_file($source) === true)
    {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

unlink('./grapher.zip');
$exclude=array('/imagetemp/','DS_Store');
$return = Zip('./grapher/', './grapher.zip',true,$exclude);


if($return==true){echo "Zip Complete\r\n\r\n";} else {echo "there was an error\r\n\r\n";}

?>
