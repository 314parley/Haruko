<?php 
	$config = $mitsuba->config;
	$sitename = $config['sitename'];
	
	$reports = $conn->query("SELECT * FROM reports;")->num_rows;
	$appeals = $conn->query("SELECT * FROM appeals;")->num_rows;
	$breqs = $conn->query("SELECT * FROM ban_requests;")->num_rows;
	$ppd = $conn->query("SELECT COUNT(*) FROM posts WHERE date > NOW() - INTERVAL 7 DAY")->num_rows;
	
?>
<?php
if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==1))
		{
	$pms = $conn->query("SELECT * FROM pm WHERE to_user=".$_SESSION['id']." AND read_msg=0")->num_rows;
		?>
<!DOCTYPE html>
<html>
  <head>
	<meta charset="UTF-8">
	<title><? echo($sitename)?> Management</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
	<link href="/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
 </head>
 <body class="skin-blue fixed">
   <!-- Site wrapper -->
   <div class="wrapper">
 	
 	<header class="main-header">
 	  <!-- Logo -->
 	  <a href="/" class="logo">
 		<!-- logo for regular state and mobile devices -->
 		<span class="logo-lg"><b><? echo($sitename)?></b> Panel</span>
 	  </a>
 	  <!-- Header Navbar: style can be found in header.less -->
 	  <nav class="navbar navbar-static-top" role="navigation">
 		<!-- Sidebar toggle button-->
 		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
 		  <span class="sr-only">Toggle navigation</span>
 		  <span class="icon-bar"></span>
 		  <span class="icon-bar"></span>
 		  <span class="icon-bar"></span>
 		</a>
 		<div class="navbar-custom-menu">
 		  <ul class="nav navbar-nav">
 			<!-- PMs: style can be found in dropdown.less-->
 			<li class="dropdown messages-menu">
 			  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 				<i class="fa fa-envelope-o"></i>
 				<? if($pms > 0){?><span class="label label-success"><? echo $pms ?></span><? } ?>
 			  </a>
 			  <ul class="dropdown-menu">
 				<li class="header">You have <? echo $pms ?> messages</li>
 				<li>
 				  <!-- inner menu: contains the actual data -->
 				  <ul class="menu">
 					<li><!-- start message -->
 					  <a href="#">
 						<!--<div class="pull-left">
 						  <img src="/img/adminLTE/user2-160x160.jpg" class="img-circle" alt="User Image"/>
 						</div>-->
 						<h4>
 						  parley
 						  <small><i class="fa fa-clock-o"></i> 5 mins</small>
 						</h4>
 						<p>Sooner or later, this'll show a PM preview!</p>
 					  </a>
 					</li><!-- end message -->
 				  </ul>
 				</li>
 				<li class="footer"><a href="mod.php?/inbox">See All Messages</a></li>
 			  </ul>
 			</li>
 			<!-- Reports: style can be found in dropdown.less -->
 			<li class="dropdown tasks-menu">
 			  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 				<i class="fa fa-flag-o"></i>
 				<? if($reports > 0){?><span class="label label-danger"><? echo $reports ?></span><? } ?>
 			  </a>
 			  <ul class="dropdown-menu">
 				<li class="header">You have <? echo $reports ?> reports</li>
 				<li>
 				  <!-- inner menu: contains the actual data -->
 				  <ul class="menu">
	 				<!-- Task item -->
 					<!--<li>
 					  <a href="#">
 						<h3>
 						  Design some buttons
 						  <small class="pull-right">20%</small>
 						</h3>
 						<div class="progress xs">
 						  <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
 							<span class="sr-only">20% Complete</span>
 						  </div>
 						</div>
 					  </a>
 					</li>--><!-- end task item -->
 					<li>
 					<a href="https://flcl.314chan.org/314/res/47.html#p47" target="_blank">
 					<h3><strong>Guy is a nerd</strong> <small class="pull-right">/314/47</small></h3>
 					</a>
 					</li>
 					<li>
 					<a href="https://flcl.314chan.org/314/res/47.html#p47" target="_blank">
 					<h3><strong>Guy is a nerd</strong> <small class="pull-right">/314/47</small></h3>
 					</a>
 					</li>
 					<li>
 					<a href="https://flcl.314chan.org/314/res/47.html#p47" target="_blank">
 					<h3><strong>Guy is a nerd</strong> <small class="pull-right">/314/47</small></h3>
 					</a>
 					</li>
 					<li>
 					<a href="https://flcl.314chan.org/314/res/47.html#p47" target="_blank">
 					<h3><strong>Guy is a nerd</strong> <small class="pull-right">/314/47</small></h3>
 					</a>
 					</li>
 				  </ul>
 				</li>

 				<li class="footer">
 				  <a href="mod.php?/reports">View all reports</a>
 				</li>
 			  </ul>
 			</li>
  			<!-- Notifications: style can be found in dropdown.less -->
 			<li class="dropdown notifications-menu">
 			  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 				<i class="fa fa-gavel"></i>
 				<? if($appeals > 0){?><span class="label label-warning"><? echo $appeals ?></span><? } ?>
 			  </a>
 			  <ul class="dropdown-menu">
 				<li class="header">You have <? echo $appeals ?> ban appeals</li>
 				<li>
 				  <!-- inner menu: contains the actual data -->
 				  <ul class="menu">
 					<li>
 					  <a href="#">
 						<i class="fa fa-users text-aqua"></i> 5 new members joined today
 					  </a>
 					</li>
 				  </ul>
 				</li>
 				<li class="footer"><a href="mod.php?/appeals">View all appeals</a></li>
 			  </ul>
 			</li>

 			<!-- User Account: style can be found in dropdown.less -->
 			<li class="dropdown user user-menu">
 			  <a href="#" class="dropdown-toggle" data-toggle="dropdown">
 				<span class="hidden-xs"><?php echo $_SESSION['username']; ?></span>
 			  </a>
 			  <ul class="dropdown-menu">
 				<!-- User image -->
 				<li class="user-header">
 				  <!--<img src="/img/adminLTE/user2-160x160.jpg" class="img-circle" alt="User Image" />-->
 				  <p>
 					<?php echo $_SESSION['username']; ?>
 					<small><?php echo $_SESSION['group_name'] ?></small>
 					<small>Automatic Logout in <span id="SecondsUntilExpire"></span> seconds for inactivity.</small>
 				  </p>
 				</li>
 				<!-- Menu Footer-->
 				<li class="user-footer">
 				  <div class="pull-left">
 					<a href="#" class="btn btn-default btn-flat">Profile</a>
 				  </div>
 				  <div class="pull-right">
 					<a href="?/logout" class="btn btn-default btn-flat">Sign out</a>
 				  </div>
 				</li>
 			  </ul>
 			</li>
 			<!-- Control Sidebar Toggle Button -->
 			<li>
 			  <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
 			</li>
 		  </ul>
 		</div>
 	  </nav>
 	</header>
 
 	<!-- =============================================== -->
 
 	<!-- Left side column. contains the sidebar -->
<?php require("nav.inc.php") ?> 
 	<!-- =============================================== -->
 
 	<!-- Content Wrapper. Contains page content -->
 	<div class="content-wrapper">
	 	<?
	 	$file = "inc/mod/".str_replace(array("/", "\\", ".."), ".", trim($path, " \t\n\r\0\x0B/\\")).".inc.php";
	 	if (file_exists($file))
	 	{
	 		include($file);
	 	} else {
	 		$modules = $conn->query("SELECT * FROM module_pages WHERE url='/".$conn->real_escape_string(str_replace(array("/", "\\", "/"), ".", trim($path, " \t\n\r\0\x0B/\\")))."'");
	 		while ($module = $modules->fetch_assoc())
	 		{
	 			include("./".$module['namespace']."/".$module['file']);
	 			$pageclass = new $module['class']($conn, $mitsuba);
	 			$pageclass->$module['method']();
	 		}
	 	}
	 	?>
 	  <!-- Main content -->
	 	  <? if($path == "/"){ ?>
	 	   	  <section class="content">
 		<!-- Default box -->
<div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-comments-o"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Posts (24 hr)</span>
                  <span class="info-box-number"><? var_dump($ppd); ?></span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-google-plus"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Likes</span>
                  <span class="info-box-number">LOL</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-green"><i class="ion ion-ios-cart-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">Sales</span>
                  <span class="info-box-number">760</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
              <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>
                <div class="info-box-content">
                  <span class="info-box-text">New Members</span>
                  <span class="info-box-number">2,000</span>
                </div><!-- /.info-box-content -->
              </div><!-- /.info-box -->
            </div><!-- /.col -->
          </div>
 	  </section><!-- /.content -->
 	  <? } ?>
 	</div><!-- /.content-wrapper -->
 	<footer class="main-footer">
 	  <div class="text-center hidden-xs">
 		- Haruko + Mitsuba -
 	  </div>
 	</footer>
 	
 	<!-- Control Sidebar -->      
 	<aside class="control-sidebar control-sidebar-dark">                
 	  <!-- Create the tabs -->
 	  <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
 		<li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
 		
 		<li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
 	  </ul>
 	  <!-- Tab panes -->
 	  <div class="tab-content">
 		<!-- Home tab content -->
 		<div class="tab-pane" id="control-sidebar-home-tab">
 		  <h3 class="control-sidebar-heading">Recent Activity</h3>
 		  <ul class='control-sidebar-menu'>
 			<li>
 			  <a href='javascript::;'>
 				<i class="menu-icon fa fa-birthday-cake bg-red"></i>
 				<div class="menu-info">
 				  <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
 				  <p>Will be 23 on April 24th</p>
 				</div>
 			  </a>
 			</li>
 			<li>
 			  <a href='javascript::;'>
 				<i class="menu-icon fa fa-user bg-yellow"></i>
 				<div class="menu-info">
 				  <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
 				  <p>New phone +1(800)555-1234</p>
 				</div>
 			  </a>
 			</li>
 			<li>
 			  <a href='javascript::;'>
 				<i class="menu-icon fa fa-envelope-o bg-light-blue"></i>
 				<div class="menu-info">
 				  <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
 				  <p>nora@example.com</p>
 				</div>
 			  </a>
 			</li>
 			<li>
 			  <a href='javascript::;'>
 				<i class="menu-icon fa fa-file-code-o bg-green"></i>
 				<div class="menu-info">
 				  <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
 				  <p>Execution time 5 seconds</p>
 				</div>
 			  </a>
 			</li>
 		  </ul><!-- /.control-sidebar-menu -->
 
 		  <h3 class="control-sidebar-heading">Tasks Progress</h3> 
 		  <ul class='control-sidebar-menu'>
 			<li>
 			  <a href='javascript::;'>               
 				<h4 class="control-sidebar-subheading">
 				  Custom Template Design
 				  <span class="label label-danger pull-right">70%</span>
 				</h4>
 				<div class="progress progress-xxs">
 				  <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
 				</div>                                    
 			  </a>
 			</li> 
 			<li>
 			  <a href='javascript::;'>               
 				<h4 class="control-sidebar-subheading">
 				  Update Resume
 				  <span class="label label-success pull-right">95%</span>
 				</h4>
 				<div class="progress progress-xxs">
 				  <div class="progress-bar progress-bar-success" style="width: 95%"></div>
 				</div>                                    
 			  </a>
 			</li> 
 			<li>
 			  <a href='javascript::;'>               
 				<h4 class="control-sidebar-subheading">
 				  Laravel Integration
 				  <span class="label label-waring pull-right">50%</span>
 				</h4>
 				<div class="progress progress-xxs">
 				  <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
 				</div>                                    
 			  </a>
 			</li> 
 			<li>
 			  <a href='javascript::;'>               
 				<h4 class="control-sidebar-subheading">
 				  Back End Framework
 				  <span class="label label-primary pull-right">68%</span>
 				</h4>
 				<div class="progress progress-xxs">
 				  <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
 				</div>                                    
 			  </a>
 			</li>               
 		  </ul><!-- /.control-sidebar-menu -->         
 
 		</div><!-- /.tab-pane -->
 		<!-- Stats tab content -->
 		<div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div><!-- /.tab-pane -->
 		<!-- Settings tab content -->
 		<div class="tab-pane" id="control-sidebar-settings-tab">            
 		  <form method="post">
 			<h3 class="control-sidebar-heading">General Settings</h3>
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Report panel usage
 				<input type="checkbox" class="pull-right" checked />
 			  </label>
 			  <p>
 				Some information about this general settings option
 			  </p>
 			</div><!-- /.form-group -->
 
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Allow mail redirect
 				<input type="checkbox" class="pull-right" checked />
 			  </label>
 			  <p>
 				Other sets of options are available
 			  </p>
 			</div><!-- /.form-group -->
 
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Expose author name in posts
 				<input type="checkbox" class="pull-right" checked />
 			  </label>
 			  <p>
 				Allow the user to show his name in blog posts
 			  </p>
 			</div><!-- /.form-group -->
 
 			<h3 class="control-sidebar-heading">Chat Settings</h3>
 
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Show me as online
 				<input type="checkbox" class="pull-right" checked />
 			  </label>                
 			</div><!-- /.form-group -->
 
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Turn off notifications
 				<input type="checkbox" class="pull-right" />
 			  </label>                
 			</div><!-- /.form-group -->
 
 			<div class="form-group">
 			  <label class="control-sidebar-subheading">
 				Delete chat history
 				<a href="javascript::;" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
 			  </label>                
 			</div><!-- /.form-group -->
 		  </form>
 		</div><!-- /.tab-pane -->
 	  </div>
 	</aside><!-- /.control-sidebar -->
 	<!-- Add the sidebar's background. This div must be placed
 		 immediately after the control sidebar -->
 	<div class='control-sidebar-bg'></div>
   </div><!-- ./wrapper -->
 
   <!-- jQuery 2.1.4 -->
   <script src="/js/jQuery/jQuery-2.1.4.min.js"></script>
   <!-- Bootstrap 3.3.2 JS -->
   <script src="/js/bootstrap.min.js" type="text/javascript"></script>
   <!-- SlimScroll -->
   <script src="/js/slimScroll/jquery.slimscroll.min.js" type="text/javascript"></script>
   <!-- FastClick -->
   <script src='/js/fastclick/fastclick.min.js'></script>
   <!-- AdminLTE App -->
   <script src="/js/app.js" type="text/javascript"></script>
   
   <!-- Demo -->
   <script src="/js/demo.js" type="text/javascript"></script>
 </body>
 <script>
	 var IDLE_TIMEOUT = 99999999999999999999999999999999999999999999999999999999999999999999999999999999; //seconds
	 var _idleSecondsTimer = null;
	 var _idleSecondsCounter = 0;

	document.onclick = function() {
		 _idleSecondsCounter = 0;
	};

	document.onmousemove = function() {
    	_idleSecondsCounter = 0;
    };

	document.onkeypress = function() {
		_idleSecondsCounter = 0;
	};

	_idleSecondsCounter = window.setInterval(CheckIdleTime, 1000);

function CheckIdleTime() {
     _idleSecondsCounter++;
     var oPanel = document.getElementById("SecondsUntilExpire");
     if (oPanel)
         oPanel.innerHTML = (IDLE_TIMEOUT - _idleSecondsCounter) + "";
    if (_idleSecondsCounter >= IDLE_TIMEOUT) {
        window.clearInterval(_idleSecondsCounter);
        alert("Time expired!");
        document.location.href = "/mod.php?/logout";
    }
}
 </script>
		<?php
		} else {
			?>
<?php //$mitsuba->admin->ui->startSection($lang['mod/log_in']); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta charset="UTF-8">
	<title>Mitsuba Management</title>
	<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
	<link href="/css/bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="/css/font-awesome.css" rel="stylesheet" type="text/css" />
	<link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<body class="login-page">
	<div class="login-box">
	  <div class="login-logo">
		<a href="/"><strong><?php echo $sitename ?></strong> Login</a>
	  </div><!-- /.login-logo -->
	  <div class="login-box-body">
		<p class="login-box-msg">Please Login.</p>
		<form action="?/login" method="POST">
		  <div class="form-group has-feedback">
			<input class="form-control" placeholder="<?php echo $lang['mod/username']; ?>" name="username"/>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" class="form-control" placeholder="<?php echo $lang['mod/password']; ?>" name="password"/>
		  </div>
		  <div class="row">
			<div class="col-xs-8">    
			  <div class="checkbox icheck">
			  </div>                        
			</div><!-- /.col -->
			<div class="col-xs-4">
			  <button type="submit" class="btn btn-primary btn-block btn-flat"><?php echo $lang['mod/log_in']; ?></button>
			</div><!-- /.col -->
		  </div>
		</form>	
	  </div><!-- /.login-box-body -->
	</div><!-- /.login-box -->
</div>
</body>
<?php
		}
?>
</html>
<?php //$mitsuba->admin->ui->endSection(); ?>