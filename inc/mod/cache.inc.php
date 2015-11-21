<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("config.rebuild");

$mitsuba->admin->ui->checkToken($_POST['token']);

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

			$mitsuba->admin->logAction($lang['log/rebuilt_cache']);

		}

		

		if ((!empty($_POST['thumbs'])) && ($_POST['thumbs']==1))

		{

			$result = $conn->query("SELECT * FROM boards ORDER BY short ASC;");

			while ($row = $result->fetch_assoc())

			{

				$mitsuba->caching->regenThumbnails($row['short']);

			}

			$mitsuba->admin->logAction($lang['log/rebuilt_thumbs']);

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
		
		if ((!empty($_POST['b_json'])) && ($_POST['b_json']==1))

		{
			$sql = "SELECT * FROM `boards`";
			if(!$result = $conn->query($sql)){
				die('There was an error running the query [' . $conn->error . ']');
			}else{
				while($row = $result->fetch_assoc()){
					//changing various rows because Vichan's format is a bit more widly used. also reorganizing them, because fuck PHP.
					$row["uri"] = $row["short"];
					unset($row["short"]);
					$row["title"] = $row["name"];
					unset($row["name"]);
					$row["subtitle"] = $row["message"];
					unset($row["message"]);
					$row["meta_description"] = $row["des"];
					unset($row["des"]);
					$row["forced_anonymous"] = $row["noname"];
					unset($row["noname"]);
					$row["default_name"] = $row["anonymous"];
					unset($row["anonymous"]);
					$row["board_type"] = $row["type"];
					unset($row["type"]);
					
				//cooldown timers [define what `cooldowns` is here]
					$row["cooldowns"] = $row["cooldowns"];
					//cooldowns between threads
					$row["cooldowns"]["threads"] = $row["time_between_threads"];
					unset($row["time_between_threads"]);
					//cooldowns between posts
					$row["cooldowns"]["posts"] = $row["time_between_posts"];
					unset($row["time_between_posts"]);
					$row["cooldowns"]["delete"] = $row["time_to_delete"];
					unset($row["time_to_delete"]);
						
					$row["max_filesize"] = $row["filesize"];
					unset($row["filesize"]);
					$row["bump_limit"] = $row["bumplimit"];
					unset($row["bumplimit"]);
					$row["max_pages"] = $row["pages"];
					unset($row["pages"]);
					$row["is_hidden"] = $row["hidden"];
					unset($row["hidden"]);
					$row["is_unlisted"] = $row["unlisted"];
					unset($row["unlisted"]);
					$row["has_captcha"] = $row["captcha"];
					unset($row["captcha"]);
					
					//unset the ones we don't need (for now).
					unset($row["bumplimit"]);
					unset($row["spoilers"]);
					unset($row["ids"]);
					unset($row["embeds"]);
					unset($row["bbcode"]);
					unset($row["nodup"]);
					unset($row["extensions"]);
					unset($row["nofile"]);
					unset($row["allow_all_sites"]);
					unset($row["files"]);
					unset($row["links"]);
					unset($row["catalog"]);
					unset($row["multifile"]);
					unset($row["maxchars"]);
					unset($row["overboard_boards"]);
					unset($row["file_replies"]);
					unset($row["allow_replies"]);
					$boardArr[] = $row;
				}
			}
			$finalJSON = json_encode($boardArr,JSON_NUMERIC_CHECK);
			file_put_contents("boards.json",$finalJSON);
		}
		?>

<?php $mitsuba->admin->ui->startSection($lang['mod/rebuilding_done']); ?>



<a href="?/rebuild"><?php echo $lang['mod/back']; ?></a>

<?php $mitsuba->admin->ui->endSection(); ?>