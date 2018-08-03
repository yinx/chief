<?php

namespace Thinktomorrow\Chief\Tests\Feature\Forms;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\ContactForm;
use Thinktomorrow\Chief\Forms\ChiefForm;

class ChiefFormTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.forms', [
            'contact'  => ContactForm::class,
            // 'articles' => ArticlePageFake::class,
            // 'others'   => OtherCollectionFake::class,
        ]);
    }

    /** @test */
    public function it_can_guess_the_model_based_on_request()
    {
        $request = ['formtype'=> ContactForm::class];

        $model = ChiefForm::guessModel($request);

        $this->assertInstanceOf(ContactForm::class, $model);
    }

    /** @test */
    public function formtype_needs_to_be_present_in_request()
    {
        $this->expectException(\DomainException::class);
        $request = ['formtype'=> null];

        $model = ChiefForm::guessModel($request);
    }
}

class OtherFormFake extends ChiefForm
{
}
