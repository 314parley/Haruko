<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
if ((!empty($_GET['b'])) && (!empty($_GET['t'])) && ($mitsuba->common->isBoard($conn, $_GET['b'])) && (is_numeric($_GET['t'])))
		{
			$mitsuba->admin->canBoard($_GET['b']);
			$result = $conn->query("SELECT * FROM posts WHERE id=".$_GET['t']." AND resto=0 AND board='".$_GET['b']."'");
			if ($result->num_rows == 1)
			{
				$pdata = $result->fetch_assoc();
				if ($pdata['sage'] == 1)
				{
					$conn->query("UPDATE posts SET sage=0 WHERE id=".$_GET['t']." AND board='".$_GET['b']."'");
					$mitsuba->caching->generatePost($_GET['b'], $_GET['t']);
				?>
	
								<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/ab_off']; ?></h2></div>
<meta http-equiv="refresh" content="1;URL='?/board&b=<?php echo $_GET['b']."&t=".$_GET['t']; ?>'" />
</div>
</div>
		<?php
				} else {
					$conn->query("UPDATE posts SET sage=1 WHERE id=".$_GET['t']." AND board='".$_GET['b']."'");
					$mitsuba->caching->generatePost($_GET['b'], $_GET['t']);
				?>
	
								<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/ab_on']; ?></h2></div>
<meta http-equiv="refresh" content="1;URL='?/board&b=<?php echo $_GET['b']."&t=".$_GET['t']; ?>'" />
</div>
</div>
		<?php
				}
			} else {
			?>
	
								<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/thread_not_found']; ?></h2></div>
</div>
</div>
		<?php
			}
		} else {
		
		}
?>