<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(3);
		if ((!empty($_POST['b'])) && (!empty($_POST['p'])) && ($mitsuba->common->isBoard($_POST['b'])) && (is_numeric($_POST['p'])) && (!empty($_POST['text'])))
		{
			$result = $conn->query("SELECT * FROM posts WHERE id=".$_POST['p']." AND board='".$_POST['b']."'");
			if ($result->num_rows == 1)
			{
				$row = $result->fetch_assoc();
				$raw = 0;
				if ((isset($_POST['raw'])) && ($_POST['raw'] == 1))
				{
					$raw = 1;
				}
				$conn->query("UPDATE posts SET comment='".$mitsuba->common->preprocessComment($_POST['text'])."', raw=".$raw." WHERE id=".$_POST['p']." AND board='".$_POST['b']."'");
				$resto = $row['resto'];
				if ($row['resto'] == 0)
				{
					$mitsuba->caching->generateCatalog($_GET['b']);
					$mitsuba->caching->generateView($_POST['b'], $row['id']);
					if ($config['caching_mode']==1)
					{
						$mitsuba->caching->forceGetThread($_POST['b'], $row['id']);
					}
					if ($config['enable_api']==1)
					{
						$mitsuba->caching->serializeThread($_POST['b'], $row['id']);
					}
					$resto = $row['id'];
				} else {
					$mitsuba->caching->generateView($_POST['b'], $row['resto']);
					if ($config['caching_mode']==1)
					{
						$mitsuba->caching->forceGetThread($_POST['b'], $row['resto']);
					}
					if ($config['enable_api']==1)
					{
						$mitsuba->caching->serializeThread($_POST['b'], $row['resto']);
					}
				}
				$mitsuba->caching->generateView($_POST['b']);
				?>
				<div class="box-outer top-box">
	<div class="box-inner">
	<div class="boxbar"><h2><?php echo $lang['mod/post_updated']; ?></h2></div>
	</div>
	</div>
	</div>
	<meta http-equiv="refresh" content="2;URL='?/board&b=<?php echo $_POST['b']; ?>&t=<?php echo $resto; ?>#p<?php echo $row['id']; ?>'" />
				<?php
			}
		}
?>