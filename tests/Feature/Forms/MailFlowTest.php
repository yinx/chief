<?php

namespace Thinktomorrow\Chief\Tests\Feature\Forms;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\ChiefForm;
use Thinktomorrow\Chief\App\Notifications\FlowMail;
use Illuminate\Support\Facades\Mail;


class MailFlowTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.forms', [
            'contact'  => ContactForm::class,
            'mail'     => MailFormFake::class,
            // 'others'   => OtherCollectionFake::class,
        ]);
    }

  
    /** @test */
    public function if_mail_then_some_methods_are_required()
    {
        $this->expectException(\Exception::class);

        $form = new MailFormFake();

        $form->handleFlow([]);
    }

    /** @test */
    public function valid_setup_can_send_mail()
    {
        Mail::fake();
        
        $form = new ValidMailFormFake();

        $form->handleFlow([]);

        Mail::assertQueued(FlowMail::class);
    }
}

class MailFormFake extends ChiefForm
{
    public function flow()
    {
        return ['mail'];
    }
}

class ValidMailFormFake extends ChiefForm
{

    public function flow()
    {
        return ['mail'];
    }

    public function recipients()
    {
        return ['email' => config('thinktomorrow.chief.contact.email')];
    }

    public function subject(){
        return "contact mail";
    }

    public function view()
    {
        return "back.mails.contact";
    }
}