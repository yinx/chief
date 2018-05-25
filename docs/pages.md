# Pages


# Work with pages

We recommend creating a PageController to manage the different page endpoints. Here's an example:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use App\Http\Controllers\Controller;

class DemoPageController extends Controller
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
`pages.index`, `pages.show`




## Create custom page

- routing
- model
- controllers
- views

- custom traits / behaviour -> bit extreme

-> easy setup for new page element.... chief:page <name>