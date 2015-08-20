<table>
	<thead>
		<tr>
			<h4>Boards</h4>
		</tr>
<?php
		ini_set('display_errors', 'On');
error_reporting(E_ALL);
include ("config.php");
include ("inc/mitsuba.php");
$conn = new mysqli($db_host, $db_username, $db_password, $db_database);
$mitsuba = new Mitsuba($conn);
$cats = $conn->query("SELECT * FROM links WHERE parent=-1 ORDER BY short ASC;");
while ($row = $cats->fetch_assoc())
                        echo '<th data-field="section">'.$row['title'].'</th>';
?>
	</thead>
</table>