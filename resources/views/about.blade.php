@extends('layouts.app')

@section('title', 'О сайте')

@section('content')
    <h1>О нашем проекте</h1>

    <p>Этот сайт был создан как демонстрация возможностей Laravel и Bootstrap. Мы стремимся к простоте, чистому интерфейсу и удобству использования.</p>

    <h3 class="mt-4">Что мы используем?</h3>
    <ul>
        <li><strong>Laravel</strong> — современный PHP-фреймворк для создания мощных веб-приложений</li>
        <li><strong>Blade</strong> — шаблонизатор Laravel, позволяющий легко управлять интерфейсами</li>
        <li><strong>Bootstrap 5</strong> — фреймворк CSS для стилизации без лишней возни</li>
    </ul>

    <h3 class="mt-4">Контакты</h3>
    <p>По вопросам пишите на <a href="mailto:support@example.com">support@example.com</a></p>
@endsection
