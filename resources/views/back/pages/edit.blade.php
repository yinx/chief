@extends('chief::back._layouts.master')

@section('page-title','Pas "' .$page->title .'" aan')


@component('chief::back._layouts._partials.header')
    @slot('title', $page->title )

    <div class="inline-group-s">
        {!! $page->statusAsLabel() !!}

        <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
        @include('chief::back.pages._partials.context-menu')
    </div>

@endcomponent

@section('content')

  <form id="updateForm" method="POST" action="{{ route('chief.back.pages.update', $page->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('chief::back.pages._form')
    @include('chief::back.pages._partials.modal')
    @include('chief::back.pages._partials.sidebar')

  </form>

  @include('chief::back.pages._partials.delete-modal')

@stop

@push('custom-scripts-after-vue')
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => route('pages.media.upload', $page->id)]))
@endpush
