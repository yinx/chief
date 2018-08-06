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

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Thinktomorrow\Chief\Pages\Page;

class PagesController extends Controller
{
    public function show($slug)
    {
        if(!$page = Page::findPublishedBySlug($slug)) {
            throw new NotFoundHttpException('No published page found by slug ['.$slug.']');
        }

        // TODO: If slug matches the homepage page, redirect to root to avoid duplicate content
        if($page->isHomepage()) {
            return redirect()->route('pages.home');
        }

        return $page->view();
    }

    public function homepage()
    {
        // Get the page that has the flag 'is_homepage'. Otherwise we take the first singles pages found. If not found, we take the first published page...
        $page = Page::guessHomepage();

        return $page->view();
    }
}
```

Create the necessary routes for each endpoint. By default, we assume the page routing has the following naming convention:
`pages.index`, `pages.show` (TODO: elaborate)

## Adding a new page
- create a new model and extend from the Page model like so:

## Building the page
Defining the layout for a page is quite simple. We use a very basic type of pagebuilder which allows you to add textfields which can contain images/columns/link/button/etc.
You can also add modules in between text sections. For a more detailed explanation about modules look  [here](../modules/index.md)


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