<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(3);
		if ((!empty($_POST['links'])) && ($_POST['links']==1))
		{
			
			$mitsuba->caching->rebuildBoardLinks();
		}
		
		if ((!empty($_POST['boards'])) && ($_POST['boards']==1))
		{
			$result = $conn->query("SELECT * FROM boards ORDER BY short ASC;");
			while ($row = $result->fetch_assoc())
			{
				$mitsuba->caching->rebuildBoardCache($row['short']);
			}
			logAction($conn, $lang['log/rebuilt_cache']);
		}
		
		if ((!empty($_POST['thumbs'])) && ($_POST['thumbs']==1))
		{
			$result = $conn->query("SELECT * FROM boards ORDER BY short ASC;");
			while ($row = $result->fetch_assoc())
			{
				$mitsuba->caching->regenThumbnails($row['short']);
			}
			logAction($conn, $lang['log/rebuilt_thumbs']);
		}
		
		if ((!empty($_POST['static'])) && ($_POST['static']==1))
		{
			$mitsuba->caching->generateFrontpage();
			$mitsuba->caching->generateNews();
			$result = $conn->query("SELECT * FROM pages;");
			while ($row = $result->fetch_assoc())
			{
				$mitsuba->caching->generatePage($row['name']);
			}
		}
		?>
					<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/rebuilding_done']; ?></h2></div>
<div class="boxcontent">
<a href="?/rebuild"><?php echo $lang['mod/back']; ?></a>
</div>
</div>
</div>