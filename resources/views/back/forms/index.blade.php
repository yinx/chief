@extends('chief::back._layouts.master')

@section('page-title', "Forms")

@component('chief::back._layouts._partials.header')
@slot('title', 'Form inputs')
@endcomponent

@section('content')
    @foreach($forms as $form)
        <div class="column-6">
            <div class="panel panel-default --raised">
                <a href="{{ route('chief.back.forms.show', $form->id) }}">
                    <div class="panel-body inset">
                        <div class="stack">
                            <h1 class="--remove-margin">{{ $form->classtype }}</h1>
                        <p>{{ count($form->entries) }}</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
@stop
