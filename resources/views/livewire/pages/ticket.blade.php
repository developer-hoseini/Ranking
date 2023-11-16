<div class="mt-lg-5 mt-md-5 container mb-2 mt-2 bg-white py-4 shadow">

    <livewire:pages.ticket.create>

        <div class="mt-3 overflow-auto text-center">
            <table
                class="table-bordered table-striped table"
                style="font-size: 18px;"
            >
                <thead>
                    <tr class="text-bold bg-info text-white">
                        <td>{{ __('words.ticket_id') }}</td>
                        <td>{{ __('words.title') }}</td>
                        <td class="ticket-td2">{{ __('words.part') }}</td>
                        <td>{{ __('words.status') }}</td>
                        <td class="ticket-td4">{{ __('words.last_update') }}</td>
                    </tr>
                </thead>
                @forelse ($this->tickets as $ticket)
                    <tr>
                        <td class="text-truncate">#{{ $ticket->id }}</td>
                        <td
                            class="text-truncate"
                            title="{{ $ticket->subject }}"
                            style="max-width: 150px;"
                        >
                            <a href="{{ route('tickets.show', $ticket->id) }}">
                                {{ $ticket->subject }}
                            </a>
                        </td>
                        <td class="text-truncate">{{ $ticket->ticketCategory?->name }}</td>
                        <td class="text-truncate {{ $ticket->status?->colorClass }}">
                            {{ $ticket->status?->nameWithoutModelPrefix }}
                        </td>
                        <td class="ticket-td4">
                            {{ $ticket->updated_at }}
                        </td>
                        {{-- TODO: add read and unread ticket --}}
                        {{-- @if ($ticket->status == $status['Readed'] || $ticket->status == $status['Pending_Answer']) --}}

                    </tr>
                @empty
                    {{ __('message.you_dont_created_tickets') }} ...
                @endforelse

            </table>
            <div class="pagination-links">
                {{ $this->tickets->links() }}
            </div>

        </div>
</div>
