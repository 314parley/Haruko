<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission("user.change_password");
if ((!empty($_POST['old'])) && (!empty($_POST['new'])) && (!empty($_POST['new2'])))
		{
			$mitsuba->admin->ui->checkToken($_POST['token']);
			if ($_POST['new']==$_POST['new2'])
			{
		
			$result = $conn->query("SELECT password,salt FROM users WHERE id=".$_SESSION['id']);
			$row = $result->fetch_assoc();
				if ($row['password'] != hash("sha512", $_POST['old'].$row['salt']))
				{
							?>
<?php $mitsuba->admin->ui->startSection($lang['mod/pwd_no_match']); ?>
<a href="?/password"><?php echo $lang['mod/back']; ?></a><?php $mitsuba->admin->ui->endSection(); ?>
			<?php
				} else {
					$conn->query("UPDATE users SET password='".hash("sha512", $_POST['new'].$row['salt'])."' WHERE id=".$_SESSION['id']);
				?>
<?php $mitsuba->admin->ui->startSection($lang['mod/pwd_updated']); ?>
<a href="?/password"><?php echo $lang['mod/back']; ?></a><?php $mitsuba->admin->ui->endSection(); ?>
				<?php
				}
			} else {
				?>
<?php $mitsuba->admin->ui->startSection($lang['mod/pwd_wrong']); ?>
<a href="?/password"><?php echo $lang['mod/back']; ?></a><?php $mitsuba->admin->ui->endSection(); ?>
			<?php
			}
		} else {
		?>
<?php $mitsuba->admin->ui->startSection(); ?>
<div class="col-md-6">
<div class="box box-primary">
                <div class="box-header">
                  <h3 class="box-title"><?php echo $lang['mod/pwd_change'];?></h3>
                </div><!-- /.box-header -->
                <!-- form start -->
                <form action="?/password" method="POST">
	            <?php $mitsuba->admin->ui->getToken($path); ?>
                  <div class="box-body">
                    <div class="form-group">
                      <label for="exampleInputEmail1"><?php echo $lang['mod/pwd_current']; ?></label>
                      <input type="password" class="form-control" name="old">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1"><?php echo $lang['mod/pwd_new']; ?></label>
                      <input type="password" class="form-control" name="new">
                    </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1"><?php echo $lang['mod/pwd_confirm']; ?></label>
                      <input type="password" class="form-control" name="new2">
                    </div>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>
                </form>
              </div>
</div>
<?php $mitsuba->admin->ui->endSection(); ?>
		<?php
		}
?>