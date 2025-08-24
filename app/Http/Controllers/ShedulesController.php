<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSheduleRequest;
use App\Models\Shedule;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ShedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $shedules = Shedule::all();

        return view('shedules.index', compact('shedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('shedules.edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        Request $request,
        \Illuminate\Contracts\Validation\Factory $validationFactory,
        \Illuminate\Contracts\Auth\Factory $auth
    ): RedirectResponse {
        $validator = $validationFactory->make(request()->all(), [
            'language' => ['required', 'min:5'],
            'group' => ['required', 'min:5'],
            'date' => ['required', 'min:5'],
            'teacher' => ['required', 'min:5'],
        ]);

        try {
            $validator->validate();
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $shedule = new Shedule;
        $shedule->language = $request->input('language');
        $shedule->group = $request->input('group');
        $shedule->date = $request->input('date');
        $shedule->teacher = $request->input('teacher');
        $shedule->author_id = $request->input('author_id');

        $shedule->save();

        return redirect()
            ->route('shedules.index')
            ->with('success', 'Запись успешно создана');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shedule $shedule, Factory $viewFactory)
    {
        return $viewFactory->make('shedules.show', compact('shedule'));
    }

    /**
     * - Здесь не передаем модель, а только примитивные данные, которые необходимы для шаблона
     * - Не используем compact()
     */
    public function edit(Shedule $shedule, Gate $gate): View
    {
        /*
        if (! $gate->allows('shedules.update', $shedule)) {
            abort(403, 'You are not allowed to edit this item');
        } */

        return view('shedules.edit', [
            'sheduleId' => $shedule->id,
            'language' => $shedule->language,
            'group' => $shedule->group,
            'date' => $shedule->date,
            'teacher' => $shedule->teacher,

        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSheduleRequest $request, Shedule $shedule)
    {
        $requestData = $request->validated();

        $shedule->language = $requestData['language'];
        $shedule->group = $requestData['group'];
        $shedule->date = $requestData['date'];
        $shedule->teacher = $requestData['teacher'];
        $shedule->save();

        return redirect()->route('shedules.show', ['shedule' => $shedule]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shedule $shedule)
    {
        //
    }
}
