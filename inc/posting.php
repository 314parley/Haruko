<?php
namespace Mitsuba;

class Posting {
	private $conn;
	private $mitsuba;

	function __construct($connection, $mitsuba) {
		$this->conn = $connection;
		$this->mitsuba = $mitsuba;
	}
	
	function deletePost($board, $postno, $password, $onlyimgdel = 0, $adm_type = -1)
	{
		if (!is_numeric($adm_type))
		{
			$adm_type = -1;
		}
		if (is_numeric($postno))
		{
			$board = $this->conn->real_escape_string($board);
			if (!$this->mitsuba->common->isBoard($board))
			{
				return -16;
			}
			$bdata = $this->mitsuba->common->getBoardData($board);
			$result = $this->conn->query("SELECT * FROM posts WHERE id=".$postno." AND board='".$board."'");
			if ($result->num_rows == 1)
			{
				$config = $this->mitsuba->config;
				$postdata = $result->fetch_assoc();
				if ($adm_type <= 0)
				{
					if (time() <= ($postdata['date'] + $bdata['time_to_delete']))
					{
						return -4;
					}
					if (md5($password) != $postdata['password'])
					{
						return -1;
					}
				}
				if ($onlyimgdel == 1)
				{
					if ((!empty($postdata['filename'])) && ($postdata['filename'] != "deleted"))
					{
							
						$filename = $postdata['filename'];
						if (substr($filename, 0, 8) == "spoiler:")
						{
							$filename = substr($filename, 8);
						}
						if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted"))
						{
							unlink("./".$board."/src/".$filename);
							unlink("./".$board."/src/thumb/".$filename);
						}
						$this->conn->query("UPDATE posts SET filename='deleted', mimetype='', filehash='' WHERE id=".$postno." AND board='".$board."';");
						if ($postdata['resto'] != 0)
						{
							$this->mitsuba->caching->generateView($board, $postdata['resto']);
							if ($config['caching_mode']==1)
							{
								$this->mitsuba->caching->forceGetThread($board, $postdata['resto']);
							}
							$this->mitsuba->caching->generateView($board);

						} else {
							$this->mitsuba->caching->generateView($board, $postno);
							if ($config['caching_mode']==1)
							{
								$this->mitsuba->caching->forceGetThread($board, $postno);
							}
							$this->mitsuba->caching->generateView($board);
						}
						if ($bdata['catalog']==1)
						{
							$this->mitsuba->caching->generateCatalog($board);
						}
						return 1; //done-image
					} else {
						return -3;
					}
				} else {
					if ($postdata['resto'] == 0) //we'll have to delete whole thread
					{
						$files = $this->conn->query("SELECT * FROM posts WHERE filename != '' AND resto=".$postdata['id']." AND board='".$board."'");
						while ($file = $files->fetch_assoc())
						{
							$filename = $file['filename'];
							if (substr($filename, 0, 8) == "spoiler:")
							{
								$filename = substr($filename, 8);
							}
							if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted"))
							{
								unlink("./".$board."/src/".$filename);
								unlink("./".$board."/src/thumb/".$filename);
							}
						}
						if ((!empty($postdata['filename'])) && ($postdata['filename'] != "deleted"))
						{
							$filename = $postdata['filename'];
							if (substr($filename, 0, 8) == "spoiler:")
							{
								$filename = substr($filename, 8);
							}
							if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted"))
							{
								unlink("./".$board."/src/".$filename);
								unlink("./".$board."/src/thumb/".$filename);
							}
						}
						$this->conn->query("DELETE FROM posts WHERE resto=".$postno." AND board='".$board."';");
						$this->conn->query("DELETE FROM posts WHERE id=".$postno." AND board='".$board."';");
						if ($bdata['hidden'] == 0)
						{
							if (file_exists("./".$board."/res/".$postno.".json"))
							{
								unlink("./".$board."/res/".$postno.".json");
							}
							if (file_exists("./".$board."/res/".$postno."_index.html"))
							{
								unlink("./".$board."/res/".$postno."_index.html");
							}
							unlink("./".$board."/res/".$postno.".html");
						}
						//$this->mitsuba->caching->generateView($board, $postno);
						$this->mitsuba->caching->generateView($board);
						return 2; //done post
					} else {
						if ((!empty($postdata['filename'])) && ($postdata['filename'] != "deleted"))
						{
							
							$filename = $postdata['filename'];
							if (substr($filename, 0, 8) == "spoiler:")
							{
								$filename = substr($filename, 8);
							}
							if ((substr($filename, 0, 6) != "embed:") && ($filename != "deleted"))
							{
								unlink("./".$board."/src/".$filename);
								unlink("./".$board."/src/thumb/".$filename);
							}
						}
						$this->conn->query("DELETE FROM posts WHERE id=".$postno." AND board='".$board."';");
						$this->mitsuba->caching->generateView($board, $postdata['resto']);
						if ($config['caching_mode']==1)
						{
							$this->mitsuba->caching->forceGetThread($board, $postdata['resto']);
						}
						$this->mitsuba->caching->generateView($board);
						return 2;
					}
				}
					
			} else {
				return -2;
			}
		} else {
			return -2;
		}
	}

	function addPost($board, $name, $email, $subject, $comment, $password, $filename, $orig_filename, $mimetype = "", $resto = null, $md5 = "", $t_w = 0, $t_h = 0, $spoiler = 0, $embed = 0, $adm_type = -1, $capcode = 0, $raw = 0, $sticky = 0, $locked = 0, $nolimit = 0, $nofile = 0, $fake_id = "", $cc_text = "", $cc_color = "", $redirect = 0)
	{
		global $lang;
		$config = $this->mitsuba->config;
		if (!$this->mitsuba->common->isBoard($board))
		{
			return -16;
		}
		if (!is_numeric($resto))
		{
			$resto = 0;
		}
		
		if (!is_numeric($adm_type))
		{
			$adm_type = -1;
		}
		
		if (!is_numeric($t_w))
		{
			$t_w = 0;
		}
		if (!is_numeric($t_h))
		{
			$t_h = 0;
		}
		
		if ((!is_numeric($raw)) || ($adm_type <= 0))
		{
			$raw = 0;
		}
		if ((!is_numeric($capcode)) || ($adm_type <= 0))
		{
			$capcode = 0;
		}
		if ((!is_numeric($sticky)) || ($adm_type <= 0))
		{
			$sticky = 0;
		}
		if ((!is_numeric($locked)) || ($adm_type <= 0))
		{
			$locked = 0;
		}
		
		if ($resto != 0)
		{
			$sticky = 0;
			$locked = 0;
		}
		
		if (($resto == 0) && (empty($filename)) && ($nofile == 0))
		{
			echo "<center><h1>".$lang['img/no_file']."</h1><br /><a href='./".$board."'>".$lang['img/return']."</a></center>";
			return;
		}
		
		if ((empty($filename)) && (empty($comment)))
		{
			echo "<center><h1>".$lang['img/no_file']."</h1><br /><a href='./".$board."'>".$lang['img/return']."</a></center>";
			return;
		}
		
		$bdata = $this->mitsuba->common->getBoardData($board);
		$fname2 = $filename;
		if ((!empty($filename)) && ($spoiler == 1) && ($bdata['spoilers'] == 1))
		{
			$filename = "spoiler:".$filename;
		}
		$embed_img = 0;
		if ((!empty($filename)) && ($embed == 1) && ($bdata['embeds'] == 1))
		{
			$fname2 = "embed";
			$embed_img = 1;
		}
		$thread = "";
		$tinfo = "";
		$replies = 0;
		if ($resto != 0)
		{
			$thread = $this->conn->query("SELECT * FROM posts WHERE id=".$resto." AND board='".$board."'");
			
			if ($bdata['bumplimit'] > 0)
			{
				$replies = $this->conn->query("SELECT * FROM posts WHERE resto=".$resto." AND board='".$board."'");
				$replies = $replies->num_rows;
			}
			
			if ($thread->num_rows == 0)
			{
				echo "<center><h1>".$lang['img/cant_reply']."</h1><br /><a href='./".$board."'>".$lang['img/return']."</a></center>";
				return;
			}
			
			$tinfo = $thread->fetch_assoc();
			if (($tinfo['locked'] == 1) && ($adm_type <= 0))
			{
				echo "<center><h1>".$lang['img/thread_locked']."</h1><br /><a href='./".$board."'>".$lang['img/return']."</a></center>";
				return;
			}
			
		}
		$lastbumped = time();
		$trip = "";
		$strip = "";
		if (($bdata['noname'] == 0) || ($adm_type >= 1))
		{
			$arr = $this->mitsuba->common->processName($name);
			$trip = $arr['trip'];
			$name = $arr['name'];
			$strip = $arr['strip'];
		} else {
			$name = "Anonymous";
			/*if (($email != "nonoko") || ($email != "nonokosage") || ($email != "noko") || ($email != "nokosage") || ($email != "sage"))
			{
				$email = "";
			}*/
		}
		$old_email = $email;
		if (($bdata['noname'] == 1) && (!empty($email)) && ($adm_type <= 0))
		{
			if (($email == "noko") || ($email == "nonoko"))
			{
				$email = "";
			} elseif (($email == "nokosage") || ($email == "nonokosage") || ($email == "sage"))
			{
				$email = "sage";
			} else {
				$email = "";
			}
		}
		
		$mimetype = $this->conn->real_escape_string($mimetype);
		$md5 = $this->conn->real_escape_string($md5);
		$poster_id = "";
		if (!empty($fake_id))
		{
			$poster_id = $fake_id;
		} else {
			if ($bdata['ids'] == 1)
			{
				if ($resto != 0)
				{
					$poster_id = $this->mitsuba->common->mkid($_SERVER['REMOTE_ADDR'], $resto, $board);
				}
				
			}
		}
		$isize = "";
		$osize = 0;
		$fsize = "";
		if ((!empty($fname2)) && ($fname2 != "embed"))
		{
			if (substr($filename, 0, 8) == "spoiler:")
			{
				$d = getimagesize("./".$board."/src/".substr($filename, 8));
				$isize = $d[0]."x".$d[1];
				$osize = filesize("./".$board."/src/".substr($filename, 8));
				$fsize = $this->mitsuba->common->human_filesize($osize);
			} else {
				$d = getimagesize("./".$board."/src/".$filename);
				$isize = $d[0]."x".$d[1];
				$osize = filesize("./".$board."/src/".$filename);
				$fsize = $this->mitsuba->common->human_filesize($osize);
			}
		}
		if ((empty($cc_text)) || (empty($cc_color)))
		{
			$cc_text = "";
			$cc_color = "";
		} else {
			$cc_text = $this->conn->real_escape_string(htmlspecialchars($cc_text));
			$cc_color = $this->conn->real_escape_string(htmlspecialchars($cc_color));
		}
		$this->conn->query("INSERT INTO posts (board, date, name, trip, strip, poster_id, email, subject, comment, password, orig_filename, filename, resto, ip, lastbumped, filehash, orig_filesize, filesize, imagesize, mimetype, t_w, t_h, sticky, sage, locked, capcode, raw, cc_text, cc_color)".
		"VALUES ('".$board."', ".time().", '".$name."', '".$trip."', '".$strip."', '".$this->conn->real_escape_string($poster_id)."', '".$this->mitsuba->common->processString($email)."', '".$this->mitsuba->common->processString($subject)."', '".$this->mitsuba->common->preprocessComment($comment)."', '".md5($password)."', '".$this->mitsuba->common->processString($orig_filename)."', '".$filename."', ".$resto.", '".$_SERVER['REMOTE_ADDR']."', ".$lastbumped.", '".$md5."', ".$osize.", '".$fsize."', '".$isize."', '".$mimetype."', ".$t_w.", ".$t_h.", ".$sticky.", 0, ".$locked.", ".$capcode.", ".$raw.", '".$cc_text."', '".$cc_color."')");
		$id = mysqli_insert_id($this->conn);
		if (empty($fake_id))
		{
			$poster_id = "";
			if ($bdata['ids'] == 1)
			{
				if ($resto == 0)
				{
					$poster_id = $this->mitsuba->common->mkid($_SERVER['REMOTE_ADDR'], $id, $board);
				}
				
			}
			if ($poster_id != "")
			{
				$this->conn->query("UPDATE posts SET poster_id='".$this->conn->real_escape_string($poster_id)."' WHERE id=".$id." AND board='".$board."'");
			}
		}
		if ($resto != 0)
		{
			if (($email == "sage") || ($tinfo['sage'] == 1) || ($replies > $bdata['bumplimit']))
			{
			
			} else {
				$this->conn->query("UPDATE posts SET lastbumped=".time()." WHERE id=".$resto." AND board='".$board."'");
			}
		}
		$email = $old_email;
		
		if ($redirect == 1)
		{
			if (($email == "nonoko") || ($email == "nonokosage"))
			{
				echo '<meta http-equiv="refresh" content="2;URL='."'./mod.php?/board&b=".$board."'".'">';
				
			} else {
				if ($resto != 0)
				{
					echo '<meta http-equiv="refresh" content="2;URL='."'./mod.php?/board&b=".$board."&t=".$resto."#p".$id."".'">';
				} else {
					echo '<meta http-equiv="refresh" content="2;URL='."'./mod.php?/board&b=".$board."&t=".$id."'".'">';
					
				}
			}
		} else {
			if (($email == "nonoko") || ($email == "nonokosage"))
			{
				echo '<meta http-equiv="refresh" content="2;URL='."'./".$board."/index.html'".'">';
				
			} else {
				if ($resto != 0)
				{
					echo '<meta http-equiv="refresh" content="2;URL='."'./".$board."/res/".$resto.".html#p".$id."".'">';
				} else {
					echo '<meta http-equiv="refresh" content="2;URL='."'./".$board."/res/".$id.".html'".'">';
					
				}
			}
		}
		if ($resto == 0)
		{
			$this->mitsuba->common->pruneOld($board);
		}
		
		if ($resto == 0)
		{
			$this->mitsuba->caching->generateView($board, $id);
			if ($config['caching_mode']==1)
			{
				$this->mitsuba->caching->forceGetThread($board, $id);
			}
			if ($config['enable_api']==1)
			{
				$this->mitsuba->caching->serializeThread($board, $id);
			}
		} else {
			$this->mitsuba->caching->generateView($board, $resto);
			if ($config['caching_mode']==1)
			{
				$this->mitsuba->caching->forceGetThread($board, $resto);
			}
			if ($config['enable_api']==1)
			{
				$this->mitsuba->caching->serializeThread($board, $resto);
			}
		}
		if ($bdata['catalog']==1)
		{
			$this->mitsuba->caching->generateCatalog($board);
		}
		$this->mitsuba->caching->generateView($board);
		
		if ($config['frontpage_style'] == 1)
		{
			$this->mitsuba->caching->generateFrontpage();
		}
	}

	function reportPost($board, $id, $reason)
	{
		if (is_numeric($id))
		{
			$board = $this->conn->real_escape_string($board);
			if (!$this->mitsuba->common->isBoard($board))
			{
				return -16;
			}
			$result = $this->conn->query("SELECT * FROM posts WHERE id=".$id." AND board='".$board."'");
			if ($result->num_rows == 1)
			{
				$postdata = $result->fetch_assoc();
				$result = $this->conn->query("SELECT * FROM reports WHERE reported_post=".$id." AND board='".$board."'");
				if ($result->num_rows == 0)
				{
					$reason = $this->conn->real_escape_string(htmlspecialchars($reason));
					$this->conn->query("INSERT INTO reports (reporter_ip, reported_post, reason, created, board) VALUES ('".$_SERVER['REMOTE_ADDR']."', ".$id.", '".$reason."', ".time().", '".$board."')");
				} else {
					return 1;
				}
			} else {
				return -15;
			}
		} else {
			return -15;
		}
	}
}
?>