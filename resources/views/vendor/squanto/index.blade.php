@extends('chief::back._layouts.master')

@section('page-title')
    Teksten
@stop

@component('chief::back._layouts._partials.header')
    @slot('title','Teksten')
    @if(Auth::user()->isSquantoDeveloper())
        <a href="{{ route('squanto.lines.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> add new line</a>
    @endif
@endcomponent

@section('content')
    <div class="row gutter card-group left">
        @foreach($pages as $page)
            @include('squanto::_rowitem')
        @endforeach
    </div>
@stop
