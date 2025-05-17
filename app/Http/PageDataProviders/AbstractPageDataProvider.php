<?php

namespace App\Http\PageDataProviders;

use Illuminate\Http\Request;

abstract class AbstractPageDataProvider
{
    public function __construct(protected Request $request)
    {
        //
    }

    protected function generateH1(): string
    {
        return '';
    }

    protected function generateTitle(): string
    {
        return '';
    }

    protected function generateDescription(): string
    {
        return '';
    }

    public function h1()
    {
        return 'ToDo';
    }

    public function title(): string
    {
        return 'ToDo: список дел для организации работы и жизни';
    }

    public function description(): string
    {
        return 'Таск-менеджер и приложение для ведения списка дел. Обретите сосредоточенность, организованность и спокойствие.';
    }
}
