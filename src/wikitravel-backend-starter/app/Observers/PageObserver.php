<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Auth;

class PageObserver
{
    public function updated(Page $page): void
    {
        if ($page->wasChanged('content')) {
            $page->revisions()->create([
                'editor_id' => Auth::id(),
                'snapshot'  => $page->content ?? '',
                'summary'   => 'Авто ревізія (зміна контенту)',
            ]);
        }
    }
}
