<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("warnings.view");

if ((isset($_GET['del'])) && ($_GET['del']==1))

	{

$mitsuba->admin->reqPermission("warnings.delete");

		if ((!empty($_GET['b'])) && (is_numeric($_GET['b'])))

		{

			$conn->query("DELETE FROM warnings WHERE id=".$_GET['b']);

		}

	}

	$mitsuba->admin->ui->startSection(''); 

	?>
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo sprintf($lang['mod/recent_warnings'], $_GET['c'])?></h3>

              <div class="box-tools">
	              <a href="?/warnings"><?php printf($lang['mod/showing_warnings'], 15); ?></a>
	              | <a href="?/warnings/all"><?php echo $lang['mod/show_all']; ?></a>
	              | <?php printf($lang['mod/show_recent'], 100); ?>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
                <tr>
                  <th><?php echo $lang['mod/ip']; ?></th>
                  <th><?php echo $lang['mod/reason']; ?></th>
                  <th><?php echo $lang['mod/staff_note']; ?></th>
                  <th><?php echo $lang['mod/created']; ?></th>
                  <th><?php echo $lang['mod/shown']; ?></th>
                  <th><?php echo $lang['mod/delete']; ?></th>
                </tr>
                <?php
$result = $conn->query("SELECT * FROM warnings ORDER BY created LIMIT 0, ".$_GET['c'].";");
while ($row = $result->fetch_assoc()){
echo "<tr>";
echo "<td>".$row['ip']."</td>";
echo "<td>".$row['reason']."</td>";
echo "<td>".$row['note']."</td>";
echo "<td>".date("d/m/Y @ H:i", $row['created'])."</td>";
if ($row['seen']==1){
echo "<td class='text-center'>YES</td>";
} else {
	echo "<td class='text-center'><b>NO</b></td>";
}
if ($_SESSION['type']>=2){
echo "<td class='text-center'><a href='?/warnings&del=1&b=".$row['id']."'>".$lang['mod/delete']."</a></td>";
} else {
echo "<td></td>";
}
echo "</tr>";
}
?>
              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div><?php $mitsuba->admin->ui->endSection(); ?>