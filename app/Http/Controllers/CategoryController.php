<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        return view('saharituwaiq.categories', [
            'categories' => $categories,
            'prefill' => $request->input('prefill', ''),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'names' => ['required', 'string'],
        ]);

        $names = collect(preg_split('/\r\n|\r|\n/', $validated['names']))
            ->map(fn ($name) => trim($name))
            ->filter()
            ->unique(fn ($name) => mb_strtolower($name))
            ->values();

        $existing = Category::pluck('name')->map(fn ($name) => mb_strtolower($name));

        $toCreate = $names->reject(fn ($name) => $existing->contains(mb_strtolower($name)));

        foreach ($toCreate as $name) {
            Category::create(['name' => $name]);
        }

        $skipped = $names->count() - $toCreate->count();

        $message = 'تمت إضافة '.$toCreate->count().' فئة.';
        if ($skipped > 0) {
            $message .= ' تم تجاهل '.$skipped.' فئة موجودة مسبقاً.';
        }

        return redirect()
            ->route('saharituwaiq.categories.index')
            ->with('success', $message);
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();

        return redirect()
            ->route('saharituwaiq.categories.index')
            ->with('success', 'تم حذف الفئة.');
    }

    public function json(): JsonResponse
    {
        return response()->json(Category::orderBy('name')->pluck('name'));
    }
}
