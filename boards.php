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
		</head>
		
		<body>
			<div class="navbar-fixed">
				<nav class="marooncolor" role="navigation">
					<div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">314chan</a>
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
              <div class="card-panel">
	              This is a list of all boards on 314chan, excluding the "unlisted" ones.<br /><small><em>(do keep note the unlisted boards aren't hidden YET.)</em></small>
	                    <table>
		                    <thead>
			                    <tr>
				                    <th data-field="url">URL</th>
				                    <th data-field="title">Title</th>
				                </tr>
				            </thead>
				            
				            <tbody>
<?php
	$json = file_get_contents("boards.json");
	$jsonIterator = new RecursiveIteratorIterator(
    new RecursiveArrayIterator(json_decode($json, TRUE)),
    RecursiveIteratorIterator::SELF_FIRST);

foreach ($jsonIterator as $key => $val) {
    if(is_array($val)) {
        #echo "$key:\n";
    } else {
	    #if($key == 1){
		#    continue;
	    #}else{}
	    if($key == "uri"){
		    echo "<tr><td><a href='/".$val."/'>/".$val."/</a></td>&nbsp;";
	    }
	    if($key == "title"){
		    echo "<td>".$val."</td></tr>";
	    }
        #echo "$key => $val\n";
    }
}	
?>
								</tr>
							</tbody>
						</table>
            </div>
          </div>
          </div>
             <div class="section">

            </div>
          </div>

          <footer class="page-footer marooncolor">
            <div class="container">
              <div class="row">
                <div class="col l6 s12">
                  <h5 class="white-text">The Constitutional Monarchy.</h5>
                  <p class="grey-text text-lighten-4">314chan would like to be as open as possible. We employ a system in which the boards are controlled by the users, and for the users. I will explain more in depth <a href="monarchy.html">here</a></p>
                  </div>
                <div class="col l3 s12">
                  <h5 class="white-text">Our Network</h5>
                  <ul>
                    <li><a class="white-text" href="http://www.76chan.org">76chan</a></li>
                    <li><a class="white-text" href="http://www.711chan.org">711chan</a></li>
                    <!--<li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>-->
                  </ul>
                </div>
                <div class="col l3 s12">
                  <h5 class="white-text">Connect</h5>
                  <ul>
                    <li><a class="white-text" href="#!">Link 1</a></li>
                    <li><a class="white-text" href="#!">Link 2</a></li>
                    <li><a class="white-text" href="#!">Link 3</a></li>
                    <li><a class="white-text" href="#!">Link 4</a></li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="footer-copyright">
              <div class="container">
              Site &copy; 2015&nbsp;314chan
              <div class="right"><a href="https://www.law.cornell.edu/uscode/text/47/230">All posts are the responsibility of the original poster.</a><div>
              </div>
            </div>
          </footer>


          <!--  Scripts-->
          <script src="js/jquery.js"></script>
          <script src="js/materialize.js"></script>
          <script src="js/init.js"></script>
					</body>
					</html>