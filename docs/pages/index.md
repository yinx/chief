[Install](../index.md)
[Local development](../chief-development.md)
[Pages](pages/index.md)
[Server](../server.md)
[Changelog](../CHANGELOG.md)
[Guidelines](../GUIDELINES.md)
# Pages

## TODO
- what is a page?
- Single page
- adding a new page (+ what's needs to be created)
- customizing a page model
    - fields
    - media
- detached page

## required
Make sure to set your namespace in the config `config/thinktomorrow/chief.php`.  

# Work with pages
Each page model should basically require:
- routes
- corresponding controllers
- views.

We recommend creating a PageController to manage the different page endpoints. Here's an example:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function index()
    {
        return view('pages.index', [
            'pages' => Page::all()
        ]);
    }

    public function show(Request $request)
    {
        return view('pages.index', [
            'page' => Page::findBySlug($request->slug)
        ]);
    }
}
```

Create the necessary routes for each endpoint. By default, we assume the page routing has the following naming convention:
`pages.index`, `pages.show` (TODO: elaborate)

## Adding a new page
- create a new model and extend from the Page model like so:


## Setting custom fields
We can use squanto fields to include a way to manage static content. Squanto is the package that deals with static text management of the site.
The way you can aim these translations to a specific

## Page API
#### Page::all()
Retrieves all the pages

#### Page::findBySlug($slug)
Retrieve a page by its unique slug value.

#### Page::sortedByCreated()->all()
Sort the results by last created pages.

## Publishable API

#### Page::getAllPublished()
Retrieves all the published pages.

#### Page::findPublishedBySlug($slug)
Retrieve a published page by its unique slug value.
If the page is not published, no page will be returned.

#### Page::sortedByPublished()->all()
Sort the results by last created pages.

#### $page->isPublished()
Returns true of false based on the published status
See isDraft() for the inverse.

#### $page->isDraft()
Return true or false based on the draft status
See isPublished() for the inverse.

#### $page->publish()
Changes the page to published
See draft() for the inverse

#### $page->draft()
Changes the page to draft
See publish() for the inverse

## Featurable API

#### $page->isFeatured()
Returns true of false based on the featured status
See isDraft() for the inverse.

#### $page->feature()
Changes the page to featured
See unfeature() for the inverse

#### $page->unfeature()
Changes the page to not featured
See feature() for the inverse

#### Page::featured()->all()
Scope the query by the featured pages.

## Create custom page

- routing
- model
- controllers
- views

- custom traits / behaviour -> bit extreme

-> easy setup for new page element.... chief:page <name>