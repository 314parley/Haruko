<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("reports.view");

if ((!empty($_GET['cl'])) && ($_GET['cl']==1))

	{

$mitsuba->admin->reqPermission("reports.clear.single");

		if ((!empty($_GET['id'])) && (is_numeric($_GET['id'])))

		{

			$conn->query("DELETE FROM reports WHERE id=".$_GET['id']);

		}

	}

	if ((!empty($_GET['m'])) && (!empty($_GET['i'])) && (is_numeric($_GET['i'])))

	{

$mitsuba->admin->reqPermission("reports.clear.multiple");

		switch($_GET['m'])

		{

			case "wtr":

				$rpinfo = $conn->query("SELECT * FROM reports WHERE id=".$_GET['i']);

				$rpinfo = $rpinfo->fetch_assoc();

				$conn->query("DELETE FROM reports WHERE reason='".$conn->real_escape_string($rpinfo['reason'])."'");

				break;

			case "ip":

				$rpinfo = $conn->query("SELECT * FROM reports WHERE id=".$_GET['i']);

				$rpinfo = $rpinfo->fetch_assoc();

				$conn->query("DELETE FROM reports WHERE ip='".$rpinfo['reporter_ip']."'");

				break;



		}

	}

	?>
<?php

if (($mitsuba->admin->checkPermission("reports.clear.all")) && ($mitsuba->admin->canBoard("%")))

{

?>
<?php

}

?>

<div class="col-md-12">
<br />
              <div class="box">

                <div class="box-header">

                  <h3 class="box-title"><?php echo $lang['mod/reports']?></h3>
									<div class="box-tools">
										<div class="input-group">
											<a href="?/reports/clear_all"><?php echo $lang['mod/clear_all']; ?></a>
										</div>
									</div>
                </div><!-- /.box-header -->

                <div class="box-body no-padding">

                  <table class="table table-striped">

                    <tbody><tr>

                      <th style="width: 10px"><?php echo $lang['mod/post']; ?></th>

                      <th><?php echo $lang['mod/file']; ?></th>

                      <th><?php echo $lang['mod/comment']; ?></th>

                      <th style="width: 40px"><?php echo $lang['mod/reason']; ?></th>

                      <th style="width: 40px"><?php echo $lang['mod/reporter_ip']; ?></th>

                      <th style="width: 40px"><?php echo $lang['mod/actions']; ?></th>

                    </tr>

<?php

		require_once( "libs/jbbcode/Parser.php" );

		$parser = new \JBBCode\Parser();

		$bbcode = $conn->query("SELECT * FROM bbcodes;");



		while ($row = $bbcode->fetch_assoc())

		{

			$parser->addBBCode($row['name'], $row['code']);

		}

		$result = $conn->query("SELECT * FROM reports ORDER BY created DESC");

		while ($row = $result->fetch_assoc())

		{

			$post = $conn->query("SELECT * FROM posts WHERE id=".$row['reported_post']." AND board='".$row['board']."'");

			if ($post->num_rows == 0)

			{

				$conn->query("DELETE FROM reports WHERE id=".$row['id']);

				continue;

			}

			$pdata = $post->fetch_assoc();

			$resto = $pdata['id'];

			if ($pdata['resto'] != 0)

			{

				$resto = $pdata['resto'];

			}

			echo "<tr>";

			echo "<td class='text-center text-nowrap'><a href='?/board&b=".$row['board']."&t=".$resto."#p".$row['reported_post']."'>/".$row['board']."/".$row['reported_post']."</a></td>";

			if (!empty($pdata['filename']))

			{

				if ($pdata['filename'] == "deleted")

				{

					echo "<td><img src='./img/deleted.gif' /></td>";

				} elseif (substr($pdata['filename'], 0, 8) == "spoiler:") {

					echo "<td class='text-center'><a href='./".$row['board']."/src/".substr($pdata['filename'], 8)."' target='_blank'><img src='./".$row['board']."/src/thumb/".substr($pdata['filename'], 8)."' /></a></td>";

				} elseif (substr($pdata['filename'], 0, 6) == "embed:") {

					echo "<td><a href='".substr($pdata['filename'], 6)."'>Embed</a></td>";

				} else {

					echo "<td class='text-center'><a href='./".$row['board']."/src/".$pdata['filename']."' target='_blank'><img src='./".$row['board']."/src/thumb/".$pdata['filename']."' /></a></td>";

				}

			} else {

				echo "<td></td>";

			}

			if ($pdata['raw'] == 0)

			{

				echo "<td>".$mitsuba->caching->processComment($row['board'], $pdata['comment'], $parser, 2)."</td>";

			} elseif ($pdata['raw'] == 2)

			{

				echo "<td>".$mitsuba->caching->processComment($row['board'], $pdata['comment'], $parser, 2, 0)."</td>";

			} else {

				echo "<td>".$pdata['comment']."</td>";

			}

			echo "<td>".$row['reason']."</td>";

			echo "<td class='text-center text-nowrap'>".$row['reporter_ip']."</td>";

			echo "<td class='text-center text-nowrap'>[ <a href='?/reports&cl=1&id=".$row['id']."'>C</a> ] [ <a href='?/bans/add&b=".$row['board']."&p=".$row['reported_post']."'>B</a> ";

			if ($mitsuba->admin->checkPermission("reports.clear.multiple"))

			{

				echo "/ <a href='?/bans/add&b=".$row['board']."&p=".$row['reported_post']."&d=1'>&</a> / <a href='?/delete_post&b=".$row['board']."&p=".$row['reported_post']."'>D</a> / <a href='?/delete_post&b=".$row['board']."&p=".$row['reported_post']."&f=1'>F</a> ] <br />";

				echo "[ <a href='?/info&ip=".$pdata['ip']."'>N</a> ] <br />";

				echo "[ <a href='?/reports&m=wtr&i=".$row['id']."'>D_WTR</a> / <a href='?/reports&m=ip&i=".$row['id']."'>D_WTIP</a> ]";

				echo "</td>";

			} else {

				echo "]</td>";

			}

			echo "</tr>";

		}

		?>

                  </tbody></table>

                </div><!-- /.box-body -->

              </div><!-- /.box -->

            </div>

<!--<table>

<thead>

<tr>

<td><?php echo $lang['mod/post']; ?></td>

<td><?php echo $lang['mod/file']; ?></td>

<td class="comments"><?php echo $lang['mod/comment']; ?></td>

<td class="reason"><?php echo $lang['mod/reason']; ?></td>

<td class="reporterIP"><?php echo $lang['mod/reporter_ip']; ?></td>

<td><?php echo $lang['mod/actions']; ?></td>

</tr>

</thead>

<tbody>

<?php

		require_once( "libs/jbbcode/Parser.php" );

		$parser = new \JBBCode\Parser();

		$bbcode = $conn->query("SELECT * FROM bbcodes;");



		while ($row = $bbcode->fetch_assoc())

		{

			$parser->addBBCode($row['name'], $row['code']);

		}

		$result = $conn->query("SELECT * FROM reports ORDER BY created DESC");

		while ($row = $result->fetch_assoc())

		{

			$post = $conn->query("SELECT * FROM posts WHERE id=".$row['reported_post']." AND board='".$row['board']."'");

			if ($post->num_rows == 0)

			{

				$conn->query("DELETE FROM reports WHERE id=".$row['id']);

				continue;

			}

			$pdata = $post->fetch_assoc();

			$resto = $pdata['id'];

			if ($pdata['resto'] != 0)

			{

				$resto = $pdata['resto'];

			}

			echo "<tr>";

			echo "<td class='text-center text-nowrap'><a href='?/board&b=".$row['board']."&t=".$resto."#p".$row['reported_post']."'>/".$row['board']."/".$row['reported_post']."</a></td>";

			if (!empty($pdata['filename']))

			{

				if ($pdata['filename'] == "deleted")

				{

					echo "<td><img src='./img/deleted.gif' /></td>";

				} elseif (substr($pdata['filename'], 0, 8) == "spoiler:") {

					echo "<td class='text-center'><a href='./".$row['board']."/src/".substr($pdata['filename'], 8)."' target='_blank'><img src='./".$row['board']."/src/thumb/".substr($pdata['filename'], 8)."' /></a></td>";

				} elseif (substr($pdata['filename'], 0, 6) == "embed:") {

					echo "<td><a href='".substr($pdata['filename'], 6)."'>Embed</a></td>";

				} else {

					echo "<td class='text-center'><a href='./".$row['board']."/src/".$pdata['filename']."' target='_blank'><img src='./".$row['board']."/src/thumb/".$pdata['filename']."' /></a></td>";

				}

			} else {

				echo "<td></td>";

			}

			if ($pdata['raw'] == 0)

			{

				echo "<td>".$mitsuba->caching->processComment($row['board'], $pdata['comment'], $parser, 2)."</td>";

			} elseif ($pdata['raw'] == 2)

			{

				echo "<td>".$mitsuba->caching->processComment($row['board'], $pdata['comment'], $parser, 2, 0)."</td>";

			} else {

				echo "<td>".$pdata['comment']."</td>";

			}

			echo "<td>".$row['reason']."</td>";

			echo "<td class='text-center text-nowrap'>".$row['reporter_ip']."</td>";

			echo "<td class='text-center text-nowrap'>[ <a href='?/reports&cl=1&id=".$row['id']."'>C</a> ] [ <a href='?/bans/add&b=".$row['board']."&p=".$row['reported_post']."'>B</a> ";

			if ($mitsuba->admin->checkPermission("reports.clear.multiple"))

			{

				echo "/ <a href='?/bans/add&b=".$row['board']."&p=".$row['reported_post']."&d=1'>&</a> / <a href='?/delete_post&b=".$row['board']."&p=".$row['reported_post']."'>D</a> / <a href='?/delete_post&b=".$row['board']."&p=".$row['reported_post']."&f=1'>F</a> ] <br />";

				echo "[ <a href='?/info&ip=".$pdata['ip']."'>N</a> ] <br />";

				echo "[ <a href='?/reports&m=wtr&i=".$row['id']."'>D_WTR</a> / <a href='?/reports&m=ip&i=".$row['id']."'>D_WTIP</a> ]";

				echo "</td>";

			} else {

				echo "]</td>";

			}

			echo "</tr>";

		}

		?>

		</tbody>

		</table>-->

		<?php $mitsuba->admin->ui->endSection(); ?>
