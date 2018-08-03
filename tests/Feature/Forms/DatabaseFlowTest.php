<?php

namespace Thinktomorrow\Chief\Tests\Feature\Forms;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Thinktomorrow\Chief\App\Notifications\FlowMail;
use Illuminate\Support\Facades\Mail;
use Thinktomorrow\Chief\Forms\FormEntry;


class DatabaseFlowTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.forms', [
            'contact' => ContactForm::class,
            'db'      => ValidDatabaseFormFake::class,
        ]);
    }
  
    // /** @test */
    // public function if_database_then_some_methods_are_required()
    // {
    //     $this->expectException(\Exception::class);

    //     $form = new MailFormFake();

    //     $form->handleFlow([]);
    // }

    /** @test */
    public function valid_setup_will_save_to_database()
    {
        $form = new ValidDatabaseFormFake();

        $form->handleFlow([
            'formtype'  => 'db',
            'firstname' => 'foo',
            'lastname'  => 'bar',
            'email'     => 'foo@bar.com',
            'content'   => 'foobar barfoo'
            ]);

        $this->assertDatabaseHas('forms', ['type' => 'db']);

        $this->assertJsonStringEqualsJsonString(json_encode([
            'firstname' => 'foo',
            'lastname'  => 'bar',
            'email'     => 'foo@bar.com',
            'content'   => 'foobar barfoo'
        ]), FormEntry::first()->fields);
    }
}

class ValidDatabaseFormFake extends ChiefForm
{
    public function customFields(){
        return ['firstname', 'lastname', 'email', 'content'];
    }

    public function flow()
    {
        return ['database'];
    }
}