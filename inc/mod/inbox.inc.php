<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

		$mitsuba->admin->reqPermission("user.inbox");

?>

<?php $mitsuba->admin->ui->startSection($lang['mod/inbox']."<small>".$pms." unread</small>"); ?>

<section class="content">

  <div class="row">

	<div class="col-md-12">

		<?php  if($pms == 0){?>

		<br />

		  <div class="box box-solid box-success">

			<div class="box-header">

		  	<h3 class="box-title"><i class="fa fa-thumbs-up"></i> Awesome!</h3>

			</div><!-- /.box-header -->

			<div class="box-body">

		  	No Private Messages available!

			</div><!-- /.box-body -->

		  </div>

		<?php  }else{?>

	  <div class="box box-primary">

		<div class="box-header with-border">

		  <h3 class="box-title">Inbox</h3>

		  <div class="box-tools pull-right">

			<div class="has-feedback">

			  <input type="text" class="form-control input-sm" placeholder="Search Mail">

			  <span class="glyphicon glyphicon-search form-control-feedback"></span>

			</div>

		  </div><!-- /.box-tools -->

		</div><!-- /.box-header -->

		<div class="box-body no-padding">

		  <div class="mailbox-controls">

			<!-- Check all button -->

			<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>

			<div class="btn-group">

			  <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>

			  <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>

			  <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>

			</div><!-- /.btn-group -->

			<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

		  </div>

		  <div class="table-responsive mailbox-messages">

			<table class="table table-hover table-striped">

			  <tbody>



				  <?php

				  $pms = $conn->query("SELECT users.username, pm.* FROM pm LEFT JOIN users ON pm.from_user=users.id WHERE pm.to_user=".$_SESSION['id']." ORDER BY pm.created DESC");

				  while ($row = $pms->fetch_assoc())

				  {



				  	echo "<tr>";

					//echo '<td class="mailbox-star"><a href="#"><i class="fa fa-trash-o"></i></a></td>';

					echo "<td class='mailbox-name'><a href='?/inbox/read&id=".$row['id']."'>&nbsp;&nbsp;".$row['username']."</a></td>";

				  	if ($row['read_msg']==0)

				  	{

					  	//PM hasn't been read yet.

				  		echo "<td class='mailbox-subject'><strong>".strip_tags($row['title'])."</strong></td>";

				  	} else {

					  	// PM is read

				  		echo "<td class='mailbox-subject'>".strip_tags($row['title'])."</td>";

				  	}

				  	echo '<td class="mailbox-attachment"></td>';

				  	echo "<td class='mailbox-date'>".date("Y/m/d H:i A", $row['created'])."</td>";

				  	echo "<td class=''><a href='?/inbox/delete&id=".$row['id']."'><i class='fa fa-trash-o'></i></a></td>";

				  	echo "</tr>";

				  }

				  ?>

			  </tbody>

			</table><!-- /.table -->

		  </div><!-- /.mail-box-messages -->

		</div><!-- /.box-body -->

		<div class="box-footer no-padding">

		  <div class="mailbox-controls">

			<!-- Check all button -->

			<button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>

			<div class="btn-group">

			  <button class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>

			  <button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>

			  <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>

			</div><!-- /.btn-group -->

			<button class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>

		  </div>

		</div>

	  </div><!-- /. box -->

	  <?php  } ?>

	</div><!-- /.col -->

  </div><!-- /.row -->

</section>

<!--

<table>

<thead>

<td><?php echo $lang['mod/title']; ?></td>

<td><?php echo $lang['mod/date']; ?></td>

<td><?php echo $lang['mod/from']; ?></td>

<td><?php echo $lang['mod/delete']; ?></td>

</thead>

<tbody>-->

		<?php

/*		$pms = $conn->query("SELECT users.username, pm.* FROM pm LEFT JOIN users ON pm.from_user=users.id WHERE pm.to_user=".$_SESSION['id']." ORDER BY pm.created DESC");

		while ($row = $pms->fetch_assoc())

		{

			echo "<tr>";

			if ($row['read_msg']==0)

			{

				echo "<td class='text-center'><b><a href='?/inbox/read&id=".$row['id']."'>".$row['title']."</a></b></td>";

			} else {

				echo "<td class='text-center'><a href='?/inbox/read&id=".$row['id']."'>".$row['title']."</a></td>";

			}

			echo "<td class='text-center text-nowrap'>".date("d/m/Y @ H:i", $row['created'])."</td>";

			echo "<td class='text-center'>".$row['username']."</td>";

			echo "<td class='text-center'><a href='?/inbox/delete&id=".$row['id']."'>".$lang['mod/delete']."</a></td>";

			echo "</tr>";

		}*/

		?>

<!--		</tbody>-->

<?php $mitsuba->admin->ui->endSection(); ?>
