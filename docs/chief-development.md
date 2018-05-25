# Local Chief development

For local development of chief we need another project to include the Chief package into since a package does not contain the whole laravel framework.
To set up the chief package for local development we link our local chief folder as a repository in the composer.json file.

Paste the following snippet in your composer.json file. This can be placed right above the 'require' section.
```php
“repositories”:[
       {
           “url”:“/full/path/to/chief”,
           “type”:“path”,
           “options”:{
               “symlinks”:true
           }
       }
   ],
```
The url property needs to be the full path to the local version of the chief package.

Next install the local version of Chief:
```php 
composer require thinktomorrow/chief
``` 

To migrate and scaffold some entries you can run:
```php
php artisan chief:db-refresh
```
**Note that this will remove your existing database entries!**

The following setup can be done the same as the install procedure. Follow the steps in this file and exclude the
steps related to the database migrations and composer installment.

// TODO: something about adding to semver, changelog...
