Arinoid Application Template
===================================

The template includes three tiers: front end, back end, and console, each of which
is a separate Yii application.

The template is designed to work in a team development environment. It supports
deploying the application in different environments.


DIRECTORY STRUCTURE
-------------------

```
common
	config/				contains shared configurations
	mail/				contains view files for e-mails
	models/				contains model classes used in both backend and frontend
	tests/				contains various tests for objects that are common among applications
console
	config/				contains console configurations
	controllers/		contains console controllers (commands)
	migrations/			contains database migrations
	models/				contains console-specific model classes
	runtime/			contains files generated during runtime
	tests/				contains various tests for the console application
backend
	assets/				contains application assets such as JavaScript and CSS
	config/				contains backend configurations
	controllers/		contains Web controller classes
	models/				contains backend-specific model classes
	runtime/			contains files generated during runtime
	tests/				contains various tests for the backend application
	views/				contains view files for the Web application
	web/				contains the entry script and Web resources
frontend
	assets/				contains application assets such as JavaScript and CSS
	config/				contains frontend configurations
	controllers/		contains Web controller classes
	models/				contains frontend-specific model classes
	runtime/			contains files generated during runtime
	tests/				contains various tests for the frontend application
	views/				contains view files for the Web application
	web/				contains the entry script and Web resources
vendor/					contains dependent 3rd-party packages
environments/			contains environment-based overrides
```


REQUIREMENTS
------------

* PHP 5.5.0+
* `composer`
* `imagemagick`
* `wkhtmltoimage`


GETTING STARTED
---------------

After you install the application, you have to conduct the following steps to initialize
the installed application. You only need to do these once for all.

1. `composer update`
2. `php yii init`
3. Create a new database `arinoid`.
4. `php yii migrate`
5. Set document roots of your Web server:

- for frontend `/path_to_application/frontend/web/` and using the URL `http://arinoid.local/`
- for backend `/path_to_application/backend/web/` and using the URL `http://admin.arinoid.local/`

SERVER UPDATE
_____________

1. `git pull`
2. `./vendor/codegyre/robo/robo run`



TESTING
-------

Install additional composer packages:
* `php composer.phar require --dev "codeception/codeception: 1.8.*@dev" "codeception/specify: *" "codeception/verify: *"`

This application boilerplate use database in testing, so you should create three databases that are used in tests:
* `yii2_advanced_unit` - database for unit tests;
* `yii2_advanced_functional` - database for functional tests;
* `yii2_advanced_acceptance` - database for acceptance tests.

To make your database up to date, you can run in needed test folder `yii migrate`, for example
if you are starting from `frontend` tests then you should run `yii migrate` in each suite folder `acceptance`, `functional`, `unit`
it will upgrade your database to the last state according migrations.

To be able to run acceptance tests you need a running webserver. For this you can use the php builtin server and run it in the directory where your main project folder is located. For example if your application is located in `/www/advanced` all you need to is:
`cd /www` and then `php -S 127.0.0.1:8080` because the default configuration of acceptance tests expects the url of the application to be `/advanced/`.
If you already have a server configured or your application is not located in a folder called `advanced`, you may need to adjust the `TEST_ENTRY_URL` in `frontend/tests/_bootstrap.php` and `backend/tests/_bootstrap.php`.

After that is done you should be able to run your tests, for example to run `frontend` tests do:

* `cd frontend`
* `../vendor/bin/codecept build`
* `../vendor/bin/codecept run`

In similar way you can run tests for other application tiers - `backend`, `console`, `common`.

You also can adjust you application suite configs and `_bootstrap.php` settings to use other urls and files, as it is can be done in `yii2-basic`.
