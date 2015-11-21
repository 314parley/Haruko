<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("logs.view");

?>

<?php $mitsuba->admin->ui->startSection(null); ?>



<div class="row">

  <div class="col-xs-12">

	<div class="box">

	  <div class="box-header">

		<h3 class="box-title">Action Log</h3>

		<div class="box-tools">

		</div>

	  </div><!-- /.box-header -->

	  <div class="box-body table-responsive no-padding">

		<table class="table table-hover">

		  <tbody><tr>

			<th>User</th>

			<th>Event</th>

			<th>Date</th>

			</tr>

		<?php

		$log = $conn->query("SELECT log.*, users.username FROM log LEFT JOIN users ON log.mod_id=users.id ORDER BY date DESC");

		while ($row = $log->fetch_assoc())

		{

			echo "<tr>";

			echo "<td class=''>".$row['username']."</td>";

			echo "<td>".$row['event']."</td>";

			echo "<td class=''>".date("d/m/Y(D)H:i:s", $row['date'])."</td>";

			echo "</tr>";

		}

		?>

	</tbody></table>

	  </div><!-- /.box-body -->

	</div><!-- /.box -->

  </div>

</div>

<?php $mitsuba->admin->ui->endSection(); ?>