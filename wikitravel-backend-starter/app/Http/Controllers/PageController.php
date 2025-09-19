<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $q = Page::query()
            ->when($request->type, fn($x) => $x->where('type', $request->type))
            ->when($request->locale, fn($x) => $x->where('locale', $request->locale))
            ->when($request->q, function ($x) use ($request) {
                $x->where(function ($w) use ($request) {
                    $w->where('title', 'like', '%' . $request->q . '%')
                      ->orWhere('content', 'like', '%' . $request->q . '%');
                });
            })
            ->orderByDesc('updated_at');

        return $q->paginate($request->integer('per_page', 20));
    }

    public function show($id)
    {
        return Page::findOrFail($id);
    }

    public function showBySlug($slug)
    {
        return Page::where('slug', $slug)->firstOrFail();
    }

    public function store(StorePageRequest $request)
    {
        $data = $request->validated();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $data['created_by'] = $request->user()->id ?? null;
        $data['updated_by'] = $request->user()->id ?? null;
        if (($data['status'] ?? null) === 'published') {
            $data['published_at'] = now();
        }
        $page = Page::create($data);

        $page->revisions()->create([
            'editor_id' => $request->user()->id,
            'snapshot'  => $page->content ?? '',
            'summary'   => 'Створення',
        ]);

        return response()->json($page, 201);
    }

    public function update($id, UpdatePageRequest $request)
    {
        $page = Page::findOrFail($id);
        $data = $request->validated();

        if (isset($data['title']) && empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $data['updated_by'] = $request->user()->id ?? null;
        if (($data['status'] ?? null) === 'published' && !$page->published_at) {
            $data['published_at'] = now();
        }
        $page->update($data);

        $page->revisions()->create([
            'editor_id' => $request->user()->id,
            'snapshot'  => $page->content ?? '',
            'summary'   => $request->input('rev_summary', 'Оновлення'),
        ]);

        return response()->json($page);
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return response()->noContent();
    }

    public function revisions($id)
    {
        $page = Page::findOrFail($id);
        return $page->revisions()->latest()->get();
    }

    public function restore($id, $rev)
    {
        $page = Page::findOrFail($id);
        $revision = $page->revisions()->findOrFail($rev);
        $page->update(['content' => $revision->snapshot]);

        $page->revisions()->create([
            'editor_id' => auth()->id(),
            'snapshot'  => $page->content ?? '',
            'summary'   => 'Відновлено ревізію',
        ]);

        return response()->json($page);
    }

    public function search(Request $request)
    {
        $request->validate(['q' => 'required|string']);
        $request->merge(['page' => $request->page ?? 1]);
        return $this->index($request);
    }
}
