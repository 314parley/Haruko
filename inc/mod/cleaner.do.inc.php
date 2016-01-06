<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}
if($mitsuba->admin->canBoard("%")){
$mitsuba->admin->reqPermission("config.cleaner");

$mitsuba->admin->ui->checkToken($_POST['token']);

		if ((!empty($_POST['bans'])) && ($_POST['bans']==1))

		{

			$conn->query("DELETE FROM bans WHERE expires<".time());

		}

		if ((!empty($_POST['warnings'])) && ($_POST['warnings']==1))

		{

			$conn->query("DELETE FROM warnings WHERE seen=1");

		}
		
		if ((!empty($_POST['posts'])) && ($_POST['posts']==1))
		{
			$conn->query("DELETE FROM posts WHERE posts.deleted > 0");	
		}
		
		?>

<?php $mitsuba->admin->ui->startSection($lang['mod/cleaning_done']); ?>



<a href="?/cleaner"><?php echo $lang['mod/back']; ?></a>

<?php $mitsuba->admin->ui->endSection(); }
	else{
		echo "Sorry. This is for Global Admins only.";
	}
?>