# Ad Server

## Installation

* Run `composer install`.
* Create vw_ad_server database and import vw_ad_server.sql.
* Update Config.php with DB connection details.

## Notes

* I couldn't create a storage interface as I followed an ORM pattern for storage, and in doing so each developer created Model inherits the MysqliDriver methods if the Config driver is set to mysqli.
* I use the NetBeans default formatting (Alt + Shift + f) but would have liked to get some kind of lint running. I tried to get a psr2 plugin working in NetBeans but was unable to.