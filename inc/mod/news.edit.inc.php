<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(2);
		if ((isset($_GET['b'])) && (is_numeric($_GET['b'])))
	{
	$result = $conn->query("SELECT * FROM news WHERE id=".$_GET['b']);
	if ($result->num_rows != 0)
	{
	if (empty($_POST['text']))
	{
	$data = $result->fetch_assoc();
	?>
<?php $mitsuba->admin->ui->startSection($lang['mod/edit_news_entry']); ?>

<form action="?/news/edit&b=<?php echo $_GET['b']; ?>" method="POST">
<?php $mitsuba->admin->ui->getToken($path); ?>
<?php echo $lang['mod/by']; ?>: <input type="text" name="who" value="<?php echo $data['who']; ?>" /><br />
<?php echo $lang['mod/title']; ?>: <input type="text" name="title" value="<?php echo $data['title']; ?>"/><br />
<?php echo $lang['mod/text']; ?>: <br />
<textarea name="text" cols="70" rows="10"><?php echo $data['text']; ?></textarea>
<input type="submit" value="<?php echo $lang['mod/submit']; ?>" />
</form>
<?php $mitsuba->admin->ui->endSection(); ?><br />
	<?php
	} else {
			$mitsuba->admin->ui->checkToken($_POST['token']);
		if ($_SESSION['type']==3)
		{
		updateEntry($conn, 1, $_GET['b'], $_POST['who'], $_POST['title'], $_POST['text']);
		} else {
		updateEntry($conn, 1, $_GET['b'], $_POST['who'], $_POST['title'], $_POST['text'], 1);
		}
		?>
<?php $mitsuba->admin->ui->startSection($lang['mod/post_updated']); ?>
<a href="?/news"><?php echo $lang['mod/back']; ?></a><?php $mitsuba->admin->ui->endSection(); ?>
		<?php
	}
	} else {
	?>
	<meta http-equiv="refresh" content="0;URL='?/news'" />
	<?php
	}
	} else {
	?>
	<meta http-equiv="refresh" content="0;URL='?/news'" />
	<?php
	}
?>