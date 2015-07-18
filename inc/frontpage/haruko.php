<?php
namespace Mitsuba;
class Frontpage {
    private $conn;
    private $config;
    private $mitsuba;
    function __construct($connection, &$mitsuba) {
        $this->conn = $connection;
        $this->mitsuba = $mitsuba;
        $this->config = $this->mitsuba->config;
    }
    function generateFrontpage($action = "none") {
        $file = '<!doctype html>';
        $file.= '<html>
					<head>
						<meta charset="UTF-8">
						<title>' . $this->config['sitename'] . '</title>
					</head>
					<body>
					     <header><img src="img/logo.png" alt="314chan Logo"/></header>
						 <nav>...</nav>
						 	<section>
						 		...
						 	</section>
						 <aside>...</aside>
						 <footer>- Haruko + Mitsuba - <br />Content (including posts, images, and content of the site) is owned by the original poster.</footer>
					</body>
					<link rel="stylesheet" type="text/css" href="/css/front.css" title="Frontpage"/>
					</html>';
        $handle = fopen("./" . $this->config['frontpage_url'], "w");
        fwrite($handle, $file);
        fclose($handle);
    }
    function generateNews() {
        $file = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
			"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">';
        $file.= '<html>
			<head>
			<title>' . $this->config['sitename'] . '</title>';
        $first_default = 1;
        $styles = $this->conn->query("SELECT * FROM styles ORDER BY `default` DESC");
        while ($row = $styles->fetch_assoc()) {
            if ($first_default == 1) {
                $file.= '<link rel="stylesheet" id="switch" href="' . $this->mitsuba->getPath($row['path'], "index", $row['relative']) . '">';
                $first_default = 0;
            }
            $file.= '<link rel="alternate stylesheet" style="text/css" href="' . $this->mitsuba->getPath($row['path'], "index", $row['relative']) . '" title="' . $row['name'] . '">';
        }
        $file.= "
			<script type='text/javascript' src='./js/style.js'></script>
			</head>
			<body>";
        $file.= '<div id="doc">
			<br /><br />';
        $file.= '<div class="box-outer top-box">
			<div class="box-inner">
			<div class="boxbar"><h2>News</h2></div>
			<div class="boxcontent">';
        $result = $this->conn->query("SELECT * FROM news ORDER BY date DESC;");
        while ($row = $result->fetch_assoc()) {
            $file.= '<div class="content">';
            $file.= '<h3><span class="newssub">' . $row['title'] . ' by ' . $row['who'] . ' - ' . date("d/m/Y @ H:i", $row['date']) . '</span></span></h3>';
            $file.= $row['text'];
            $file.= '</div>';
        }
        $file.= '</div>
			</div>
			</div>
			</div>
			</body>
			</html>';
        $handle = fopen("./" . $this->config['news_url'], "w");
        fwrite($handle, $file);
        fclose($handle);
    }
}
?>