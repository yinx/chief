<?php

namespace Thinktomorrow\Chief\Flows;

use Thinktomorrow\Chief\Forms\ChiefForm;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Notifications\FlowMail;
use Illuminate\Support\Facades\Mail;


class MailFormFlow extends Flow{

    public static function run(ChiefForm $model, Request $request) {
        self::checkRequiredMethods($model);

        $recipients = $model->recipients();
        $view       = 'chief::'.$model->view();
        $subject    = $model->subject();

        Mail::to($recipients)->queue(new FlowMail($request->all(), $view, $subject));
    }

    protected static function requiredMethods()
    {
        return ['recipients', 'view', 'subject'];
    }
}