<?php

class DatabaseFormFlow{
    
    public function run(Form $model, Request $request) {
        $fields = $model->fields();
    }
}