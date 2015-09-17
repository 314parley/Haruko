<?php

namespace Mitsuba;

class Frontpage {

    private $conn;

    private $config;

    private $mitsuba;

    function __construct($connection, &$mitsuba) {

        $this->conn = $connection;

        $this->mitsuba = $mitsuba;

        $this->config = $this->mitsuba->config;

    }

    

    function generateFrontpage($action = "none") {

        $file = '<!doctype html>';

        $file.= '<html lang="en">

        <head>

            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

						<meta charset="UTF-8">

						<title>' . $this->config['sitename'] . '</title>

            <link href="css/MIcons.css" rel="stylesheet">

            <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>

            <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

					</head>

					<body>

          <div class="navbar-fixed">

              <nav class="marooncolor" role="navigation">

            <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">' . $this->config['sitename'] . '</a>

            '.

            //put an if statement on weather the sitename equals "314chan" here"

            '

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

              '.

              //end if statement

              '

              <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>

            </div>

          </nav>

        </div>

          <div class="section no-pad-bot" id="index-banner">

            <div class="container">

              <br><br>

              <div class="card-panel">

              <table class="centered striped">

                      <thead>

                        <tr>

                          <h4>Boards</h4><small>Sections are currently broken</small>

                        </tr>';

                        $cats = $this->conn->query("SELECT * FROM links WHERE parent=-1 ORDER BY short ASC;");

                        while ($row = $cats->fetch_assoc())

                        $file .= '<th data-field="section">'.$row['title'].'</th>';

                        $file .= '

                      </thead>

                      <tbody>

                        ';

                        $cats = $this->conn->query("SELECT * FROM links WHERE parent=-1 ORDER BY short ASC;");

                        while ($row = $cats->fetch_assoc()){

	                        $children = $this->conn->query("SELECT * FROM links WHERE parent=".$row['id']." ORDER BY short ASC");

	                        while ($child = $children->fetch_assoc()){

		                        if (!empty($child['url_index'])){

			                        $file .= '<td>

			                        <a class="boardlink" href="'.$child['url_index'].'" title="'.$child['title'].'">'.$child['title'].'</a>

			                        </td>';

		                        }else{

			                        $file .= '<td>

			                        <a class="boardlink" href="'.$child['url'].'" title="'.$child['title'].'">/'.$child['url'].'/ - '.$child['title'].'</a>

			                        </td>';

		                        }

	                        }

                        }

                        $file .='

                        </tbody>

                    </table>

              <br><br>



            </div>

          </div>

          </div>





          <div class="container">

            <div class="row">

               <div class="col s6">

                 <h4><div class="card-panel">Recent Images</div></h4>

                 <div class="row">

             <!--<div class="col s12">This div is 12-columns wide</div>-->

             <div class="col s6">

               <div class="card">

                    <div class="card-image">

                      <img src="img/sample-1.jpg">

                      <span class="card-title">Subject</span>

                    </div>

                    <div class="card-content">

                      <p>Im a post, with a subject, and with an image.<br /><a>>>555555</a><br />blahblah</a></p>

                    </div>

                    <div class="card-action">

                      <a href="#">View</a>

                      <a href="#">Reply</a>

                    </div>

                  </div>

             </div>

             <div class="col s6">

               <div class="card">

                    <div class="card-image">

                      <img src="img/sample-1.jpg">

                      <span class="card-title"></span>

                    </div>

                    <div class="card-content">

                      <p>Im a post, without a subject, and with an image.<br /><a>>>555555</a><br />blahblah</a></p>

                    </div>

                    <div class="card-action">

                      <a href="#">View</a>

                      <a href="#">Reply</a>

                    </div>

                  </div>

             </div>

           </div>

               </div>



               <div class="col s6">

                 <div class="card medium">

                 <ul class="collection with-header">

                <li class="collection-header"><h4>Statistics</h4></li>';

				$result = $this->conn->query("SELECT * FROM posts");

					$num_rows = $result->num_rows;



				$result = $this->conn->query("SELECT DISTINCT ip FROM posts");

					$num_users = $result->num_rows;



				$result = $this->conn->query("SELECT sum(orig_filesize) FROM posts");

					$num_bytes = $result->fetch_array()[0];



				$result = $this->conn->query("SELECT * FROM `bans`");

					$num_bans = $result->num_rows;

		{

			$file .= '<li class="collection-item"><strong>Total posts:</strong> '.$num_rows.'</li>

					  <li class="collection-item"><strong>Unique posters:</strong> '.$num_users.'</li>

					  <li class="collection-item"><strong>Active content:</strong> '.$this->mitsuba->common->human_filesize($num_bytes).'</li>

					  <li class="collection-item"><strong>Active Bans:</strong> '.$num_bans.'</li>

			';

		}

                $file .= '

              </ul>

            </div>

               </div>

             </div>

            <br><br>



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

              Site &copy; ' . date("Y") . '&nbsp;'.$this->config['sitename'].'

              <div class="right"><a href="https://www.law.cornell.edu/uscode/text/47/230">All posts are the responsibility of the original poster.</a><div>

              </div>

            </div>

          </footer>





          <!--  Scripts-->

          <script src="js/jquery.js"></script>

          <script src="js/materialize.js"></script>

          <script src="js/init.js"></script>

					</body>

					</html>';

        $handle = fopen("./" . $this->config['frontpage_url'], "w");

        fwrite($handle, $file);

        fclose($handle);

    }

    function generateNews() {

        $file = '<!doctype html>';

        $file.= '

        <html lang="en">

        <head>

            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

            <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>

						<meta charset="UTF-8">

						<title>' . $this->config['sitename'] . ' - News</title>

            <link href="css/MIcons.css" rel="stylesheet">

            <link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>

            <link href="css/style.css" type="text/css" rel="stylesheet" media="screen,projection"/>

					</head>



        ';

        $file.= '

        					<body>

          <div class="navbar-fixed">

              <nav class="marooncolor" role="navigation">

            <div class="nav-wrapper container"><a id="logo-container" href="#" class="brand-logo">' . $this->config['sitename'] . '</a>

            '.

            //put an if statement on weather the sitename equals "314chan" here"

            '

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

              '.

              //end if statement

              '

              <a href="#" data-activates="nav-mobile" class="button-collapse"><i class="material-icons">menu</i></a>

            </div>

          </nav>

        </div>



        ';

        $file.= '

        <div class="section no-pad-bot" id="index-banner">

            <div class="container">

              <br><br>

        ';

        $result = $this->conn->query("SELECT * FROM news ORDER BY date DESC;");

        while ($row = $result->fetch_assoc()) {

            $file.= '<div class="card-panel">';

            $file.= '<h4>' . $row['title'] . ' by ' . $row['who'] . ' - ' . date("d/m/Y h:i", $row['date']) . '</h4><hr />';

            $file.= $row['text'];

            $file.= '</div>';

        }

        $file.= '</div>

			</div>

			</div>

			</div>

			</body>

			</html>';

        $handle = fopen("./" . $this->config['news_url'], "w");

        fwrite($handle, $file);

        fclose($handle);

    }

}

?>
