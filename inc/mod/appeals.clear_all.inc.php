<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(2);
		?>
	
								<div class="box-outer top-box">
<div class="box-inner">
<div class="boxbar"><h2><?php echo $lang['mod/want_clear_appeals']; ?></h2></div>
<div class="boxcontent"><a href="?/appeals"><?php echo $lang['mod/no_big']; ?></a> <a href="?/appeals&m=clear_all_yes"><?php echo $lang['mod/yes_big']; ?></a></div>
</div>
</div>
		<?php