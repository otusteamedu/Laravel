@extends('layouts.main')

@section('content')
    <section class="w-full">
        <img src="/images/banner.jpg" alt="Баннер" class="w-full h-auto object-cover">
    </section>
    <main class="container mx-auto py-8 flex-grow flex flex-col md:flex-row gap-8 px-6">
        <section class="w-full">
            <h1 class="text-2xl font-semibold mb-4">Это главная</h1>
            <p class="mb-4">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium commodi dolores, eius necessitatibus numquam officia vitae. Atque exercitationem itaque molestiae sit! Corporis dolores itaque, nobis non vero vitae. Architecto consequatur dolor inventore laboriosam odit, officiis possimus quis. Alias dignissimos dolor illum impedit iusto laboriosam, maiores maxime perspiciatis quas quasi repellat rerum saepe, velit! Aspernatur corporis doloribus fuga libero voluptatum! Architecto dolore eius explicabo illo illum nemo nobis nostrum omnis perspiciatis, possimus praesentium quas quidem rem sit, unde, vel vero? Aperiam architecto at, culpa, delectus error est explicabo illum in quae quibusdam ratione repellendus, rerum saepe unde veniam. Alias amet corporis dolores ea earum, error harum modi mollitia nisi nulla odio ratione sapiente! Ab accusamus architecto dignissimos earum, eius error fugit in, iste iure laudantium minima mollitia nihil nulla optio porro quidem quisquam quod ratione repellendus reprehenderit repudiandae temporibus tenetur voluptatem? Distinctio dolore dolores doloribus eligendi facilis id illum laboriosam, molestiae non nulla odio quasi quo repudiandae sed soluta sunt temporibus? Adipisci alias aliquam aliquid animi asperiores assumenda atque corporis dolore doloremque eveniet exercitationem harum hic id illo iure iusto laborum maiores minus nam, nemo officia officiis omnis perspiciatis quasi quisquam quod rem reprehenderit sed sequi ullam velit voluptas voluptate voluptatibus!
            </p>
            @if ($sales)
            <section class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-8" role="alert">
                <strong class="font-bold">Special Announcement!</strong>
                <span class="block sm:inline">  Check out our new products.</span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-yellow-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z" /></svg>
                </span>
            </section>
            @endif

            {{-- @dump($articles) --}}
            <section class="mb-8">
                <h3 class="text-xl font-semibold mb-4">@lang('index.last_news')</h3>
                <div class="news grid lg:grid-cols-3 gap-4">

                    @foreach ($articles as $article)
                        @continue(!$article['date'])

                        @php
                            $isFull = $loop->iteration%4 == 0;
                        @endphp


                    <article
                        @class([
                            'news-item',
                            'lg:col-span-3' => $isFull,
                            ])>
                        <h4>{{$article['title']}}</h4>
                        <p class="text-gray-500 text-sm mb-1">{{$article['date']}}</p>
                        <p>{{$article['description']}}</p>
                        <a href="#" class="text-blue-500 hover:text-blue-700 transition-colors pl-0">@lang('index.read_more')</a>
                    </article>
                    @endforeach

                </div>
            </section>


        </section>

    </main>

    @include('blocks.form')
@endsection
