<?php

if (!defined("IN_MOD"))

{

	die("Nah, I won't serve that file to you.");

}

$mitsuba->admin->reqPermission("news.view");

?>

<?php $mitsuba->admin->ui->startSection($lang['mod/news']); ?>
<?php if($mitsuba->admin->canBoard("%")){?>
[<a href="?/news/add">Add News Entry</a> / <a href="?/news/manage">Manage News Entries</a>]
<?php }?>

<?php

$result = $conn->query("SELECT * FROM news ORDER BY date DESC;");

while ($row = $result->fetch_assoc())

{
echo '<div class="content">';

echo '<h3><span class="newssub">'.$row['title'].' by '.$row['who'].' - '.date("d/m/Y @ H:i", $row['date']).'&nbsp;[<a href="?/news/edit&b='.$row['id'].'">Edit</a>]</span></span></h3>';

echo $row['text'];

echo '</div>';

}

?>

<?php $mitsuba->admin->ui->endSection(); ?>