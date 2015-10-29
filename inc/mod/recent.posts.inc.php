<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("recent.posts");

if ((!empty($_GET['max'])) && (is_numeric($_GET['max'])))

{

	$max = $_GET['max'];

} else {

	$max = 50;

}

$mitsuba->admin->ui->startSection();

?>

<div class="row">

            <div class="col-xs-12">

              <div class="box">

                <div class="box-header">

                  <h3 class="box-title"><?php echo sprintf($lang['mod/recent_n_posts'], $max)?></h3>

                  <div class="box-tools">

                    <div class="input-group">

<?php echo $lang['mod/show_recent_none']; ?>: <a href="?/recent/posts">50</a>&nbsp;<a href="?/recent/posts&max=100">100</a>&nbsp;<a href="?/recent/posts&max=250">250</a>&nbsp;<a href="?/recent/posts&max=500">500</a>

                    </div>

                  </div>

                </div><!-- /.box-header -->

                <div class="box-body table-responsive no-padding">

                  <table class="table table-hover">

                    <thead>

						<tr>

							<th><?php echo $lang['mod/post']; ?></th>

							<th><?php echo $lang['mod/name']; ?></th>

							<th><?php echo $lang['mod/e_mail']; ?></th>

							<th><?php echo $lang['mod/date']; ?></th>

							<th><?php echo $lang['mod/comment']; ?></th>

							<th><?php echo $lang['mod/subject']; ?></th>

							<th><?php echo $lang['mod/file']; ?></th>

							<th><?php echo $lang['mod/delete']; ?></th>

						</tr>

					</thead>

                    <tbody>

	                    <?php

		                    $post_array = array();

							$num = 0;

							require_once( "libs/jbbcode/Parser.php" );

							$parser = new \JBBCode\Parser();

							$bbcode = $conn->query("SELECT * FROM bbcodes;");

							while ($row = $bbcode->fetch_assoc())

							{

								$parser->addBBCode($row['name'], $row['code']);

							}

							$posts = $conn->query("SELECT * FROM posts ORDER BY date DESC LIMIT 0, ".$max);

							

							while ($row = $posts->fetch_assoc()){

								echo "<tr>";

								echo "<td>";

									$resto = $row['resto'];

									$op = 0;

									if ($row['resto'] == 0) { $resto = $row['id']; $op = 1; }

									echo "<a href='?/board&b=".$row['board']."&t=".$resto."'>/".$row['board']."/".$row['id']."</a> ";

									if ($op == 1) { echo "<strong>OP</strong>"; }

								echo "</td>";

								echo "<td>";

								$trip = "";

									if (!empty($row['trip']))

										{

											$trip = "<span class='postertrip'>!".$row['trip']."</span>";

										}

									if (!empty($row['capcode_text']))

										{

											echo '<span class="name"><span style="'.$row['capcode_style'].'">'.$row['name'].'</span></span>'.$trip.' <span class="commentpostername"><span style="'.$row['capcode_style'].'">## '.$row['capcode_text'].'</span></span>';

											} else {

												echo '<span class="name">'.$row['name'].'</span>'.$trip;

											}

								echo "</td>";

								echo "<td>";

								if (!empty($row['email']))

										{

											echo $row['email'];

										}else{

											echo "<em>No email</em>";

										}

								echo "</td>";

								echo "<td>";

									echo date("d/m/Y @ H:i", $row['date']);

								echo "</td>";

								echo "<td>";

									if ($row['raw'] != 1){

										if ($row['raw'] == 2){

											$comment = $mitsuba->caching->processComment($row['board'], $row['comment'], $parser, 2, 0);

										} else {

											$comment = $mitsuba->caching->processComment($row['board'], $row['comment'], $parser, 2);

										}

									} else {

										$comment = $row['comment'];

									}

									echo $comment;

								echo "</td>";

								echo "<td>";

									if (!empty($row['subject'])){
										echo $row['subject'];
									}else{
										echo "<em>No Subject</em>";
									}

								echo "</td>";

								echo "<td>";

									if (!empty($row['filename'])){

										if ($row['filename'] == "deleted"){

											echo "<img src='./img/deleted.gif' />";

										} elseif (substr($row['filename'], 0, 8) == "spoiler:") {

											echo "<a href='./".$row['board']."/src/".substr($row['filename'], 8)."' target='_blank'><img src='./".$row['board']."/src/thumb/".substr($row['filename'], 8)."' /></a><br /><b>Spoiler image</b>";

										} elseif (substr($row['filename'], 0, 6) == "embed:") {

											echo "<a href='".substr($row['filename'], 6)."'>Embed</a>";

										} else {

											echo "<a href='/".$row['board']."/src/".$row['filename']."' target='_blank'><img src='./".$row['board']."/src/thumb/".$row['filename']."' /></a>";

										}

										} else {

											echo "<em>No File</em>";

										}

								echo "</td>";

								echo "<td>";

								echo '[<a href="?/delete_post&b='.$row['board'].'&p='.$row['id'].'">D</a>] [<a href="?/delete_post&b='.$row['board'].'&p='.$row['id'].'&f=1">F</a>] [<a href="?/bans/add&b='.$row['board'].'&p='.$row['id'].'">B</a>]';

								echo "</td>";

								echo "</tr>";

							}

		                ?>

                    <!--<tr>

                      <td>183</td>

                      <td>John Doe</td>

                      <td>11-7-2014</td>

                      <td><span class="label label-success">Approved</span></td>

                      <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>

                    </tr>

                    <tr>

                      <td>219</td>

                      <td>Alexander Pierce</td>

                      <td>11-7-2014</td>

                      <td><span class="label label-warning">Pending</span></td>

                      <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>

                    </tr>

                    <tr>

                      <td>657</td>

                      <td>Bob Doe</td>

                      <td>11-7-2014</td>

                      <td><span class="label label-primary">Approved</span></td>

                      <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>

                    </tr>

                    <tr>

                      <td>175</td>

                      <td>Mike Doe</td>

                      <td>11-7-2014</td>

                      <td><span class="label label-danger">Denied</span></td>

                      <td>Bacon ipsum dolor sit amet salami venison chicken flank fatback doner.</td>

                    </tr>-->

                  </tbody></table>

                </div><!-- /.box-body -->

              </div><!-- /.box -->

            </div>

          </div>



<?php $mitsuba->admin->ui->endSection(); ?>
