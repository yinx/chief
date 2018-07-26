<?php

class MailFlow{

    public function run(Form $model, Request $request) {
        if(!method_exists($model, 'recipients'))
        {
            throw new \Exception('Form needs to implement the recipients method when defining the mail flow');
        }

        if(!method_exists($model, 'view'))
        {
            throw new \Exception('Form needs to implement the view method when defining the mail flow');
        }

        if(!method_exists($model, 'data'))
        {
            throw new \Exception('Form needs to implement the data method when defining the mail flow');
        }

        $recipients = $model->recipients();
        $view       = $model->view();
        $data       = $model->data();

        (new FlowMail($data, $this->view))->toMail($this->recipients);
    }

    
}