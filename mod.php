<?php
ob_start();
if (!file_exists("./config.php")) {
    header("Location: ./install.php");
    die();
}
define("IN_MOD", TRUE);
session_start();
include ("config.php");
include ("version.php");
include ("inc/mitsuba.php");
include ("inc/strings/mod.strings.php");
include ("inc/strings/imgboard.strings.php");
include ("inc/strings/log.strings.php");
if (count($_GET) == 0) {
    $path = "/";
} else {
    $pkey = array_keys($_GET);
    if (substr($pkey[0], 0, 1) == "/") {
        $path = $pkey[0];
    } else {
        $path = "/";
    }
}
if ($path != "/") {
    $path = rtrim($path, "/ ");
}
if (((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == 0)) && (!(($path == "/") || ($path == "/login")))) {
    echo '<div class="callout callout-danger">
                    <h4>Uh oh!</h4>
                    <p>'.$lang['mod/not_logged_in'].'</p>
                  </div>';
}
$conn = new mysqli($db_host, $db_username, $db_password, $db_database);
$mitsuba = new Mitsuba($conn);
function deleteEntry($conn, $type, $id) {
    global $mitsuba;
    if (!is_numeric($id)) {
        return -1;
    }
    $table = "";
    if ($type == 0) {
        $table = "announcements";
    }
    if ($type == 1) {
        $table = "news";
    }
    if ($mitsuba->admin->checkPermission($table . ".delete", $_SESSION['group'])) {
        $conn->query("DELETE FROM " . $table . " WHERE id=" . $id);
    } elseif ($mitsuba->admin->checkPermission($table . ".delete.own", $_SESSION['group'])) {
        $result = $conn->query("SELECT * FROM " . $table . " WHERE id=" . $id);
        $entry = $result->fetch_assoc();
        if ($entry['mod_id'] == $_SESSION['id']) {
            $conn->query("DELETE FROM " . $table . " WHERE id=" . $id);
        }
    } else {
        die("Insufficient permissions");
    }
    if ($type == 1) {
        $mitsuba->caching->generateNews();
    }
}
function updateEntry($conn, $type, $id, $who, $title, $text) {
    global $mitsuba;
    if (!is_numeric($id)) {
        return -1;
    }
    $who = $conn->real_escape_string($who);
    $title = $conn->real_escape_string($title);
    $text = $conn->real_escape_string($text);
    $table = "";
    if ($type == 0) {
        $table = "announcements";
    }
    if ($type == 1) {
        $table = "news";
    }
    if ($mitsuba->admin->checkPermission($table . ".update", $_SESSION['group'])) {
        $conn->query("UPDATE " . $table . " SET who='" . $who . "', title='" . $title . "', text='" . $text . "' WHERE id=" . $id);
    } elseif ($mitsuba->admin->checkPermission($table . ".update.own", $_SESSION['group'])) {
        $result = $conn->query("SELECT * FROM " . $table . " WHERE id=" . $id);
        $entry = $result->fetch_assoc();
        if ($entry['mod_id'] == $_SESSION['id']) {
            $conn->query("UPDATE " . $table . " SET who='" . $who . "', title='" . $title . "', text='" . $text . "' WHERE id=" . $id);
        }
    }
    if ($type == 1) {
        $mitsuba->caching->generateNews();
    }
}
function processEntry($conn, $string) {
    $new = str_replace("\r", "", $string);
    $new = $conn->real_escape_string($new);
    $lines = explode("\n", $new);
    $new = "";
    foreach ($lines as $line) {
        if (substr($line, 0, 1) != "<") {
            $new.= "<p>" . strip_tags($line, "<script><style><link><meta><canvas>") . "</p>";
        }
    }
    return $new;
}
if ((!empty($_SESSION['logged'])) && (!empty($_SESSION['cookie_set'])) && ($_SESSION['cookie_set'] == 2)) {
    $cookie = "";
    $cookie.= ($mitsuba->admin->checkPermission("post.ignorenoname") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.ignoresizelimit") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.raw") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.antibump") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.sticky") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.closed") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.nofile") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.fakeid") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.ignorecaptcha") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.capcode") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.customcapcode") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.viewip") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.delete.single") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("post.edit") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("bans.add") ? 1 : 0);
    $cookie.= ($mitsuba->admin->checkPermission("bans.add.request") ? 1 : 0);
    setcookie('in_mod', $cookie, 0);
    $_SESSION['cookie_set'] = 1;
}
if (($path != "/nav") && ($path != "/board") && ($path != "/board/action") && (($path != "/") || ((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == 0))) && (substr($path, 0, 5) != "/api/")) {
?>
<?php
}
if ((!empty($_SESSION['logged'])) && ($_SESSION['logged'] == 1) && ($_SESSION['ip'] != $_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $mitsuba->admin->logAction(sprintf($lang['log/ip_changed'], $_SESSION['ip'], $_SERVER['HTTP_CF_CONNECTING_IP']));
    $_SESSION['ip'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
}
switch ($path) {
    case "/":
        include ("inc/mod/main.inc.php");
    break;
        //yes, I know this is hard-wired into the code. We need to figure out a better way.
        
    case "/login":
        require "inc/mod/login.inc.php";
        header("Location: /mod.php");
    break;
        // /?logout
        
    case "/logout":
        setcookie('in_mod', '0', time() - 86400);
        session_destroy();
        header("Location: /mod.php");
    break;
    default:
        /*$file = "inc/mod/".str_replace(array("/", "\\", ".."), ".", trim($path, " \t\n\r\0\x0B/\\")).".inc.php";
        if (file_exists($file))
        {
        include($file);
        } else {
        $modules = $conn->query("SELECT * FROM module_pages WHERE url='/".$conn->real_escape_string(str_replace(array("/", "\\", "/"), ".", trim($path, " \t\n\r\0\x0B/\\")))."'");
        while ($module = $modules->fetch_assoc())
        {
        include("./".$module['namespace']."/".$module['file']);
        $pageclass = new $module['class']($conn, $mitsuba);
        $pageclass->$module['method']();
        }
        }*/
        include ("inc/mod/main.inc.php");
    break;
}
if (($path != "/nav") && ($path != "/board") && ($path != "/board/action") && (($path != "/") || ((!isset($_SESSION['logged'])) || ($_SESSION['logged'] == 0))) && (substr($path, 0, 5) != "/api/")) {
?>
<?php
}
$conn->close();