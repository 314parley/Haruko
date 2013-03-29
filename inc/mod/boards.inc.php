<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
reqPermission(2);
?>
<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/create_new_board']; ?></h2></div>
<div class="boxcontent">
<form action="?/boards/add" method="POST">
<?php echo $lang['mod/board_directory']; ?>: <input type="text" name="short" maxlength=10 /><br />
<?php echo $lang['mod/board_name']; ?>: <input type="text" name="name" maxlength=40 /><br />
<?php echo $lang['mod/board_short']; ?>: <input type="text" name="des" maxlength=100 /><br />
<?php echo $lang['mod/board_msg']; ?>: <br /><textarea cols=70 rows=7 name="msg"></textarea><br />
<?php echo $lang['mod/board_limit']; ?>: <input type="text" name="limit" maxlength=9 value="0" /><br />
<?php echo $lang['mod/board_pages']; ?>: <input type="text" name="pages" maxlength=9 value="15" /><br />
<?php echo $lang['mod/board_time_between_posts']; ?>: <input type="text" name="time_between_posts" maxlength=9 value="20" /><br />
<?php echo $lang['mod/board_time_to_delete']; ?>: <input type="text" name="time_to_delete" maxlength=9 value="120" /><br />
<?php echo $lang['mod/board_filesize']; ?>: <input type="text" name="filesize" maxlength=9 value="2097152" /><br />
<?php echo $lang['mod/board_options']; ?>: <input type="checkbox" name="spoilers" value="1" /><?php echo $lang['mod/board_spoilers']; ?> <input type="checkbox" name="noname" value="1" /><?php echo $lang['mod/board_no_name']; ?> <input type="checkbox" name="ids" value="1" /><?php echo $lang['mod/board_ids']; ?><br />
<input type="checkbox" name="embeds" value="1" /><?php echo $lang['mod/board_embeds']; ?> <input type="checkbox" name="bbcode" value="1" checked/><?php echo $lang['mod/board_bbcode']; ?><br />
<input type="submit" value="<?php echo $lang['mod/submit']; ?>" />
</form>
</div>
</div>
</div>
<br />
<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/manage_boards']; ?></h2></div>
<div class="boxcontent">
<?php echo $lang['mod/all_boards']; ?>: <br />
<table>
<thead>
<tr>
<td><?php echo $lang['mod/directory']; ?></td>
<td><?php echo $lang['mod/name']; ?></td>
<td><?php echo $lang['mod/description']; ?></td>
<td><?php echo $lang['mod/bump_limit']; ?></td>
<td><?php echo $lang['mod/message']; ?></td>
<td><?php echo $lang['mod/special']; ?></td>
<td><?php echo $lang['mod/edit']; ?></td>
<td><?php echo $lang['mod/delete']; ?></td>
<td><?php echo $lang['mod/rebuild_cache']; ?></td>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM boards;");
while ($row = $result->fetch_assoc())
{
echo '<tr>';
echo "<td><a href='./".$row['short']."/'>/".$row['short']."/</a></td>";
echo "<td>".$row['name']."</td>";
echo "<td>".$row['des']."</td>";
echo "<td>".$row['bumplimit']."</td>";
if (!empty($row['message']))
{
echo "<td>".$lang['mod/yes']."</td>";
} else {
echo "<td>".$lang['mod/no']."</td>";
}
echo "<td>";
if ($row['spoilers']==1) { echo "<b>".$lang['mod/spoilers']."</b><br />"; }
if ($row['noname']==1) { echo "<b>".$lang['mod/noname']."</b><br />"; }
if ($row['ids']==1) { echo "<b>".$lang['mod/ids']."</b><br />"; }
if ($row['embeds']==1) { echo "<b>".$lang['mod/embeds']."</b><br />"; }
if ($row['bbcode']==1) { echo "<b>".$lang['mod/board_bbcode']."</b><br />"; }
echo "</td>";
echo "<td><a href='?/boards/edit&board=".$row['short']."'>".$lang['mod/edit']."</a></td>";
echo "<td><a href='?/boards/delete&board=".$row['short']."'>".$lang['mod/delete']."</a></td>";
echo "<td><a href='?/boards/rebuild&board=".$row['short']."'>".$lang['mod/rebuild_cache']."</a></td>";
echo '</tr>';
}
?>
</tbody>
</table>
</div>
</div>
</div>