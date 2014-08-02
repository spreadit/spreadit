## spreadit                                                                                                                                                   
 
[spreadit](https://spreadit.io) is a privacy centric discussion board and link aggregator with a large focus on transparency and a "no-mod" design.
 
For notices of versions and other spreadit related announcements keep an eye on [/s/spreadit](https://spreadit.io/s/spreadit).
 
If you happen to find an exploit or have any other sensitive matter please email me @ admin spreadit.io before announcing it publicly. 
 
### Installation
 
Requirements:
 * git
 * curl
 * php5
 * npm
 * mysql
 * redis
 
Instructions:
* `git clone https://github.com/spreadit/spreadit.git`
* `cd spreadit`
* `curl -sS https://getcomposer.org/installer | php` #install composer
* `php composer.phar install` #install php dependencies
* `npm install` #install required js packages
* `grunt` #generate js/css
* `chmod -R 777 app/storage` #make cache & log dir writable
* `mysql -uroot -e "create database spreadit;"`
* `cd scripts`
* `mysql -uroot spreadit < spreadit.sql` #import db schema
 
Configuration:
 
* `app/config.php`
* `app/database.php`

### Contributing to spreadit
 
**All issues and pull requests should be filed on the [spreadit/spreadit](http://github.com/spreadit/spreadit) repository.
Please fork and create new pull request for any feature
 
 
### License
 
spreadit is open source software licensed under the [spreadit non-free license](https://spreadit.io/api/license)
 
~~~
              SPREADIT NONFREE PUBLIC LICENSE
                    Version 1, July 2014
 
 Copyright (C) 2004 Anonymous <admin@spreadit.io> 
 
 Everyone is permitted to copy and distribute verbatim or modified 
 copies of this license document, and changing it is allowed.
 
        DO WHAT THE FUCK YOU WANT TO AS LONG AS IT ISN'T EVIL
   TERMS AND CONDITIONS FOR COPYING, DISTRIBUTION AND MODIFICATION 
 
  0. You just DO WHAT THE FUCK YOU WANT TO (AS LONG AS IT ISN'T EVIL)
 
~~~
 
You'll have to use your own brain to determine whether or not what you are doing is evil.          
