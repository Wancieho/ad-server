# Ad Server

## Installation

* Run `composer install`.
* Create vw_ad_server database and import vw_ad_server.sql.
* Update Config.php with DB connection details.

## Notes

* I couldn't create a storage interface as I followed an ORM pattern for storage, and in doing so each developer created Model inherits the MysqliDriver methods if the Config driver is set to mysqli.
* I use the NetBeans default formatting (Alt + Shift + f) but would have liked to get some kind of lint running. I tried to get a psr2 plugin working in NetBeans but was unable to.
* I would have broken the controller logic up into other classes to avoid instantiating controllers but I didn't due to lack of time. I would like to have found a solution to passing $_POST data to these classes instead of using the global $_POST but that would require understanding the RestServer route handling better. I could have maybe passed all the parameters through the URL to try fix the $_POST issue but this would have presented other issues.
* Would like to have setup and use JSON data for sending to the API