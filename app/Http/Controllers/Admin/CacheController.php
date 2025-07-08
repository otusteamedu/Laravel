<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Cache\CacheWarmupService;
use App\Services\Cache\CacheServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CacheController extends Controller
{
    public function __construct(
        private CacheWarmupService $warmupService,
        private CacheServiceInterface $cacheService
    ) {
    }

    /**
     * Страница управления кэшем
     */
    public function index(): View
    {
        $this->authorize('admin-access');
        
        return view('admin.cache.index');
    }

    /**
     * Прогрев кэша
     */
    public function warmup(Request $request): RedirectResponse
    {
        $this->authorize('admin-access');
        
        try {
            $this->warmupService->warmupAll();
            
            return back()->with('success', 'Кэш успешно прогрет!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при прогреве кэша: ' . $e->getMessage());
        }
    }

    /**
     * Очистка кэша
     */
    public function clear(Request $request): RedirectResponse
    {
        $this->authorize('admin-access');
        
        try {
            $this->cacheService->flush();
            
            return back()->with('success', 'Кэш успешно очищен!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при очистке кэша: ' . $e->getMessage());
        }
    }

    /**
     * Прогрев кэша задач
     */
    public function warmupTasks(Request $request): RedirectResponse
    {
        $this->authorize('admin-access');
        
        try {
            $this->warmupService->warmupTasks();
            
            return back()->with('success', 'Кэш задач успешно прогрет!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при прогреве кэша задач: ' . $e->getMessage());
        }
    }

    /**
     * Прогрев кэша категорий
     */
    public function warmupCategories(Request $request): RedirectResponse
    {
        $this->authorize('admin-access');
        
        try {
            $this->warmupService->warmupCategories();
            
            return back()->with('success', 'Кэш категорий успешно прогрет!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при прогреве кэша категорий: ' . $e->getMessage());
        }
    }

    /**
     * Прогрев кэша пользователей
     */
    public function warmupUsers(Request $request): RedirectResponse
    {
        $this->authorize('admin-access');
        
        try {
            $this->warmupService->warmupUsers();
            
            return back()->with('success', 'Кэш пользователей успешно прогрет!');
        } catch (\Exception $e) {
            return back()->with('error', 'Ошибка при прогреве кэша пользователей: ' . $e->getMessage());
        }
    }
} 