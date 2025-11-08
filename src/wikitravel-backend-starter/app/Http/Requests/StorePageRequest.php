<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'type' => 'required|string|max:32',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'locale' => 'nullable|string|max:5',
            'status' => 'nullable|in:draft,published,archived',
            'tags' => 'nullable|array',
        ];
    }
}
