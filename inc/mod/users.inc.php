<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("users.view");

	?>
<section class="content">
<div class="col-md-12">
<div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $lang['mod/new_user'] ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
					<form action="?/users/add" method="POST">
					<?php $mitsuba->admin->ui->getToken($path); ?>
					<?php echo $lang['mod/username']; ?>: <input type="text" name="username" /><br />
					<?php echo $lang['mod/password']; ?>: <input type="password" name="password"/><br />
					<?php echo $lang['mod/type']; ?>: 
					<select name="type">
					<?php 
					$groups = $conn->query("SELECT * FROM groups");
					while ($row = $groups->fetch_assoc()){
						echo "<option value=".$row['id'].">".$row['name']."</option>";
						}
					?>
					</select>
					<br /><br />					
					<?php
					$mitsuba->admin->ui->getBoardList();
					?>
					<br />
					<input type="submit" value="<?php echo $lang['mod/add_user']; ?>" />
					</form>
                </div><!-- /.box-body -->
              </div>
</div>
</section>
<section class="content">
<div class="col-md-12">
<div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $lang['mod/all_users'] ?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                  <table class="table table-bordered">
                    <tbody>
	                    <tr>
                      <th><?php echo $lang['mod/username']; ?></th>
                      <th><?php echo $lang['mod/type']; ?></th>
                      <th><?php echo $lang['mod/boards']; ?></th>
                      <th><?php echo $lang['mod/edit']; ?></th>
                      <th><?php echo $lang['mod/delete']; ?></th>
                    </tr>
                    <?php
	                    $result = $conn->query("SELECT users.*, groups.name AS gname FROM users LEFT JOIN groups ON users.group=groups.id ORDER BY FIELD(gname, 'Administrator', 'Moderator', 'Janitor', 'Disabled'), users.username;");
	                    $usern = $result->num_rows;
	                    while ($row = $result->fetch_assoc()){
		                    echo "<tr>";
		                    	echo "<td>".$row['username']."</td>";
		                    	echo "<td>";
		                    	echo $row['gname'];
		                    	echo "</td>";
		                    if ($row['boards']=="%"){
			                    echo "<td>All boards</td>";
			                }else{
				                echo "<td>".$row['boards']."</td>";
				            }
				            echo "<td><a href='?/users/edit&id=".$row['id']."'>".$lang['mod/edit']."</a></td>";
				            if ($usern != 1){
					            echo "<td><a href='?/users/delete&id=".$row['id']."'>".$lang['mod/delete']."</a></td>";
					        } else {
						        echo "<td></td>";
						    }
						    echo "</tr>";
						    }
?>

                  </tbody></table>
                </div><!-- /.box-body -->
              </div>
</div>
</section>

<?php $mitsuba->admin->ui->endSection(); ?>
