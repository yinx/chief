@if($errors and count($errors) > 0)
    <alert class="alert --raised fixed--bottom-right inset-s animated animation-delayed-4 fadeOutDown" type="error">
        <div v-cloak>
            @foreach($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    </alert>
@endif