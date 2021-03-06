<h2>Eigen modules</h2>
<p>Hier vind je de verzameling van alle modules (blokken) die specifiek zijn voor deze pagina. Je kan deze op de pagina tonen door
    ze te selecteren in de <a href="#inhoud">inhoudstab</a></p>

@if($page->modules->isEmpty())
    <div class="center-center stack-xl">
        <div>
            <a @click="showModal('create-module')" class="btn btn-primary squished">
            <i class="icon icon-zap icon-fw"></i> Voeg een eerste module toe specifiek voor deze pagina.
            </a>
        </div>
    </div>
@endif

@if(!$page->modules->isEmpty())
    @foreach($page->modules as $module)
        @include('chief::back.modules._partials._rowitem')
    @endforeach

    <div class="stack">
        <a @click="showModal('create-module')" class="btn btn-primary">
        <i class="icon icon-plus"></i>
        Voeg een module toe
        </a>
    </div>
@endif