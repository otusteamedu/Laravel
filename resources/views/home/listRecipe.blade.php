@if(empty($recipes))
    <div class="alert alert-warning">Ничего не найдено по вашим продуктам</div>
@else
    <div class="row">
        @foreach($recipes as $recipe)
            <div class="col-md-6">
                @include('home.recipe', ['recipe' => $recipe])
            </div>
        @endforeach
    </div>
@endif
