# Ad Server

## Installation

* Run `composer install`.
* Create vw_ad_server database and import vw_ad_server.sql.
* Update Config.php with DB connection details.

## Usage

### Campaigns
```ruby
GET http://host/campaigns - retrieve all
GET http://host/campaigns/1 - retrieve by ID
POST http://host/campaigns - create
DELETE http://host/campaigns - delete all
DELETE http://host/campaigns/1 - delete by ID
```

### Banners
```ruby
GET http://host/banners - retrieve all
GET http://host/banners/1 - retrieve by ID
POST http://host/banners - create
DELETE http://host/banners - delete all
DELETE http://host/banners/1 - delete by ID
```

### Campaign and banners
Example banners key:
```ruby
[{"name":"banner1", "width": 200, "height": 300, "content": "lorem"}, {"name": "banner2", "width": 400, "height": 500, "content": "ipsum"}, {"name": "banner3", "width": 600, "height": 700, "content": "dolor"}])
```
```ruby
GET http://host/campaignsBanners - create batch
```

### Recommend
```ruby
GET http://host/recommend?width=200&height=300 - retrieve random banner
```

## Notes

* I couldn't create a storage interface as I followed an ORM pattern, and in doing so each developer created Model only uses static methods from the MysqliDriver class.
* I use the NetBeans default formatting (Alt + Shift + f) but would have liked to get some kind of lint running. I tried to get a psr2 plugin working in NetBeans but was unable to.
* I would have broken the controller logic up into other classes to avoid instantiating controllers but I didn't due to lack of time. I would like to have found a solution to passing $_POST data to these classes instead of using the global $_POST but that would require understanding the RestServer route handling better. I could have maybe passed all the parameters through the URL to try fix the $_POST issue but this would have presented other issues.
* Would like to have setup and use JSON data for sending to the API.
* Didn't have time to do API update (PUT) calls.
* Would have setup .env but had no time so went with a static class approach.