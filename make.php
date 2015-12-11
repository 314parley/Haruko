
<!doctype html>
<html lang="en">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
			<meta charset="UTF-8">
			<title>314chan</title>
            <link href="css/MIcons.css" rel="stylesheet">
            <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
            <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>
            <style>
	            h1{
		            text-align: center;
	            }
            </style>
        </head>
		<body>
			<div class="navbar-fixed">
				<nav class="marooncolor" role="navigation">
					<div class="nav-wrapper container"><a id="logo-container" href="/" class="brand-logo">314chan</a>
						<ul class="right hide-on-med-and-down">
							<li><a href="rules.html">Rules</a></li>
							<li><a href="faq.html">FAQ</a></li>
							<li><a href="news.html">News</a></li>
							<li><a href="https://irc.314chan.org">IRC</a></li>
						</ul>
						<ul id="nav-mobile" class="side-nav">
							<li><a href="rules.html">Rules</a></li>
							<li><a href="faq.html">FAQ</a></li>
							<li><a href="news.html">News</a></li>
							<li><a href="https://irc.314chan.org">IRC</a></li>
						</ul>
						<a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>
					</div>
				</nav>
			</div>
			
          <div class="section no-pad-bot" id="index-banner">
	          <div class="container">
		          <br><br>
		          <div class="row">
			          <?php
				          
				          include("config.php");
				          include("inc/mitsuba.php");
				          $conn = new mysqli($db_host, $db_username, $db_password, $db_database);
				          $haruko = new Mitsuba($conn);
				          if ($_POST) {
					          if($haruko->admin->boards->addBoard($_POST['uri'], "imageboard", $_POST['bname'], $_POST['desc'], $_POST['desc'], 0, 1, 0, 0, 1, 1, 20, 60, 120, 2097152, 15, 0, 0, 1, 0, 2000, "Anonymous", "%", 1, 0, "", 1, 1, "", 0) > 0){
								if ((!empty($_POST['uri'])) && ($haruko->common->isBoard($_POST['uri']))){
									$haruko->caching->rebuildBoardCache($_POST['uri']);
									echo "<h1>/".$_POST['uri']."/ Rebuilt!</h1>";
								}else{
									echo "<h1>Rebuilding apparently didn't work...</h1>";
								}
					          echo "<h1>Board Created!</h1>";
					          }else{
						          echo "<h1>YOU BROKE IT ;-; contact parley.</h1><br /><em>(This usually means a MariaDB transaction failed, or the board already exists.)</em>";
					          }
				          }else{
			          ?>
				    <form class="col s12" method="POST">
				      <div class="row">
				        <div class="input-field col s12">
					      <label for="uri">Board Directory <small>(excluding leading/following slash) <strong>(max: 10)</strong></small></label>
				          <input  placeholder="b" id="uri" name="uri" type="text" maxlength=10 class="validate" required autofocus autocomplete="off">
				        </div>
				      </div>
				      <div class="row">
				        <div class="input-field col s12">
				          <label for="bname">Board name<small> (Text next to board directory on board pages) <strong>(max: 100)</strong></small></label>
				          <input  placeholder="Fortuitous Folly" id="bname" name="bname"type="text" maxlength=100 class="validate" required autocomplete="off">
				        </div>
				      </div>
				      <div class="row">
				        <div class="input-field col s12">
				          <label for="desc">Board Description <small><strong>(max: 100)</strong></small></label>
				          <textarea placeholder="The posts created here are lies, and are terms of idiotic chucklefuckery." id="desc" name="desc" maxlength=100 class="materialize-textarea" required></textarea>
				        </div>
				      </div>
				      <div class="row">
				        <div class="input-field col s6">
				          <label for="uname">Username <small></small></label>
				          <input placeholder="StaffyMcGee" id="uname" name="uname"type="text" maxlength=10 class="validate" autocomplete="off">
				        </div>
				        <div class="input-field col s6">
				          <label for="last_name">Password <small>(Please pick a secure password!)</small></label>
				          <input placeholder="&bull;&bull;&bull;&bull;&bull;&bull;&bull;&bull;" id="pass" name="pass" type="password" class="validate" autocomplete="off">
				        </div>
				      </div>
				      <div class="row">
				        <div class="input-field col s12">
				          <label for="first_name">Contact email <small>(For board recovery; optional.)</small></label>
				          <input  placeholder="admin@314chan.org" id="email" name="email" type="email" class="validate" autocomplete="off">
				        </div>
				      </div>
					<button class="btn waves-effect waves-light" type="submit">Submit<i class="material-icons right">send</i></button>
				    </form>
				    <?php
					    }
				    ?>
				  </div>
		      </div>
		  </div>
          <!--  Scripts-->

          <script src="js/jquery.js"></script>
          <script src="js/materialize.js"></script>
          <script src="js/init.js"></script>
		</body>
</html>