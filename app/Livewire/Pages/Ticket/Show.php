<?php

namespace App\Livewire\Pages\Ticket;

use App\Enums\StatusEnum;
use App\Models\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Show extends Component
{
    use WithFileUploads;

    public $ticketId;

    #[Rule([
        'form.content' => 'required|string|min:3|max:65535',
        'form.file' => 'nullable|file|max:30000',
    ])]
    public ?array $form = [
        'content' => null,
        'file' => null,
    ];

    public function mount($id)
    {
        $this->ticketId = $id;

    }

    #[Computed]
    public function ticket()
    {
        return Ticket::where('id', $this->ticketId)
            ->authCreatedScope()
            ->with([
                'childTickets.createdByUser.media',
                'media',
                'ticketCategory:id,name',
                'ticketStatus:id,name',
            ])
            ->firstOrFail();
    }

    public function submitForm()
    {
        $this->validate();

        $pendingStatusId = Status::nameScope(StatusEnum::TICKET_PENDING->value)->first()?->id;

        DB::beginTransaction();

        try {
            $replyTicket = Ticket::create([
                'content' => $this->form['content'],
                'ticket_category_id' => $this->ticket->ticket_category_id,
                'status_id' => $pendingStatusId,
                'ticket_parent_id' => $this->ticket->id,
                'created_by_user_id' => auth()->id(),
            ]);

            $this->ticket->update([
                'status_id' => $pendingStatusId,
            ]);

            if (isset($this->form['file'])) {
                $replyTicket->addMedia($this->form['file']?->getRealPath())
                    ->toMediaCollection('files');
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $this->reset();

        session()->flash('success', 'Your reply has been successfully sent and is awaiting a reply ...        ');

        $this->redirect(route('tickets.show', $this->ticket->id));

    }

    public function render()
    {
        return view('livewire.pages.ticket.show');
    }
}
