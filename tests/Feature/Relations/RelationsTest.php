<?php

namespace Thinktomorrow\Chief\Tests\Feature\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Relations\Relation;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\ChiefDatabaseTransactions;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class RelationsTest extends TestCase
{
    use ChiefDatabaseTransactions;

    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        ParentFake::migrate();
        ChildFake::migrate();
    }

    /** @test */
    public function a_parent_can_attach_a_child()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);

        $this->assertCount(1, $parent->children());
        $this->assertEquals($child->id, $parent->children()->first()->id);

        $this->assertCount(1, $child->parents());
        $this->assertEquals($parent->id, $child->parents()->first()->id);
    }

    /** @test */
    public function a_parent_can_attach_multiple_children()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        $parent->adoptChild($child);
        $parent->adoptChild($child2);

        $this->assertCount(2, $parent->children());
        $this->assertInstanceOf(ChildFake::class, $parent->children()->first());
    }

    /** @test */
    public function a_child_can_attach_multiple_parents()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $child->acceptParent($parent2);

        $this->assertCount(2, $child->parents());
        $this->assertInstanceOf(ParentFake::class, $child->parents()->first());
    }

    /** @test */
    public function a_parent_can_detach_a_child()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $parent->adoptChild($child);
        $this->assertCount(1, $parent->children());

        $parent->rejectChild($child);

        // Relationship is no more but Child object itself still exists
        $this->assertCount(0, $parent->children());

        $this->assertNotNull(ChildFake::find($child->id));
        $this->assertCount(0, $child->fresh()->parents());
    }

    /** @test */
    public function a_child_can_detach_its_parent()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent);
        $this->assertCount(1, $child->parents());

        $child->rejectParent($parent);

        $this->assertCount(0, $child->parents());

        $this->assertNotNull(ParentFake::find($parent->id));
        $this->assertCount(0, $parent->fresh()->children());
    }

    /** @test */
    public function a_parent_or_child_can_get_relation_model()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $parent->adoptChild($child);

        $this->assertInstanceOf(Relation::class, $parent->relationWithChild($child));
        $this->assertInstanceOf(Relation::class, $child->relationWithParent($parent));
    }

    /** @test */
    public function child_relations_are_sorted()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();

        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $this->assertEquals($child2->id, $parent->children()->first()->id);
        $this->assertEquals($child->id, $parent->children()->last()->id);
    }

    /** @test */
    public function parent_relations_are_sorted()
    {
        $parent = ParentFake::create();
        $parent2 = ParentFake::create();
        $child = ChildFake::create();

        $child->acceptParent($parent, ['sort' => 2]);
        $child->acceptParent($parent2, ['sort' => 1]);

        $this->assertEquals($parent2->id, $child->parents()->first()->id);
        $this->assertEquals($parent->id, $child->parents()->last()->id);
    }
    
    /** @test */
    public function a_parent_or_child_can_return_a_relation_identifier()
    {
        $parent = ParentFake::create();
        $child = ChildFake::create();

        $this->assertEquals($parent->getMorphClass().'@'.$parent->id, $parent->flatReference()->get());
        $this->assertEquals($child->getMorphClass().'@'.$child->id, $child->flatReference()->get());
    }

    /** @test */
    public function available_children_for_a_parent_can_be_listed_as_a_collection()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
        ]);

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $relations = Relation::availableChildren($parent);


        $this->assertInstanceOf(Collection::class, $relations);
        $this->assertEquals([$child->id, $child2->id], $relations->pluck('id')->toArray());
    }

    /** @test */
    public function available_children_can_be_page_or_module()
    {
        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
        ]);

        $parent = ParentFake::create();
        $child = ChildFake::create();
        $child2 = ChildFake::create();
        $parent->adoptChild($child, ['sort' => 2]);
        $parent->adoptChild($child2, ['sort' => 1]);

        $relations = Relation::availableChildren($parent);


        $this->assertInstanceOf(Collection::class, $relations);
        $this->assertEquals([$child->id, $child2->id], $relations->pluck('id')->toArray());
    }

    /** @test */
    public function page_that_is_not_listed_as_child_should_not_be_available()
    {
        config()->set('thinktomorrow.chief.collections', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);

        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
            ArticlePageFake::class,
        ]);

        // Create models
        $parent = ParentFake::create();
        ChildFake::create();
        ArticlePageFake::create(['collection' => 'articles']);
        Single::create(['collection' => 'singles']);

        $availableChildren = Relation::availableChildren($parent);

        $this->assertCount(2, $availableChildren);
        $this->assertInstanceOf(ArticlePageFake::class, $availableChildren[0]);
        $this->assertInstanceOf(ChildFake::class, $availableChildren[1]);
    }

    /** @test */
    public function page_as_parent_should_not_listed_as_available()
    {
        config()->set('thinktomorrow.chief.collections', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
        ]);

        config()->set('thinktomorrow.chief.relations.children', [
            ChildFake::class,
            ArticlePageFake::class,
        ]);

        // Create models
        $parent = ArticlePageFake::create(['collection' => 'articles']);
        ChildFake::create();
        ArticlePageFake::create(['collection' => 'articles', 'id' => 999]);
        Single::create(['collection' => 'singles']);

        $availableChildren = Relation::availableChildren($parent);

        $this->assertCount(2, $availableChildren);
        $this->assertInstanceOf(ArticlePageFake::class, $availableChildren[0]);
        $this->assertInstanceOf(ChildFake::class, $availableChildren[1]);

        // Assert the proper article is kept as child
        $this->assertEquals(999, $availableChildren[0]->id);
    }
}
