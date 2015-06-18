Haruko [![Build Status](https://travis-ci.org/314parley/Haruko.svg)](https://travis-ci.org/314parley/Haruko)
=======

Project Haruko (previously Mitsuba) is an opensource (AGPLv3 licensed) image board software written in PHP and using MySQLi. This is the version deployed on a test 314chan server (contact [parley](mailto:admin@314chan.org) for URL, or visit 314chan IRC)

Installation
------------

**Warning: ** Haruko needs ZipArchive for module uploads!

To install Haruko you'll have to:
* Run {install_location}/install.php

Planned features
----------------

* Staff system where the users vote for the head administrator. (please give us ideas!)
* Revamped, fully HTML5 mod panel
* "Not a bot" Captcha (sans Google/reCaptcha)
* Improved spam detection
* Easier deployment

Todo
----------------

* Work on converting all mod pages to AdminLTE compatable pages
* Extend API to the new(er) 4chan/Vichan API
* Other?

API
---

The API is at {install_location}/`board`/`thread_no`.json

For more info visit [4chan's api](https://github.com/4chan/4chan-API)

Credits
-------
Desuneko - creator and developer of Project Haruko (RIP)

skandal - first betatester, helped with bughunting, made Polish translation and several alternative stylesheets

hardkurahen - javascripts

JanPavulon - javascripts

sebagaua - some PHPs

License
--------
See [LICENSE](https://github.com/HarukoBBS/Haruko/blob/master/LICENSE).

Haruko contains code from [jQuery (MIT license)](http://jquery.com/), [PHP Markdown by Michel Fortin (custom license, available on website)](http://michelf.ca/projects/php-markdown/), [jBBCode by Jackson Owens (MIT license)](http://jbbcode.com/), [cool-php-captcha by José Rodríguez (GPLv3)](https://code.google.com/p/cool-php-captcha/), [Meny by Hakim El Hattab (custom license, available on website)](https://github.com/hakimel/Meny), [AdminLTE (MIT license)](https://github.com/almasaeed2010/AdminLTE) and [jquery.cookie by Klaus Hartl (MIT license)](https://github.com/carhartl/jquery-cookie).
