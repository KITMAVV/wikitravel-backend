<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePageRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'type' => 'sometimes|string|max:32',
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:pages,slug,' . $id,
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'locale' => 'nullable|string|max:5',
            'status' => 'nullable|in:draft,published,archived',
            'tags' => 'nullable|array',
        ];
    }
}
