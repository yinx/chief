<div class="tab-block">

    <div class="loading" v-show="false">
        loading...
    </div>

    <tabs v-cloak>
        @foreach([
            'nl',
            'fr',
        ] as $locale)


            

            <tab class="row clearfix gutter" name="{{ $locale }}">

                <div class="column translation-sidemenu">
                    @if(count($groupedLines) > 2)
                        <ul class="sticky" style="top:9rem;">
                            <?php $id = 1 ?>
                            @foreach($groupedLines as $group => $lines)
                                @if($group != 'general')
                                    <li>
                                        <a class="anchor-item squished" href="#section{{ $id++ }}-{{ $locale }}" >{{ $group }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="column-9">
                    <div class="tab-content">
                        <?php $id = 1 ?>
                        @foreach($groupedLines as $group => $lines)

                            @if($group != 'general')
                                <div id="section{{ $id++ }}-{{ $locale }}" class="section-divider">
                                    <span>{{ $group }}</span>
                                    <span class="divider-locale font-s">{{ $locale }}</span>
                                </div>
                            @endif

                            @foreach($lines as $line)
                                @include('squanto::_form',['locale' => $locale])
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </tab>
        @endforeach
    </tabs>
    <!--end tab content -->
</div>
<!-- end tab block -->
