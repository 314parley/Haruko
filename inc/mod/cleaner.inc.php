<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(3);
?>
<?php $mitsuba->admin->ui->startSection($lang['mod/cleaner']); ?>

<form action="?/cleaner/do" method="POST">
<input type="checkbox" name="bans" value=1><?php echo $lang['mod/delete_expired_bans']; ?></input><br />
<input type="checkbox" name="warnings" value=1><?php echo $lang['mod/delete_shown_warnings']; ?></input><br />
<input type="submit" value="<?php echo $lang['mod/submit']; ?>"><br />
</form>
<?php $mitsuba->admin->ui->endSection(); ?>