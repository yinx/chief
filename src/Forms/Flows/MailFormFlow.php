<?php

namespace Thinktomorrow\Chief\Forms\Flows;

use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Notifications\FlowMail;
use Illuminate\Support\Facades\Mail;


class MailFormFlow extends Flow{

    public static function run(ChiefForm $model, $request) {
        self::checkRequiredMethods($model);

        $recipients = $model->recipients();
        $view       = 'chief::'.$model->view();
        $subject    = $model->subject();

        Mail::to($recipients)->queue(new FlowMail($request, $view, $subject));
    }

    protected static function requiredMethods()
    {
        return ['recipients', 'view', 'subject'];
    }
}