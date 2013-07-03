<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
?>
<?php $mitsuba->admin->ui->startSection($lang['mod/announcements']); ?>

<?php
$result = $conn->query("SELECT * FROM announcements ORDER BY date DESC;");
while ($row = $result->fetch_assoc())
{
echo '<div class="content">';
echo '<h3><span class="newssub">'.$row['title'].' by '.$row['who'].' - '.date("d/m/Y @ H:i", $row['date']).'</span></span></h3>';
echo $row['text'];
echo '</div>';
}
?>
<?php $mitsuba->admin->ui->endSection(); ?>