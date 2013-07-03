<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(3);
		$search = "";
		$replace = "";
		if ((!empty($_POST['mode'])) && ($_POST['mode'] == "add"))
		{
			$continue = 0;
			if (empty($_POST['search'])) { echo "<b style='color: red;'>".$lang['mod/fill_all_fields']."</b>"; } else { $search = $_POST['search']; $continue = 1; }
			if (empty($_POST['replace'])) { echo "<b style='color: red;'>".$lang['mod/fill_all_fields']."</b>"; } else { $replace = $_POST['replace']; $continue = 1; }
			if ($continue == 1)
			{
				$search = $conn->real_escape_string($_POST['search']);
				$replace = $conn->real_escape_string($_POST['replace']);
				$boards = "";
				if ((!empty($_POST['all'])) && ($_POST['all']==1))
				{
					$boards = "*";
				} else {
					if (!empty($_POST['boards']))
					{
						foreach ($_POST['boards'] as $board)
						{
							$boards .= $board.",";
						}
					} else {
						$board = "*";
					}
				}
				if ($boards != "*") { $boards = substr($boards, 0, strlen($boards) - 1); }
				$conn->query("INSERT INTO wordfilter (`search`, `replace`, `boards`, `active`, `regex`) VALUES ('".$search."', '".$replace."', '".$boards."', 1, 0);");
			}
			$search = "";
			$replace = "";
		} elseif ((!empty($_POST['mode'])) && ($_POST['mode'] == "edit") && (!empty($_POST['id']))) {
			$continue = 0;
			if (empty($_POST['search'])) { echo "<b style='color: red;'>".$lang['mod/fill_all_fields']."</b>"; } else { $search = $_POST['search']; $continue = 1; }
			if (empty($_POST['replace'])) { echo "<b style='color: red;'>".$lang['mod/fill_all_fields']."</b>"; } else { $replace = $_POST['replace']; $continue = 1; }
			if ($continue == 1)
			{
				$search = $conn->real_escape_string($_POST['search']);
				$id = $_POST['id'];
				if (!is_numeric($id)) { echo "<b style='color: red;'>".$lang['mod/fool']."</b>"; }
				$replace = $conn->real_escape_string($_POST['replace']);
				$boards = "";
				if ((!empty($_POST['all'])) && ($_POST['all']==1))
				{
					$boards = "*";
				} else {
					if (!empty($_POST['boards']))
					{
						foreach ($_POST['boards'] as $board)
						{
							$boards .= $board.",";
						}
					} else {
						$board = "*";
					}
				}
				if ($boards != "*") { $boards = substr($boards, 0, strlen($boards) - 1); }
				$conn->query("UPDATE wordfilter SET `search`='".$search."', `replace`='".$replace."', `boards`='".$boards."' WHERE id=".$id);
			}
			$search = "";
			$replace = "";
		}

		if ((!empty($_GET['d'])) && ($_GET['d'] == 1) && (!empty($_GET['n'])))
		{
			$n = $conn->real_escape_string($_GET['n']);
			if (!is_numeric($n)) { echo "<b style='color: red;'>".$lang['mod/fool']."</b>"; }
			$conn->query("DELETE FROM wordfilter WHERE id=".$n);
		}
		?>
<b><?php echo $lang['mod/rebuild_notice']; ?></b><br />
<?php $mitsuba->admin->ui->startSection($lang['mod/manage_wordfilter']); ?>

<table>
<thead>
<tr>
<td><?php echo $lang['mod/wf_search']; ?></td>
<td><?php echo $lang['mod/wf_replace']; ?></td>
<td><?php echo $lang['mod/boards']; ?></td>
<td><?php echo $lang['mod/actions']; ?></td>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM wordfilter ORDER BY search ASC");
while ($row = $result->fetch_assoc())
{
echo "<tr>";
echo "<td><center>".htmlspecialchars($row['search'])."</center></td>";
echo "<td><center>".htmlspecialchars($row['replace'])."</center></td>";
echo "<td><center>".$row['boards']."</center></td>";
echo "<td><center><a href='?/wordfilter&d=1&n=".$row['id']."'>".$lang['mod/delete']."</a> <a href='?/wordfilter/edit&n=".$row['id']."'>".$lang['mod/edit']."</a></center></td>";
echo "</tr>";
}
?>
</tbody>
</table>
<?php $mitsuba->admin->ui->endSection(); ?>
<br /><br />
<?php $mitsuba->admin->ui->startSection($lang['mod/wf_add']); ?>

<form action="?/wordfilter" method="POST">
<input type="hidden" name="mode" value="add">
<?php echo $lang['mod/wf_search']; ?>: <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>"/><br />
<?php echo $lang['mod/wf_replace']; ?>: <input type="text" name="replace" value="<?php echo htmlspecialchars($replace); ?>"/><br />
<br /><br />
<?php
$mitsuba->admin->ui->getBoardList();
?>
<input type="submit" value="<?php echo $lang['mod/submit']; ?>" />
</form>
<?php $mitsuba->admin->ui->endSection(); ?>