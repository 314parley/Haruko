<?php
//dont change these, dont even think about adding "bmp" support, it will rape you in the ass
$EXTENSIONS = 'gif,jpg,jpeg,png';
if(isset($_GET["board"])?$folder = 'images/'.$_GET["board"]."/":$folder = 'images/');
$fileList = array();
$handle = opendir($folder);
while ( false !== ( $file = readdir($handle) ) ) {
    $file_info = pathinfo($file);
    if ($file != "." && $file != "..") {
        if(strstr($file, ".") && strstr($EXTENSIONS,strtolower($file_info['extension']))){
            $fileList[] = $file;
        }elseif(!isset($_GET["board"])){
            $handle2 = opendir($folder.$file);
            while ( false !== ( $file = readdir($handle2) ) ) {
                $file_info = pathinfo($file);
                if ($file != "." && $file != "..") {
                    if(strstr($file, ".") && strstr($EXTENSIONS,strtolower($file_info['extension']))){
                        $fileList[] = $file;
                    }
                }
            }
        }
    }
}
closedir($handle);
if(count($fileList) > 0 ? $img = $folder.$fileList[rand(0, count($fileList)-1)] : die());
$imageInfo = pathinfo($img);
header ('Content-type: image/'.($imageInfo['extension'] == 'jpg' ? 'jpeg' : $imageInfo['extension']));
readfile($img);
?>