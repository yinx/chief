@extends('back._layouts.master')

@section('page-title','Voeg nieuw artikel toe')

@component('back._layouts._partials.header')
    @slot('title', 'Nieuw artikel')
    <div class="btn-group relative">
		<button type="button" class="btn btn-primary squished">Save</button>
		<button type="button" class="btn btn-primary squished dropdown-toggle" data-toggle="dropdown">
			<span class="icon icon-chevron-down"></span>
		</button>
		<ul class="dropdown-menu" role="menu">
			<li><a href="#">As draft</a></li>
			<li><a href="#">In review</a></li>
		</ul>
	</div>

@endcomponent

@push('custom-styles')
	<link rel="stylesheet" href="{{ asset('assets/back/vendor/redactor2/redactor.css') }}">
	<link href="{{ asset('assets/back/theme/vendor/plugins/datepicker/css/bootstrap-datetimepicker.css') }}"
	      rel="stylesheet" type="text/css">
@endpush

@push('custom-scripts')
	<script src="{{ asset('assets/back/vendor/redactor2/redactor.js') }}"></script>
	<script>
		;(function ($) {

			$('.redactor-editor').redactor({
				focus: true,
				pastePlainText: true,
				buttons: ['html', 'formatting', 'bold', 'italic',
					'unorderedlist', 'orderedlist', 'outdent', 'indent',
					'link', 'alignment', 'horizontalrule']
			});

		})(jQuery);

	</script>

@endpush

@section('content')

	<form method="POST" action="{{ route('back.articles.store') }}" enctype="multipart/form-data" role="form">
		{{ csrf_field() }}

		@include('back.articles._form')

	</form>

@stop
