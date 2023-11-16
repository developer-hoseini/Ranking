<?php

namespace App\Livewire\Pages;

use App\Models\Ticket as ModelsTicket;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

class Ticket extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Computed]
    public function tickets()
    {
        $tickets = ModelsTicket::query()
            ->authCreatedScope()
            ->whereNull('ticket_parent_id')
            ->with(['ticketCategory:id,name'])
            ->latest()
            ->paginate(config('ranking.settings.global.per_page'));

        return $tickets;
    }

    public function render()
    {
        return view('livewire.pages.ticket');
    }
}
