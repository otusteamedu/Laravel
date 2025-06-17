<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Services\Team\TeamCreateService;
use App\Services\Team\TeamData;
use App\Services\Team\TeamDestroyService;
use App\Services\Team\TeamsViewService;
use App\Services\Team\TeamUpdateService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        TeamsViewService $teamsViewService,
    ): View
    {
        $data['teams'] = $teamsViewService->fetchAll();
        return view('teams.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('team.create');
        return view('teams.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        TeamRequest $request,
        TeamCreateService $teamCreateService,
    ): RedirectResponse
    {
        try {
            $data = $request->validated();
            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('teams', 'public');
                $data['logo_path'] = $path;
            }

            $teamCreateService->handle(new TeamData($data));
        } catch (Throwable $e) {
            return  redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('teams.index')->with('status', 'team-created');
    }

    /**
     * Display the specified resource.
     */
    public function show(
        int $id,
        TeamsViewService $teamsViewService,
    ): View
    {
        $data['team'] = $teamsViewService->fetchOne($id);

        if (is_null($data['team'])) {
            abort(404);
        }

        return view('teams.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(
        int $id,
        TeamsViewService $teamsViewService,
    ): View
    {
        Gate::authorize('team.update', $id);
        $data['team'] = $teamsViewService->fetchOne($id);

        if (is_null($data['team'])) {
            abort(404);
        }

        return view('teams.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        int $id,
        TeamRequest $request,
        TeamUpdateService $teamUpdateService,
    ): RedirectResponse
    {
        try {
            $data = $request->validated();
            $data['id'] = $id;

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('teams', 'public');
                $data['logo_path'] = $path;
            }

            $oldLogoPath = $teamUpdateService->handle(new TeamData($data));
            if($oldLogoPath && Storage::disk('public'))
            {
                Storage::disk('public')->delete($oldLogoPath);
            }

        } catch (Throwable $e) {
            return  redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }

        return redirect()->route('teams.index')->with('status', 'team-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        int $id,
        TeamDestroyService $teamDestroyService,
    ): RedirectResponse
    {
        Gate::authorize('team.delete', $id);
        try {
            $teamDestroyService->handle($id);
        } catch (Throwable $e) {
            return  redirect()->route('teams.index')->with('status', 'team-not-deleted')->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->route('teams.index')->with('status', 'team-deleted');
    }
}
