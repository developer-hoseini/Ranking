<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Notification extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function notifications()
    {
        $authUser = \Auth::user();

        $notifications = $authUser->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(config('ranking.settings.global.per_page'));

        return $notifications;
    }

    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.pages.notification');
    }
}
