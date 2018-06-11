# Ad Server

## Installation

* Run `composer install`.
* Create vw_ad_server database and import vw_ad_server.sql.
* Update Config.php with DB connection details.

## Usage

### Campaigns
```ruby
POST http://host/campaigns - create
GET http://host/campaigns - read all
GET http://host/campaigns/1 - read by ID
DELETE http://host/campaigns - delete all
DELETE http://host/campaigns/1 - delete by ID
```

### Banners
```ruby
POST http://host/banners - create
GET http://host/banners - read all
GET http://host/banners/1 - read by ID
DELETE http://host/banners - delete all
DELETE http://host/banners/1 - delete by ID
```

### Campaign and banners
```ruby
GET http://host/campaignAndBanners - create batch
```
Example `banners` key:
```ruby
[{"name":"banner1", "width": 200, "height": 300, "content": "lorem"}, {"name": "banner2", "width": 400, "height": 500, "content": "ipsum"}, {"name": "banner3", "width": 600, "height": 700, "content": "dolor"}])
```

### Recommend
```ruby
GET http://host/recommend?width=200&height=300 - read random banner
```

## Notes

* I didn't need to create a storage interface as I followed an ORM pattern, and in doing so each developer created Model just extends Model which is my storage handler. I would need to think of another way to implement using a storage interface.
* I use the NetBeans default formatting (Alt + Shift + f) but would have liked to get some kind of lint running. I tried to get a psr2 plugin working in NetBeans but was unable to.
* I would have broken the controller logic up into other classes to avoid instantiating controllers but I didn't due to lack of time. I would like to have found a solution to passing $_POST data to these classes instead of using the global $_POST but that would require understanding the RestServer route handling better. I could have maybe passed all the parameters through the URL to try fix the $_POST issue but this would have presented other issues.
* Would like to have setup and use JSON data for sending to the API.
* Would have setup .env but had no time so went with a static "singleton" approach.
* If allowed I would have added an additional library for running unit tests to check each API call is working correctly.