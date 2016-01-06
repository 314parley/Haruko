<?php

namespace Mitsuba;

class Common {

    private $conn;

    private $mitsuba;

    function __construct($connection, &$mitsuba) {

        $this->conn = $connection;

        $this->mitsuba = $mitsuba;

    }

    function getEmbed($url, $embed_table = null, $s = 250) {

        foreach ($embed_table as $row) {

            if (preg_match($row['regex'], $url, $vresult)) {

                $vresult[0] = $s;

                foreach ($vresult as $k => $v) {

                    $vresult[$k] = htmlspecialchars($v);

                }

                return vsprintf($row['code'], $vresult);

            }

        }

        return 0;

    }

    function addSystemBan($IPAddress, $reason, $note, $expires, $boards) {

        if (!empty($IPAddress)) {

            $IPAddress = $this->conn->real_escape_string($IPAddress);

            $reason = $this->conn->real_escape_string($reason);

            $note = $this->conn->real_escape_string($note);

            $boards = $this->conn->real_escape_string($boards);

            $created = time();

            $perma = 1;

            if (($expires == "0") || ($expires == "never") || ($expires == "") || ($expires == "perm") || ($expires == "permaban")) {

                $expires = 0;

                $perma = 1;

            } else {

                $expires = $this->parse_time($expires);

                $perma = 0;

            }

            if (($expires == false) && ($perma == 0)) {

                return -2;

            }

            $this->conn->query("INSERT INTO bans (ip, mod_id, reason, note, created, expires, boards) VALUES ('" . $IPAddress . "', 0, '" . $reason . "', '" . $note . "', " . $created . ", " . $expires . ", '" . $boards . "');");

            return 1;

        }

    }

    function isEmbed($url, $embed_table = null) {

        foreach ($embed_table as $row) {

            if (preg_match($row['regex'], $url, $vresult)) {

                return 1;

            }

        }

        return 0;

    }

    function human_filesize($bytes, $decimals = 2) {

        $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');

        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[$factor];

    }

    function mkid($IPAddress, $topic, $board, $junk = "") {

        global $id_salt;

        return substr(crypt(md5($IPAddress . 't' . $board . $topic . $junk . $id_salt), 'h!'), -8);

    }
    
    function getIP(){
    $fields = array('HTTP_X_FORWARDED_FOR',
                    'REMOTE_ADDR',
                    'HTTP_CF_CONNECTING_IP',
                    'HTTP_X_CLUSTER_CLIENT_IP');

    foreach($fields as $f)
    {
        $tries = isset($_SERVER[$f]) ? $_SERVER[$f] : '';
        if (empty($tries))
            continue;
        $tries = explode(',',$tries);
        foreach($tries as $try)
        {
            $r = filter_var($try,
                            FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 |
                            FILTER_FLAG_NO_PRIV_RANGE |
                            FILTER_FLAG_NO_RES_RANGE);

            if ($r !== false)
            {
                return $try;
            }
        }
    }
    return false;
}
    
    function getGraphicsExtension() {

        if (extension_loaded('imagick')) {

            return "imagick";

        } elseif (extension_loaded('gd')) {

            return "gd";

        } else {

            return 0;

        }

    }
    
    function getBoardData($short) {

        return $this->isBoard($short); //yeah, yeah, I know...



    }

    function isBoard($short) {

        $result = $this->conn->query("SELECT * FROM boards WHERE short='" . $this->conn->real_escape_string($short) . "'");

        if ($result->num_rows == 1) {

            return $result->fetch_assoc();

        } else {

            return false;

        }

    }

    function showMsg($title, $text) {

        global $lang;

?>

		<html>

		<head>

		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<title><?php echo $title; ?></title>

		<script type="text/javascript" src="./js/style.js"></script>

		</head>

		<body>

		<table style="text-align: center; width: 100%; height: 300px;">

		<tbody>

		<tr valign="middle">

		<td align="center" style="font-size: x-large; font-weight: bold;">

		<span id="errmsg" style="color: red;"><?php echo $text; ?></span>

		<br><br>

		[<a href="javascript:history.go(-1);"><?php echo $lang['img/return']; ?></a>]

		</td>

		</tr>

		</tbody>

		</table>

		</body>

		</html>

		<?php

    }

    function thumb($board, $filename, $ext, $s = 250) {

        $extension = $this->getGraphicsExtension();

        if ($ext == ".webm") {

            //initialize

            $fname = './' . $board . '/src/' . $filename . $ext;

            $thumb_dir = './' . $board . '/src/thumb/';

            require_once 'webm.class.php';

            $movie = new \webm($fname);

            //actual logic...

            if ($movie->valid_webm()) {

                if ($movie->thumbnail($thumb_dir . $filename . '.gif', $s, $s, "") && $movie->thumbnail($thumb_dir . $filename . '.webm', $s, $s, "h")) {

                    return array("width" => $s, "height" => $s);

                } else {

                    echo "There was a problem in uploading your thumbnail... contact parley.";

                }

            } else {

                echo "The file you uploaded was <strong>NOT</strong> a valid WebM file.";



            }

        }

        $filename = $filename . $ext;

        if (!(strpos($filename, "url:") === false)) {

            //dont make thumbnails of links

            return 0;

        }

        if ($extension == "imagick") {

            $fname = './' . $board . '/src/' . $filename;

            $thumb_dir = './' . $board . '/src/thumb/'; //thumbnail directory

            $width = $s; //output width

            $height = $s; //output height

            $img = new \Imagick($fname);

            $img = $img->coalesceImages();

            foreach ($img as $frame) {

                $frame->thumbnailImage($width, $height, true);

            }

            $img->writeImages($thumb_dir . $filename, true);

            $ig = $img->getImageGeometry();

            $img->destroy();

            return $ig;

        } elseif ($extension == "gd") {

            if (!function_exists("ImageCreate") || !function_exists("ImageCreateFromJPEG")) return;

            $fname = './' . $board . '/src/' . $filename;

            $thumb_dir = './' . $board . '/src/thumb/'; //thumbnail directory

            $width = $s; //output width

            $height = $s; //output height

            // width, height, and type are aquired

            $size = GetImageSize($fname);

            $type = "jpg";

            try {

                switch ($size[2]) {

                    case 1:

                        if (!function_exists("ImageCreateFromGIF")) return;

                        $im_in = ImageCreateFromGIF($fname);

                        $type = "gif";

                        if (!$im_in) {

                            return -1;

                        }

                        break;

                    case 2:

                        $im_in = ImageCreateFromJPEG($fname);

                        $type = "jpg";

                        if (!$im_in) {

                            return -1;

                        }

                        break;

                    case 3:

                        if (!function_exists("ImageCreateFromPNG")) return;

                        $im_in = ImageCreateFromPNG($fname);

                        $type = "png";

                        if (!$im_in) {

                            return -1;

                        }

                        break;

                    default:

                        return -2;

                    }

                }

                catch(Exception $e) {

                    return -1;

                }

                // Resizing

                if ($size[0] > $width || $size[1] > $height) {

                    $key_w = $width / $size[0];

                    $key_h = $height / $size[1];

                    ($key_w < $key_h) ? $keys = $key_w : $keys = $key_h;

                    $out_w = ceil($size[0] * $keys) + 1;

                    $out_h = ceil($size[1] * $keys) + 1;

                } else {

                    $out_w = $size[0];

                    $out_h = $size[1];

                }

                // the thumbnail is created

                if (function_exists("ImageCreateTrueColor")) {

                    $im_out = ImageCreateTrueColor($out_w, $out_h);

                } else {

                    $im_out = ImageCreate($out_w, $out_h);

                }

                //adds transparency [devnote: this took me fucking 3-4 days to figure out how to do it... why isn't there better documentation?]

                imagealphablending($im_out, false);

                imagesavealpha($im_out, true);

                $trans_layer_overlay = imagecolorallocatealpha($image, 220, 220, 220, 127);

                imagefill($im_out, 0, 0, $trans_layer_overlay);

                // copy resized original

                imagecopyresampled($im_out, $im_in, 0, 0, 0, 0, $out_w, $out_h, $size[0], $size[1]);

                // thumbnail saved

                switch ($type) {

                    case "jpg":

                        ImageJPEG($im_out, $thumb_dir . $filename, 70);

                    break;

                    case "png":

                        ImagePNG($im_out, $thumb_dir . $filename, 9);

                    break;

                    case "gif":

                        ImageGIF($im_out, $thumb_dir . $filename);

                    break;

                }

                chmod($thumb_dir . $filename, 0666);

                // created image is destroyed

                ImageDestroy($im_in);

                ImageDestroy($im_out);

                return array("width" => $out_w, "height" => $out_h);

        }

    }

    function delTree($dir) {

        $files = array_diff(scandir($dir), array('.', '..'));

        foreach ($files as $file) {

            (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");

        }

        return rmdir($dir);

    }

    function isWhitelisted($IPAddress) {

        $whitelist = $this->conn->query("SELECT * FROM whitelist WHERE ip='" . $IPAddress . "' ORDER BY id DESC LIMIT 0, 1");

        if ($whitelist->num_rows >= 1) {

            $wlistdata = $whitelist->fetch_assoc();

            if ($wlistdata['nolimits'] == 1) {

                return 2;

            }

            return 1;

        } else {

            return 0;

        }

    }
    //thanks Rijndael. Easily en/decrypting your PMs since whenever.
    function pm_encrypt($encrypt, $key){
    	$encrypt = serialize($encrypt);
    	$iv = mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM);
    	$key = pack('H*', $key);
    	$mac = hash_hmac('sha256', $encrypt, substr(bin2hex($key), -32));
    	$passcrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $encrypt.$mac, MCRYPT_MODE_CBC, $iv);
    	$encoded = base64_encode($passcrypt).'|'.base64_encode($iv);
    	return $encoded;
    }
    function pm_decrypt($decrypt, $key){
    	$decrypt = explode('|', $decrypt.'|');
    	$decoded = base64_decode($decrypt[0]);
    	$iv = base64_decode($decrypt[1]);
    	if(strlen($iv)!==mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC)){ return false; }
    	$key = pack('H*', $key);
    	$decrypted = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $decoded, MCRYPT_MODE_CBC, $iv));
    	$mac = substr($decrypted, -64);
    	$decrypted = substr($decrypted, 0, -64);
    	$calcmac = hash_hmac('sha256', $decrypted, substr(bin2hex($key), -32));
    	if($calcmac!==$mac){ return false; }
    	$decrypted = unserialize($decrypted);
    	return $decrypted;
    }
    function startsWith($haystack, $needle) {

        return !strncmp($haystack, $needle, strlen($needle));

    }

    function endsWith($haystack, $needle) {

        $length = strlen($needle);

        if ($length == 0) {

            return true;

        }

        return (substr($haystack, -$length) === $needle);

    }

    /*function randomPassword() {

    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";

    $pass = array();

    $alphaLength = strlen($alphabet) - 1;

    for ($i = 0; $i < 8; $i++) {

    $n = rand(0, $alphaLength);

    $pass[] = $alphabet[$n];

    }

    return implode($pass);

    }*/

    function randomPassword() {

        $fp = @fopen('/dev/urandom', 'rb');

        $result = '';

        if ($fp !== FALSE) {

            $result.= @fread($fp, 8);

            @fclose($fp);

        } else {

            trigger_error('Can not open /dev/urandom.');

        }

        // convert from binary to string

        $result = base64_encode($result);

        // remove none url chars

        $result = strtr($result, '+/', '-_');

        // Remove = from the end

        $result = str_replace('=', ' ', $result);

        return $result;

    }

    function randomSalt() {

        $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789+_-)(*&^%$#@!~|';

        $pass = array();

        $alphaLength = strlen($alphabet) - 1;

        for ($i = 0;$i < 15;$i++) {

            $n = rand(0, $alphaLength);

            $pass[] = $alphabet[$n];

        }

        return implode($pass);

    }

    function getsecuretripcode($pwd) {

        $striphash = $this->mktripcode(substr($pwd, 1));

        $strips = $this->conn->query("SELECT * FROM tripcodes WHERE hash='" . $striphash . "' AND secure=1");

        if ($strips->num_rows >= 1) {

            $row = $strips->fetch_assoc();

            return $row['replace'];

        } else {

            $strip = $this->mksecuretripcode($pwd);

            $this->conn->query("INSERT INTO tripcodes (`hash`, `replace`, `secure`) VALUES ('" . $striphash . "', '" . $strip . "', 1);");

            return $strip;

        }

    }

    function processName($string) {

        $arr = array();

        $new = $string;

        //$new = str_replace("##", "#", $new);

        $exploded = explode("#", $new, 2);

        $arr['trip'] = "";

        $arr['name'] = "";

        $arr['strip'] = "";

        //$arr['striphash'] = "";

        if (count($exploded) > 1) {

            $arr['name'] = $exploded[0];

            if (substr($exploded[1], 0, 1) == "#") {

                $moretrips = explode("#", substr($exploded[1], 1), 2);

                if (count($moretrips) > 1) {

                    $arr['strip'] = $this->getsecuretripcode($moretrips[0]);

                    $arr['trip'] = $this->mktripcode($moretrips[1]);

                } else {

                    $arr['strip'] = $this->getsecuretripcode(substr($exploded[1], 1));

                }

            } else {

                $moretrips = explode("#", $exploded[1], 2);

                if (count($moretrips) > 1) {

                    if (substr($moretrips[1], 0, 1) == "#") {

                        $arr['strip'] = $this->getsecuretripcode(substr($moretrips[1], 1));

                    } else {

                        $arr['strip'] = $this->getsecuretripcode($moretrips[1]);

                    }

                    $arr['trip'] = $this->mktripcode($moretrips[0]);

                } else {

                    $arr['trip'] = $this->mktripcode($exploded[1]);

                }

            }

        } else {

            $arr['name'] = $new;

            $arr['trip'] = "";

        }

        $arr['name'] = $this->conn->real_escape_string($arr['name']);

        $arr['name'] = htmlspecialchars($arr['name']);

        return $arr;

    }

    function processString($string) {

        $new = $string;

        $new = $this->conn->real_escape_string($new);

        $new = htmlspecialchars($new);

        return $new;

    }

    function preprocessComment($string) {

        $new = str_replace("\r", "", $string);

        $new = $this->conn->real_escape_string($new);

        return $new;

    }

    function isFile($path, $board_files = "%") {

        $mime = "";

        if (empty($board_files)) {

            $board_files = "%";

        }

        if (function_exists("finfo_file")) {

            $finfo = finfo_open();

            $mime = finfo_file($finfo, $path, FILEINFO_MIME_TYPE);

        } elseif (function_exists("mime_content_type")) {

            $mime = mime_content_type($path);

        } else {

            if (function_exists("getimagesize")) {

                $a = getimagesize($path);

                $image_type = $a[2];

                /*if ($image_type == IMAGETYPE_GIF)

                {

                $mime = "image/gif";

                }

                if ($image_type == IMAGETYPE_PNG)

                {

                $mime = "image/png";

                }

                if ($image_type == IMAGETYPE_JPEG)

                {

                $mime = "image/jpeg";

                }*/

                $mime = image_type_to_mime_type($image_type);

            } else {

                return false;

            }

        }

        $extensions = $this->conn->query("SELECT * FROM extensions WHERE mimetype='" . $this->conn->real_escape_string($mime) . "'");

        if ($extensions->num_rows == 1) {

            $ext = $extensions->fetch_assoc();

            if (($board_files == "%") || (in_array($ext['ext'], explode(",", $board_files)))) //If the extensions in $board_files are in $ext['ext']

            {

                $nfo['extension'] = $ext['ext'];

                $nfo['image'] = $ext['image'];

                $nfo['mimetype'] = $mime;

                return $nfo;

            } else {

                return false;

            }

        } else {

            return false;

        }

    }

    function mktripcode($pw) {

        $pw = mb_convert_encoding($pw, 'SJIS', 'UTF-8');

        $salt = substr($pw . 'H.', 1, 2);

        $salt = preg_replace('/[^\.-z]/', '.', $salt);

        $salt = strtr($salt, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');

        $trip = substr(crypt($pw, $salt), -10);

        return $trip;

    }

    function mksecuretripcode($pw, $junk = "r3volution") {

        global $securetrip_salt;

        $pw = mb_convert_encoding($pw, 'SJIS', 'UTF-8');

        $pw = str_replace('&', '&amp;', $pw);

        $pw = str_replace('"', '&quot;', $pw);

        $pw = str_replace("'", '&#39;', $pw);

        $pw = str_replace('<', '&lt;', $pw);

        $pw = str_replace('>', '&gt;', $pw);

        $randomstring = "";

        $poststring = "";

        foreach ($_POST as $key => $value) {

            $poststring.= $key . "!" . $value;

        }

        $poststring = strtr($poststring, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');

        $randomstring = md5($poststring) . time() . rand(90, 1681018501) . $junk;

        $salt = substr($pw . 'H!' . $randomstring, 1, 2);

        $salt = preg_replace('/[^.\/0-9:;<=>?@A-Z\[\\\]\^_`a-z]/', '.', $salt);

        $salt = strtr($salt, ':;<=>?@[\]^_`', 'ABCDEFGabcdef');

        $trip = crypt($pw . $randomstring . $securetrip_salt, $salt);

        return $trip;

    }

    function isBanned($IPAddress, $board) {

        $IPAddressbans = $this->conn->query("SELECT * FROM bans WHERE ip='" . $IPAddress . "' AND (expires>" . time() . " OR expires=0) ORDER BY expires DESC;");

        $rangebans = $this->conn->query("SELECT * FROM rangebans ORDER BY expires DESC;");

        $IPAddressbandata = null;

        $rangebandata = null;

        $bandata = null;

        $otherbans = array();

        while ($row = $rangebans->fetch_assoc()) {

            $range = str_replace('*', '(.*)', $row['ip']);

            if ($this->startsWith($range, ".")) {

                if ((strpos($IPAddress, $range) !== FALSE)) {

                    if ($row['boards'] == "%") {

                        $rangebandata = $row;

                        $rangebandata['range'] = 1;

                    } else {

                        if ($board == "%") {

                            $rangebandata = $row;

                            $rangebandata['range'] = 1;

                        } else {

                            $boards = explode(",", $row['boards']);

                            if (in_array($board, $boards)) {

                                $rangebandata = $row;

                                $rangebandata['range'] = 1;

                            }

                        }

                    }

                    $otherbans[] = $row;

                    $otherbans[count($otherbans) - 1]['range'] = 1;

                }

            } elseif ($this->startsWith($IPAddress, $range)) {

                if ($row['boards'] == "%") {

                    $rangebandata = $row;

                    $rangebandata['range'] = 1;

                } else {

                    if ($board == "%") {

                        $rangebandata = $row;

                        $rangebandata['range'] = 1;

                    } else {

                        $boards = explode(",", $row['boards']);

                        if (in_array($board, $boards)) {

                            $rangebandata = $row;

                            $rangebandata['range'] = 1;

                        }

                    }

                }

                $otherbans[] = $row;

                $otherbans[count($otherbans) - 1]['range'] = 1;

            } elseif (preg_match('/' . $range . '/', $IPAddress)) {

                if ($row['boards'] == "%") {

                    $rangebandata = $row;

                    $rangebandata['range'] = 1;

                } else {

                    if ($board == "%") {

                        $rangebandata = $row;

                        $rangebandata['range'] = 1;

                    } else {

                        $boards = explode(",", $row['boards']);

                        if (in_array($board, $boards)) {

                            $rangebandata = $row;

                            $rangebandata['range'] = 1;

                        }

                    }

                }

                $otherbans[] = $row;

                $otherbans[count($otherbans) - 1]['range'] = 1;

            }

        }

        while ($row = $IPAddressbans->fetch_assoc()) {

            if ((empty($IPAddressbandata)) || ($IPAddressbandata['expires'] < $row['expires'])) {

                if ($row['boards'] == "%") {

                    $IPAddressbandata = $row;

                } else {

                    if ($board == "%") {

                        $IPAddressbandata = $row;

                    } else {

                        $boards = explode(",", $row['boards']);

                        if (in_array($board, $boards)) {

                            $IPAddressbandata = $row;

                        }

                    }

                }

            }

            $otherbans[] = $row;

            $otherbans[count($otherbans) - 1]['range'] = 0;

        }

        if (($IPAddressbandata != null) && ($rangebandata != null)) {

            if (($IPAddressbandata['expires'] == 0) || ($IPAddressbandata['expires'] > $rangebandata['expires'])) {

                $bandata = $IPAddressbandata;

            } elseif (($rangebandata['expires'] == 0) || ($rangebandata['expires'] > $IPAddressbandata['expires'])) {

                $bandata = $rangebandata;

            } else {

                $bandata = $IPAddressbandata;

            }

        } elseif (($IPAddressbandata != null) || ($rangebandata != null)) {

            if ($IPAddressbandata != null) {

                $bandata = $IPAddressbandata;

            } elseif ($rangebandata != null) {

                $bandata = $rangebandata;

            } else {

                return 0;

            }

        } else {

            return 0; //not banned



        }

        if (!empty($bandata)) {

            if (count($otherbans) >= 1) {

                $bandata['more'] = $otherbans;

            }

            return $bandata;

        }

        return 0;

    }

    function isWarned($IPAddress) {

        $warns = $this->conn->query("SELECT * FROM warnings WHERE ip='" . $IPAddress . "' AND seen=0 ORDER BY created ASC LIMIT 0, 1;");

        if ($warns->num_rows == 1) {

            $warndata = $warns->fetch_assoc();

            return $warndata;

        } else {

            return 0;

        }

        return 0;

    }

    function banInfo($bandata, $board) {

        if ((empty($bandata['range'])) && ($bandata['seen'] == 0)) {

            $this->conn->query("UPDATE bans SET seen=1 WHERE id=" . $bandata['id']);

        }

        if ($bandata['boards'] == "%") {

            $boards = 1;

        } else {

            $boards = 0;

        }

        if ($bandata['expires'] != 0) {

            $left = floor(($bandata['expires'] - time()) / (60 * 60 * 24));

            $days = floor(($bandata['expires'] - $bandata['created']) / (60 * 60 * 24));

        } else {

            $left = - 1;

            $days = - 1;

        }

?>

		<p>You have been <?php if ($left == - 1) {

            echo "<b>permanently</b>";

        } ?> <?php if (!empty($bandata['range'])) {

            echo "<b>range-</b>";

        } ?>banned from <b><?php if ($boards == 1) {

            echo "all ";

        } else {

            echo "few ";

        } ?></b>boards for the following reason:</p>

		<p><?php echo $bandata['reason']; ?></p>

		<p>You were banned on <b><?php echo date("d/m/Y (D) H:i:s", $bandata['created']); ?></b> and your ban expires

		<b><?php if ($left != - 1) {

            echo " on " . date("d/m/Y (D) H:i:s", $bandata['expires']) . ", which is <b>" . $left . "</b> days from now.";

        } else {

            echo " never";

        }; ?></b>.</p>

		<p>According to our server your IP is: <b><?php echo self::getIP(); ?></b></p>

		<?php

        $range = 0;

        if (!empty($bandata['range_ip'])) {

            $range = 1;

        }

        $appeals = $this->conn->query("SELECT * FROM appeals WHERE ban_id=" . $bandata['id'] . " AND rangeban=" . $range);

        //Your appeal has been sent and is waiting until review, you can change it here.

        $appeal = ($bandata['appeal'] - time()) / (60 * 60 * 24);

        if (($bandata['appeal'] != 0) && ($appeal < 0)) {

?>

			<br /><p>You may appeal your ban in the form below. Please explain why you deserve to be unbanned.</p>

			<?php

            $app_msg = "";

            $app_mail = "";

            if ($appeals->num_rows == 1) {

                $appealdata = $appeals->fetch_assoc();

                $app_msg = $appealdata['msg'];

                $app_mail = $appealdata['email'];

                echo "<b>Your appeal has been sent and is waiting until review, you can change it here.</b>";

            }

?>

			<form action="/imgboard.php" method="POST" class="col s12">
			<input type="hidden" name="mode" value="usrapp" />
			<input type="hidden" name="banid" value="<?php echo $bandata['id']; ?>" />
			<input type="hidden" name="banrange" value="<?php echo $range; ?>" />
			<div class="input-field col s6">
			</div>
			<div class="input-field col s12">
			<textarea id="appeal" class="materialize-textarea" rows="6" name="msg"><?php echo $app_msg; ?></textarea>
			<label for="appeal">Appeal Message</label>
			</div>
			<!--<input type="submit" value="Submit">-->
			<!-- button is bugged... I know, I know.-->
			<button type="submit" class="btn-floating btn-large waves-effect waves-light red"><i class="material-icons">send</i></button>
			</form>

			<?php

        } elseif ($bandata['appeal'] != 0) {

?>

			<p>You'll be allowed to appeal your ban in <b><?php echo floor(($bandata['appeal'] - time()) / (60 * 60 * 24)); ?></b> days.</p>

			<?php

        } else {

?>

			<p>You may not appeal your ban.</p>

			<?php

        }

    }

    function banMessage($board = "%") {

        $bandata = $this->isBanned(self::getIP(), $board);

        if ($bandata != 0) {

?>

				<html lang="en">

        <head>

            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

						<meta charset="UTF-8">

				<title>Banned</title>

            <link href="/css/MIcons.css" rel="stylesheet">

            <link href="/css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>

            <link href="/css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

					</head>

	<body>
<div class="navbar-fixed">

              <nav class="marooncolor" role="navigation">

            <div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">314chan</a>

            

              <ul class="right hide-on-med-and-down">

                <li><a href="rules.html">Rules</a></li>

                <li><a href="faq.html">FAQ</a></li>

                <li><a href="news.html">News</a></li>

                <li><a href="https://irc.314chan.org">IRC</a></li>

              </ul>



              <ul id="nav-mobile" class="side-nav" style="left: -250px;">

                <li><a href="rules.html">Rules</a></li>

                <li><a href="faq.html">FAQ</a></li>

                <li><a href="news.html">News</a></li>

                <li><a href="https://irc.314chan.org">IRC</a></li>

              </ul>

              

              <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>

            </div>

          </nav>

        </div>
<div class="container">
            <div class="card-panel">

				<h4>You are banned ;_;</h4>
				<img style="float: right;" src="/banned/r.php" alt="Mitsuba" height="155px"/>
				
				<?php
					$this->banInfo($bandata, $board);
					if ((!empty($bandata['more'])) && (count($bandata['more']) > 1)) {		
				?>
					<p><b>There's more than one ban placed on your IP.</b></p>
					<?php
						foreach ($bandata['more'] as $ban) {
							echo "<hr />";
							$this->banInfo($ban, $board);
						}
					}
				?>
			</div>
		</body>
	</html>

	<?php

            die();

        }

    }

    function verifyBan($IPAddress, $ban_id, $range) {

        if ((!is_numeric($ban_id)) || (!is_numeric($range))) {

            return false;

        }

        if ($range == 0) {

            $ban = $this->conn->query("SELECT * FROM bans WHERE id=" . $ban_id);

            if ($ban->num_rows == 1) {

                $binfo = $ban->fetch_assoc();

                if ($binfo['ip'] == $IPAddress) {

                    return true;

                }

            }

        } else {

            $ban = $this->conn->query("SELECT * FROM rangebans WHERE id=" . $ban_id);

            if ($ban->num_rows == 1) {

                $binfo = $ban->fetch_assoc();

                $range = str_replace('*', '(.*)', $binfo['ip']);

                if ($this->startsWith($range, ".")) {

                    if ((strpos($IPAddress, $range) !== FALSE)) {

                        return true;

                    }

                } elseif ($this->startsWith($IPAddress, $range)) {

                    return true;

                } elseif (preg_match('/' . $range . '/', $IPAddress)) {

                    return true;

                }

            }

        }

        return false;

    }

    function warningMessage() {

        $warndata = $this->isWarned(self::getIP());

        if ($warndata != 0) {

?>

				<html>

	<head>

	<title>Warning!</title>

<?php

            $first_default = 1;

            $styles = $this->conn->query("SELECT * FROM styles ORDER BY `default` DESC");

            while ($row = $styles->fetch_assoc()) {

                if ($first_default == 1) {

                    echo '<link rel="stylesheet" id="switch" href="' . $this->mitsuba->getPath($row['path'], "index", $row['relative']) . '">';

                    $first_default = 0;

                }

                echo '<link rel="alternate stylesheet" style="text/css" href="' . $this->mitsuba->getPath($row['path'], "index", $row['relative']) . '" title="' . $row['name'] . '">';

            }

?>

	<script type='text/javascript' src='./js/style.js'></script>

	</head>

	<body>

	<div id="doc">

	<br /><br />

	<div class="box-outer top-box">

	<div class="box-inner">

	<div class="boxbar"><h2>You were issued a warning! ;_;</h2></div>

	<div class="boxcontent">

		<img style="float: right;" src="/banned/r.php" alt="Mitsuba" />	<p>You have been issued <b>a warning</b> with the following message:</p>

	<p><?php echo $warndata['reason']; ?></p>

	<p>Your warning was issued on <b><?php echo date("d/m/Y (D) H:i:s", $warndata['created']); ?></b>.</p>

	<p>Now that you have seen this message, you should be able to post again. Click <a href="javascript:history.back()">here</a> to return.</p>

	</div>

	</div>

	</div>

	</body>

	</html>

	<?php

            $this->conn->query("UPDATE warnings SET seen=1 WHERE id=" . $warndata['id']);

            die();

        }

    }

    function pruneOld($board) {

        $board = $this->conn->real_escape_string($board);

        if (!$this->isBoard($board)) {

            return -16;

        }

        $bdata = $this->getBoardData($board);

        $toremove = 9001;

        if ($bdata['type'] == "fileboard") {

            $toremove = $bdata['files'] + 1;

        } elseif (($bdata['type'] == "imageboard") || ($bdata['type'] == "textboard")) {

            $toremove = ($bdata['pages'] + 2) * 10;

        } else {

            return 0;

        }

        $threads = $this->conn->query("SELECT * FROM posts WHERE resto=0 AND board='" . $board . "' AND deleted=0 ORDER BY sticky DESC, lastbumped DESC LIMIT " . $toremove . ", 2000");

        while ($row = $threads->fetch_assoc()) {

            $files = $this->conn->query("SELECT * FROM posts WHERE filename != '' AND resto=" . $row['id'] . " AND board='" . $board . "'");

            while ($file = $files->fetch_assoc()) {

                $filename = $file['filename'];

                if (substr($filename, 0, 8) == "spoiler:") {

                    $filename = substr($filename, 8);

                }

                if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted")) {

                    unlink("./" . $board . "/src/" . $filename);

                    if (file_exists("./" . $board . "/src/thumb/" . $filename)) {

                        unlink("./" . $board . "/src/thumb/" . $filename);

                    }

                }

            }

            $filename = $row['filename'];

            if (substr($filename, 0, 8) == "spoiler:") {

                $filename = substr($filename, 8);

            }

            if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted")) {

                unlink("./" . $board . "/src/" . $filename);

                if (file_exists("./" . $board . "/src/thumb/" . $filename)) {

                    unlink("./" . $board . "/src/thumb/" . $filename);

                }

            }

            $this->conn->query("DELETE FROM posts WHERE resto=" . $row['id'] . " AND board='" . $board . "'");

            $this->conn->query("DELETE FROM posts WHERE id=" . $row['id'] . " AND board='" . $board . "'");

            if ($bdata['hidden'] == 0) {

                unlink("./" . $board . "/res/" . $row['id'] . ".html");

            }

        }

        $deleted_posts = $this->conn->query("SELECT * FROM posts WHERE board='" . $board . "' AND deleted<" . (time() - 3600 * $this->mitsuba->config['keep_hours']) . " AND deleted<>0");

        while ($row = $deleted_posts->fetch_assoc()) {

            if ($row['resto'] == 0) {

                $files = $this->conn->query("SELECT * FROM posts WHERE filename != '' AND resto=" . $row['id'] . " AND board='" . $board . "'");

                while ($file = $files->fetch_assoc()) {

                    $filename = $file['filename'];

                    if (substr($filename, 0, 8) == "spoiler:") {

                        $filename = substr($filename, 8);

                    }

                    if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted")) {

                        unlink("./" . $board . "/src/" . $filename);

                        if (file_exists("./" . $board . "/src/thumb/" . $filename)) {

                            unlink("./" . $board . "/src/thumb/" . $filename);

                        }

                    }

                }

            }

            $filename = $row['filename'];

            if (substr($filename, 0, 8) == "spoiler:") {

                $filename = substr($filename, 8);

            }

            if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted")) {

                unlink("./" . $board . "/src/" . $filename);

                if (file_exists("./" . $board . "/src/thumb/" . $filename)) {

                    unlink("./" . $board . "/src/thumb/" . $filename);

                }

            }

            $this->conn->query("DELETE FROM posts WHERE resto=" . $row['id'] . " AND board='" . $board . "'");

            $this->conn->query("DELETE FROM posts WHERE id=" . $row['id'] . " AND board='" . $board . "'");

            if ($bdata['hidden'] == 0) {

                unlink("./" . $board . "/res/" . $row['id'] . ".html");

            }

        }

    }

    function parse_time($str) {

        if (empty($str)) return false;

        if (($time = @strtotime($str)) !== false) return $time;

        if (!preg_match('/^((\d+)\s?ye?a?r?s?)?\s?+((\d+)\s?mon?t?h?s?)?\s?+((\d+)\s?we?e?k?s?)?\s?+((\d+)\s?da?y?s?)?((\d+)\s?ho?u?r?s?)?\s?+((\d+)\s?mi?n?u?t?e?s?)?\s?+((\d+)\s?se?c?o?n?d?s?)?$/', $str, $matches)) return false;

        $expire = 0;

        if (isset($matches[2])) {

            // Years

            $expire+= $matches[2] * 60 * 60 * 24 * 365;

        }

        if (isset($matches[4])) {

            // Months

            $expire+= $matches[4] * 60 * 60 * 24 * 30;

        }

        if (isset($matches[6])) {

            // Weeks

            $expire+= $matches[6] * 60 * 60 * 24 * 7;

        }

        if (isset($matches[8])) {

            // Days

            $expire+= $matches[8] * 60 * 60 * 24;

        }

        if (isset($matches[10])) {

            // Hours

            $expire+= $matches[10] * 60 * 60;

        }

        if (isset($matches[12])) {

            // Minutes

            $expire+= $matches[12] * 60;

        }

        if (isset($matches[14])) {

            // Seconds

            $expire+= $matches[14];

        }

        return time() + $expire;

    }

}

?>
