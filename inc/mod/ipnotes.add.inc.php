<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("ipnotes.add");

$IPAddress = "";

		if ((!empty($_GET['ip'])) && (filter_var($_GET['ip'], FILTER_VALIDATE_IP)))

		{

			$IPAddress = $_GET['ip'];

		}

		if ((!empty($_POST['ip'])) && (filter_var($_POST['ip'], FILTER_VALIDATE_IP)))

		{

			$IPAddress = $_POST['ip'];

		}

		

		if (empty($IPAddress))

		{

		?>

<?php $mitsuba->admin->ui->startSection($lang['mod/no_ip']); ?>

<a href="?/ipnotes"><?php echo $lang['mod/back']; ?></a>

<?php $mitsuba->admin->ui->endSection(); ?>



			<?php

		} else {

			if ((!empty($IPAddress)) && (!empty($_POST['note'])))

			{

				$mitsuba->admin->ui->checkToken($_POST['token']);

				$note = processEntry($conn, $_POST['note']);

				$conn->query("INSERT INTO ip_notes (ip, text, created, mod_id) VALUES ('".$IPAddress."', '".$note."', ".time().", ".$_SESSION['id'].")");

				?>

<?php $mitsuba->admin->ui->startSection($lang['mod/ip_note_added']); ?>

<a href="?/ipnotes"><?php echo $lang['mod/back']; ?></a>

<?php $mitsuba->admin->ui->endSection(); ?>



				<?php

			}

		}

		if (empty($_POST['note']))

		{

		?>

<?php $mitsuba->admin->ui->startSection($lang['mod/fill_all_fields']); ?>

<a href="?/ipnotes"><?php echo $lang['mod/back']; ?></a>

<?php $mitsuba->admin->ui->endSection(); ?>



			<?php

		}

?>