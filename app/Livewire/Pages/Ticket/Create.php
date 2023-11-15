<?php

namespace App\Livewire\Pages\Ticket;

use App\Enums\StatusEnum;
use App\Models\Status;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $showForm = false;

    public $ticketCategories = [];

    #[Rule([
        'form.subject' => 'required|string|min:3|max:255',
        'form.content' => 'required|string|min:3|max:65535',
        'form.ticket_category_id' => 'required|exists:ticket_categories,id',
        'form.file' => 'nullable|file|max:30000',
    ])]
    public ?array $form = [
        'subject' => null,
        'content' => null,
        'ticket_category_id' => null,
        'file' => null,
    ];

    public function mount()
    {
        $this->ticketCategories = TicketCategory::select(['id', 'name'])->get()->toArray();
    }

    public function toggleForm()
    {
        $this->showForm = ! $this->showForm;
    }

    public function submitForm()
    {
        $this->validate();

        $ticket = Ticket::create([
            'subject' => $this->form['subject'],
            'content' => $this->form['content'],
            'ticket_category_id' => $this->form['ticket_category_id'],
            'status_id' => Status::nameScope(StatusEnum::TICKET_PENDING->value)->first()?->id,
            'created_by_user_id' => auth()->id(),
        ]);

        if (isset($this->form['file'])) {
            $ticket->addMedia($this->form['file']?->getRealPath())
                ->toMediaCollection('files');
        }

        $this->reset();

        session()->flash('success', 'Your ticket created successfully');

        $this->redirect(route('tickets.index'));

    }

    public function render()
    {
        return view('livewire.pages.ticket.create');
    }
}
