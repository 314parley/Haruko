<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
reqPermission(1);
	if (empty($_POST['text']))
	{
	?>
	<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/add_news']; ?></h2></div>
<div class="boxcontent">
<form action="?/news/add" method="POST">
<?php echo $lang['mod/by']; ?>: <input type="text" name="who" value="<?php echo $_SESSION['username']; ?>" /><br />
<?php echo $lang['mod/title']; ?>: <input type="text" name="title"/><br />
<?php echo $lang['mod/text']; ?>: <br />
<textarea name="text" cols="70" rows="10"></textarea>
<input type="submit" value="<?php echo $lang['mod/submit']; ?>" />
</form>
</div>
</div>
</div><br />
<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/your_entries']; ?></h2></div>
<div class="boxcontent">
<table>
<thead>
<tr>
<td><?php echo $lang['mod/title']; ?></td>
<td><?php echo $lang['mod/date']; ?></td>
<td><?php echo $lang['mod/edit']; ?></td>
<td><?php echo $lang['mod/delete']; ?></td>
</tr>
</thead>
<tbody>
<?php
$result = $conn->query("SELECT * FROM news WHERE mod_id=".$_SESSION['id']." ORDER BY date DESC;");
while ($row = $result->fetch_assoc())
{
echo "<tr>";
echo "<td>".$row['title']."</td>";
echo "<td>".date("d/m/Y @ H:i", $row['date'])."</td>";
echo "<td><a href='?/news/edit&b=".$row['id']."'>".$lang['mod/edit']."</a></td>";
echo "<td><a href='?/news/delete&b=".$row['id']."'>".$lang['mod/delete']."</a></td>";
echo "</td>";
}
generateNews($conn);
?>
</tbody>
</table>
</div>
</div>
</div>
	<?php
	} else {
		$text = processEntry($conn, $_POST['text']);
		$who = $_SESSION['username'];
		if (!empty($_POST['who'])) { $who = $_POST['who']; }
		$conn->query("INSERT INTO news (date, who, title, text, mod_id) VALUES (".time().", '".$who."', '".$conn->real_escape_string(htmlspecialchars($_POST['title']))."', '".$text."', ".$_SESSION['id'].");");
		generateNews($conn);
		
		?>
		<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/post_added']; ?></h2></div>
<div class="boxcontent"><a href="?/news"><?php echo $lang['mod/back']; ?></a></div>
</div>
</div>
		<?php
	}
?>