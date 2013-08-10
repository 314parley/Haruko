<?php
if (!defined("IN_MOD"))
{
	die("Nah, I won't serve that file to you.");
}
$mitsuba->admin->reqPermission(3);
		if ((!empty($_GET['id'])) && (is_numeric($_GET['id'])))
		{
			$mitsuba->admin->ui->checkToken($_POST['token']);
			$id = $_GET['id'];
			if ($username = $mitsuba->admin->users->isUser($id))
			{
				if ((!empty($_POST['username'])) && (is_numeric($_POST['type'])))
				{
					$type = $_POST['type'];
					if (empty($type)) { $type = 0; }
					$boards = "";
					if (((!empty($_POST['all'])) && ($_POST['all']==1)) || ($type == 2))
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
					if ($username != $_POST['username'])
					{
						$mitsuba->admin->logAction(sprintf($lang['log/changed_username'], $username, $_POST['username']));
					}
					$mitsuba->admin->logAction(sprintf($lang['log/edited_user'], $username));
					if ($boards != "*") { $boards = substr($boards, 0, strlen($boards) - 1); }
					$mitsuba->admin->users->updateUser($id, $_POST['username'], $_POST['password'], $_POST['type'], $boards);
					?>
<?php $mitsuba->admin->ui->startSection($lang['mod/user_updated']); ?>

<a href="?/users"><?php echo $lang['mod/back']; ?></a>
<?php $mitsuba->admin->ui->endSection(); ?>
					<?php
				} else {
					$result = $conn->query("SELECT * FROM users WHERE id=".$_GET['id']);
					$data = $result->fetch_assoc();
					$boards = $data['boards'];
					if ($data['boards'] != "*") { $board = explode(",", $data['boards']); }
		?>
<?php $mitsuba->admin->ui->startSection($lang['mod/edit_user']); ?>

<form action="?/users/edit&id=<?php echo $id; ?>" method="POST">
<?php $mitsuba->admin->ui->getToken(); ?>
<?php echo $lang['mod/username']; ?>: <input type="text" name="username" value="<?php echo $data['username']; ?>"/><br />
<?php echo $lang['mod/password_leave_blank']; ?>: <input type="password" name="password"/><br />
<?php
$disabled = "";
$janitor = "";
$moderator = "";
$administrator = "";

switch ($data['type'])
{
	case 0:
		$disabled = " selected ";
		break;
	case 1:
		$janitor = " selected ";
		break;
	case 2:
		$moderator = " selected ";
		break;
	case 3:
		$administrator = " selected ";
		break;
}
?>
<?php echo $lang['mod/type']; ?>: <select name="type"><option value="0"<?php echo $disabled; ?>><?php echo $lang['mod/disabled']; ?></option><option value="1"<?php echo $janitor; ?>><?php echo $lang['mod/janitor']; ?></option><option value="2"<?php echo $moderator; ?>><?php echo $lang['mod/moderator']; ?></option><option value="3"<?php echo $administrator; ?>><?php echo $lang['mod/administrator']; ?></option></select>

<br /><br />
<?php
$mitsuba->admin->ui->getBoardList($boards);
?>
<br />
<input type="submit" value="<?php echo $lang['mod/submit']; ?>" />
</form>
<?php $mitsuba->admin->ui->endSection(); ?><br />
<?php
				}
			}
		}
?>