<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    // ПУБЛІЧНЕ: список
    public function index()
    {
        return Page::select('id','title','slug','created_at','updated_at')->latest()->get();
    }

    // ПУБЛІЧНЕ: по id
    public function show(int $id)
    {
        return Page::findOrFail($id);
    }

    // ПУБЛІЧНЕ: по slug
    public function showBySlug(string $slug)
    {
        return Page::where('slug', $slug)->firstOrFail();
    }

    // ПРИВАТНЕ: створення
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'   => ['required','string','max:255'],
            'slug'    => ['required','string','max:255','alpha_dash', 'unique:pages,slug'],
            'content' => ['nullable','string'],
        ]);

        $page = Page::create($data);

        return response()->json($page, 201);
    }

    // ПРИВАТНЕ: оновлення
    public function update(Request $request, int $id)
    {
        $page = Page::findOrFail($id);

        $data = $request->validate([
            'title'   => ['sometimes','string','max:255'],
            'slug'    => ['sometimes','string','max:255','alpha_dash', Rule::unique('pages','slug')->ignore($page->id)],
            'content' => ['nullable','string'],
        ]);

        $page->update($data);
        return response()->json($page);
    }

    // ПРИВАТНЕ: видалення
    public function destroy(int $id)
    {
        Page::findOrFail($id)->delete();
        return response()->noContent();
    }

    // ПРИВАТНЕ: ревізії (заглушка)
    public function revisions(int $id)
    {
        return response()->json(['revisions' => []]);
    }

    // Пошук (опційно)
    public function search(Request $request)
    {
        $q = $request->query('q');
        return Page::where('title', 'like', "%$q%")
            ->orWhere('content', 'like', "%$q%")
            ->get();
    }
}
