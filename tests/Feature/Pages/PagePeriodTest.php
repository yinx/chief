<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;
use Illuminate\Support\Carbon;

class PagePeriodTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles'    => ArticlePageFake::class,
        ]);
    }

    /** @test */
    public function it_can_set_a_period_for_a_page()
    {
        $page = Page::create(['collection' => 'singles', 'slug' => 'foobar', 'title:nl' => 'foobar']);

        $this->asAdmin()
            ->put(route('chief.back.pages.update', $page->id), $this->validUpdatePageParams([
                'start_at' => '2019-01-01',
                'end_at' => '2019-01-12',
            ]));

        $this->assertTrue($page->hasPeriod());
        $this->assertEquals(Carbon::create('2019','1','1'), $page->fresh()->start_at);
        $this->assertEquals(Carbon::create('2019','1','12'), $page->fresh()->end_at);
    }

}
