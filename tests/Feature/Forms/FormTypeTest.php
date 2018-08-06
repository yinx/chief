<?php

namespace Thinktomorrow\Chief\Tests\Feature\Forms;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\ContactForm;
use Thinktomorrow\Chief\Forms\ChiefForm;

class FormTypeTest extends TestCase
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
    public function a_form_requires_a_type()
    {
        $this->expectException(\PDOException::class);

        factory(ChiefForm::class)->create(['type' => null]);
    }


}
