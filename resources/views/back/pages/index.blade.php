@extends('chief::back._layouts.master')

@section('page-title', "Pagina's")

@component('chief::back._layouts._partials.header')
    @slot('title', "Jouw pagina's")
        <div class="inline-group-s">
            <a href="{{ route('chief.back.pages.create') }}" class="btn btn-primary">
                <i class="icon icon-plus"></i>
                Voeg een pagina toe
            </a>
        </div>
    @endcomponent

    @section('content')
        <tabs v-cloak>
            <tab name="Drafts ({{$drafts->count()}})" id="drafts">
                @foreach($drafts as $page)
                    <div class="row">
                        <div class="column-8 stretched">
                            <div class="column-12">
                                <h2>
                                    {{ $page->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $page->title }}" href="{{ route('demo.pages.show', $page->slug) }}?preview-mode" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                            </div>
                            <div class="column-12">
                                {{ teaser($page->content,150,'...') }}
                            </div>
                            <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="center-y column">
                            <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="btn btn-link text-font">Aanpassen</a>
                            <form action="{{ route('chief.back.pages.publish') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="publishActions-{{$page->id}} hidden">
                                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                                    <input type="hidden" name="id" value="{{ $page->id }}">
                                    <button type="submit" class="btn btn-subtle">Publiceer</button>
                                </div>
                            </form>
                            <a @click="showModal('delete-page-{{$page->id}}')" class="btn btn-link"><i class="icon icon-trash icon-fw"></i></a>
                        </div>
                    </div>
                    <hr>
                @include('back.pages._partials.delete-modal')
                @endforeach
                @if($drafts->isEmpty())
                    <div class="row">
                        <div class="column-12 stack">
                            Er zijn géén pagina's in draft.
                        </div>

                        <div class="column-12">
                            <a href="{{ route('chief.back.pages.create') }}" class="btn btn-primary">
                                <i class="icon icon-zap icon-fw"></i> Tijd om aan de slag te gaan
                            </a>
                        </div>
                    </div>
                @endif
            </tab>
            <tab name="Published ({{ $published->count() }})" id="published">
                @foreach($published as $page)
                    <div class="row">
                        <div class="column-8 stretched">
                            <div class="column-12">
                                <h2>
                                    {{ $page->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $page->title }}" href="{{ route('chief.back.pages.show',$page->slug) }}?preview-mode=true" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                            </div>
                            <div class="column-12">
                                {{ teaser($page->content,150,'...') }}
                            </div>
                            <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="center-y column">
                            <a href="{{ route('chief.back.pages.edit',$page->getKey()) }}" class="btn btn-link text-font">Aanpassen</a>
                            <form action="{{ route('chief.back.pages.publish') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="publishActions-{{$page->id}} hidden">
                                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                                    <input type="hidden" name="id" value="{{ $page->id }}">
                                    <button type="submit" class="btn btn-icon btn-subtle">Plaats in draft</button>
                                </div>
                            </form>
                            <a @click="showModal('delete-page-{{$page->id}}')" class="btn btn-link"><i class="icon icon-trash icon-fw"></i></a>
                        </div>
                    </div>
                </div>
                <hr>
            @include('back.pages._partials.delete-modal')
            @endforeach
            @if($published->isEmpty())
                <div class="row left">
                    <div class="column-12 stack">
                        Er staan géén pagina's online.</p>
                    </div>

                    <div class="column-12">
                        <a href="{{ route('back.pages.create') }}" class="btn btn-primary">
                            <i class="icon icon-zap icon-fw"></i> Tijd om aan de slag te gaan
                        </a>
                    </div>
                </div>
            @endif
            <div class="text-center">
                {!! $published->render() !!}
            </div>
        </tab>
        <tab name="Archief ({{ $archived->count() }})" id="archief">
            @foreach($archived as $page)
                    <div class="row">
                        <div class="column-8 stretched">
                            <div class="column-12">
                                <h2>
                                    {{ $page->getTranslationFor('title') }}
                                    <a title="Bekijk {{ $page->title }}" href="{{ route('demo.pages.show',$page->slug) }}?preview-mode=true" target="_blank" class="text-subtle font-s">Preview</a>
                                </h2>
                            </div>
                            <div class="column-12">
                                {{ teaser($page->content,150,'...') }}
                            </div>
                            <span class="text-subtle">Laatst aangepast op: {{ $page->updated_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="center-y column">
                            <a href="{{ route('back.pages.edit',$page->getKey()) }}" class="btn btn-link text-font">Aanpassen</a>
                            <form action="{{ route('back.pages.publish') }}" method="POST">
                                {{ csrf_field() }}
                                <div class="publishActions-{{$page->id}} hidden">
                                    <input type="hidden" name="checkboxStatus" value="{{ $page->isPublished() }}">
                                    <input type="hidden" name="id" value="{{ $page->id }}">
                                    <button type="submit" class="btn btn-icon btn-subtle">Plaats in draft</button>
                                </div>
                            </form>
                            <a @click="showModal('delete-page-{{$page->id}}')" class="btn btn-link"><i class="icon icon-trash icon-fw"></i></a>
                        </div>
                    </div>
                </div>
                <hr>
            @include('back.pages._partials.delete-modal')
            @endforeach
            @if($archived->isEmpty())
                <div class="row left">
                    <div class="column-12 stack">
                        Er staan géén pagina's in het archief.</p>
                    </div>

                <div class="column-12">
                    <a href="{{ route('chief.back.pages.create') }}" class="btn btn-primary">
                        <i class="icon icon-zap icon-fw"></i> Tijd om aan de slag te gaan
                    </a>
                </div>
            @endif
            <div class="text-center">
                {!! $archived->render() !!}
            </div>
        </tab>
    </tabs>
@stop

@push('custom-scripts')
    <script>
    // SHOW OR HIDE PUBLISH BUTTON
    $("[class^='showPublishOptions-'], [class*='showPublishOptions-']").click(function(){
        var id = this.dataset.publishId;
        $('.publishActions-'+id).removeClass('--hidden');
        $('.showPublishOptions-'+id).addClass('--hidden');
    });
    $('.noPublish').click(function(){
        var id = this.dataset.publishId;
        $('.publishActions-'+id).addClass('--hidden');
        $('.showPublishOptions-'+id).removeClass('--hidden');
    });
</script>
@endpush