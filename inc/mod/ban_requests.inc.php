<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(2);
		if ((isset($_GET['del'])) && ($_GET['del']==1))
		{
			if ((!empty($_GET['b'])) && (is_numeric($_GET['b'])))
			{
				$conn->query("DELETE FROM ban_requests WHERE id=".$_GET['b']);
			}
		}
	?>
<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/ban_requests']; ?></h2></div>
<div class="boxcontent">
<table>
<thead>
<tr>
<td><?php echo $lang['mod/ip']; ?></td>
<td><?php echo $lang['mod/reason']; ?></td>
<td><?php echo $lang['mod/staff_note']; ?></td>
<td><?php echo $lang['mod/created']; ?></td>
<td><?php echo $lang['mod/actions']; ?></td>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM ban_requests ORDER BY created DESC LIMIT 0, 15;");
while ($row = $result->fetch_assoc())
{
echo "<tr>";
echo "<td><center>".$row['ip']."</center></td>";
echo "<td>".$row['reason']."</td>";
echo "<td>".$row['note']."</td>";
echo "<td><center>".date("d/m/Y @ H:i", $row['created'])."</center></td>";

$post_r = $conn->query("SELECT * FROM posts WHERE id=".$row['post']." AND board='".$row['board']."'");
if ($post_r->num_rows == 1)
{
$post = $post_r->fetch_assoc();
$resto = $post['resto'];
if ($resto == 0) { $resto = $post['id']; }
echo "<td><center>[ <a href='?/ban_requests&del=1&b=".$row['id']."'>C</a> / <a href='?/bans/add&r=".$row['id']."'>B</a> / <a href='?/board&b=".$row['board']."&t=".$resto."#p".$post['id']."'>P</a> ]</center></td>";
} else {
echo "<td><center>[ <a href='?/ban_requests&del=1&b=".$row['id']."'>C</a> / <a href='?/bans/add&r=".$row['id']."'>B</a> ]</center></td>";
}

echo "</tr>";
}
?>
</tbody>
</table>
<?php printf($lang['mod/showing_requests'], 15); ?> <a href="?/ban_requests/all"><?php echo $lang['mod/show_all']; ?></a>
</div>
</div>
</div>
<script type="text/javascript">parent.nav.location.reload();</script>