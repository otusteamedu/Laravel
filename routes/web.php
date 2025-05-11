<?php

use Illuminate\Support\Facades\Route;


Route::view('/', view: 'home.index', data: [
    'articles' => [
        [
            'title' => 'News Title 1',
            'date' => '2019-02-12',
            'description' => 'This is a brief description of the first news item.',
        ],
        [
            'title' => 'News Title 2',
            'date' => '2019-03-12',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias, cumque distinctio dolorem ducimus explicabo fuga itaque maxime quisquam sunt voluptates! At labore nostrum voluptates voluptatibus? Accusantium ad aspernatur cupiditate dolore doloribus enim eum facere impedit ipsam laudantium nulla numquam, odit officia perspiciatis placeat quam quisquam reiciendis sapiente vitae voluptas. Illum!',
        ],
        [
            'title' => 'News Title 3',
            'date' => '2019-02-12',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus ad atque aut consequuntur, debitis deserunt eaque earum eius fugit in iusto laudantium libero maxime molestiae molestias neque pariatur, sunt. Amet architecto commodi, cupiditate deserunt dolore dolores eaque fuga inventore iure iusto necessitatibus neque non nulla officia perferendis quae quod recusandae repellat sunt ut, vitae, voluptatibus?',
        ],
        [
            'title' => 'News Title 4',
            'date' => '2019-02-12',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus aliquid assumenda aut blanditiis consequatur debitis dolor eius eveniet excepturi explicabo inventore laborum magnam minima molestiae neque nostrum nulla omnis placeat quam qui quod, rerum, sequi tempora. Accusantium aliquid doloremque eligendi ex fugiat fugit harum iure nemo nesciunt numquam, optio placeat quod similique suscipit veniam. Dolor dolore magnam modi sequi.',
        ],
        [
            'title' => 'News Title 5',
            'date' => '2019-02-12',
            'description' => 'This is a brief description of the first news item.',
        ],
        [
            'title' => 'News Title 6',
            'date' => '2019-02-12',
            'description' => 'This is a brief description of the first news item.',
        ],
        [
            'title' => 'News Title 7',
            'date' => '2019-02-12',
            'description' => 'This is a brief description of the first news item.',
        ],
        [
            'title' => 'News Title 8',
            'date' => '',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias cupiditate dignissimos enim sapiente. Animi est odit quibusdam reprehenderit sed similique voluptatibus! Dolores eveniet iusto quae quam qui. Ab, illum placeat?',
        ],
        [
            'title' => 'News Title 9',
            'date' => '2019-02-12',
            'description' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Alias cupiditate dignissimos enim sapiente. Animi est odit quibusdam reprehenderit sed similique voluptatibus! Dolores eveniet iusto quae quam qui. Ab, illum placeat?',
        ],
    ],
    'sales' => true
]);

Route::view('/registration', view: 'auth.registration')->name('registration');
Route::view('/profile', view: 'user.profile')->name('profile');
Route::view('/about', view: 'about')->name('about');
