<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("ipnotes.view");

?>

<?php $mitsuba->admin->ui->startSection(''); ?>
<div class="row">
<div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?php echo $lang['mod/recent_ip_notes']?></h3>

              <div class="box-tools">
	              <?php printf($lang['mod/showing_notes'], 15); ?> <a href="?/ipnotes/all"><?php echo $lang['mod/show_all']; ?></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">
              <table class="table table-hover">
                <tbody>
	            <tr>
                  <th><?php echo $lang['mod/created']; ?></th>
                  <th>IP Address</th>
                  <th><?php echo $lang['mod/note']; ?></th>
                  <th><?php echo $lang['mod/delete']; ?></th>
                </tr>
<?php

$result = $conn->query("SELECT * FROM ip_notes LIMIT 0, 15;");
if($result->num_rows > 0){
	while ($row = $result->fetch_assoc()){
	echo "<tr>";
	echo "<td>".date("d/m/Y(D)H:i:s", $row['created'])."</td>";
	echo "<td>".$row['ip']."</td>";
	echo "<td>".$row['text']."</td>";
	echo "<td><a href='?/ipnotes/delete&id=".$row['id']."'>".$lang['mod/delete']."</a></td>";
	echo "</tr>";
	}
}else{
	
}

?>              </tbody></table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        </div>
<?php #$mitsuba->admin->ui->startSection("");?>
<div class="row">
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo $lang['mod/add_ip_note']?></h3>
			</div>
            <!-- /.box-header -->
            <!-- form start -->
            <form role="form" action="?/ipnotes/add" method="POST">
	            <?php $mitsuba->admin->ui->getToken($path); ?>
              <div class="box-body">
                <div class="form-group">
                  <label for="IPAddr"><?php echo $lang['mod/ip']; ?></label>
                  <input type="text" name="ip" class="form-control" id="IPAddr" placeholder="Enter IP Address">
                </div>
                <div class="form-group">
                  <label for="IPNote">IP Note</label>
                  <textarea name="note" class="form-control" id="IPNote" placeholder="Enter note for IP" cols="70" rows="12"></textarea>
                </div>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div></div>

<?php $mitsuba->admin->ui->endSection(); ?>