@extends('chief::back._layouts.master')

@section('page-title','Form')

@component('chief::back._layouts._partials.header')
    @slot('title', 'Form entries for "'. $form->classtype . '"')
@endcomponent

@section('content')
    <div class="treeview stack-l">
        <div class="row">
            @foreach((new $form->type)->customFields() as $field)
                <div class="column center-y">
                    <strong>{{$field}}</strong>
                </div>
            @endforeach
        </div>
        @foreach($form->entries as $entry)
            <div class="row">
                @foreach((new $form->type)->customFields() as $field)
                    <div class="column center-y">
                        {{ $entry->fields[$field] }}
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

@stop
