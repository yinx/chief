@extends('chief::back._layouts.master')

@section('page-title','Pas "' .$module->slug .'" aan')


@component('chief::back._layouts._partials.header')
    @slot('title')
        {{ $module->slug }} <span class="text-subtle">{{ $module->collectionDetails()->singular }}</span>
    @endslot
    @slot('subtitle')
        <a class="center-y" href="{{ route('chief.back.modules.index') }}"><span class="icon icon-arrow-left"></span> Terug naar alle modules</a>
    @endslot
    <button data-submit-form="updateForm" type="button" class="btn btn-primary">Wijzigingen opslaan</button>
@endcomponent

@section('content')

  <form id="updateForm" method="POST" action="{{ route('chief.back.modules.update', $module->id) }}" enctype="multipart/form-data" role="form">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="PUT">

    @include('chief::back.modules._form')
  </form>

  @include('chief::back.modules._partials.delete-modal')

@stop

@push('custom-styles')
    <!-- make redactor available for any components. -->
    <script src="/chief-assets/back/js/vendors/redactor.js"></script>
@endpush

@push('custom-scripts-after-vue')
    @include('chief::back._layouts._partials.editor-script', ['imageUploadUrl' => route('modules.media.upload', $module->id)])
@endpush

@include('chief::back._elements.file-component')
@include('chief::back._elements.slimcropper-component')
@include('chief::back._elements.fileupload-component')
