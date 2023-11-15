    <div class="mt-lg-5 mt-md-5 container mb-2 mt-2 bg-white py-4 shadow">
        <div class="border-bottom border-dark pb-2">
            <a
                class="ticket_create_btn btn btn-success"
                href="{{ route('tickets.index') }}"
                style="float:left;"
            >{{ __('words.all_tickets') }}<i class="fa fa-paper-plane mr-2"></i></a>
            <div
                class="text-bold text-dark mr-3"
                style="font-size: 25px;"
            >{{ __('words.support') }}</div>
            <div class="mr-3 mt-2">{{ __('message.ask_your_questions') }}</div>
        </div>
        <div class="mt-3 text-center">
            <table
                class="table-bordered table-striped table"
                style="font-size: 18px;"
            >
                <tr class="text-bold bg-info text-white">
                    <td>#{{ $this->ticket?->id }}</td>
                    <td>{{ $this->ticket?->subject }}</td>
                    <td class="ticket-td2">
                        {{ __('words.section') }}
                        {{ $this->ticket?->ticketCategory?->name }}
                    </td>
                    <td class="{{ $this->ticket?->status?->colorClass }}">
                        {{ $this->ticket?->status?->nameWithoutModelPrefix }}
                    </td>
                </tr>
            </table>

            @foreach ($this->ticket?->childTickets as $childTicket)
                @if ($childTicket->created_by_user_id == auth()->id())
                    <div
                        class="mt-2"
                        style="display: flex;"
                    >
                        <img
                            src="{{ $childTicket->createdByUser?->avatar }}"
                            style="border-radius: 50px;"
                            height="60"
                            width="60"
                        />
                        <div
                            class="chat-right mr-2 mt-2 px-2 py-2"
                            style="min-width: 240px;"
                        >
                            <div class="font-weight-bold mb-1">{{ __('words.you') }}</div>

                            {{ $childTicket->content }}
                            @if ($childTicket->media)
                                @foreach ($childTicket->media as $media)
                                    <a
                                        href="{{ $media->getUrl() }}"
                                        target="_blank"
                                    >
                                        {{ __('words.uploaded_file') }}
                                    </a>
                                @endforeach
                            @endif
                            <div
                                class="mt-2 text-left"
                                style="direction: ltr;color: #555;"
                            >
                                {{ $childTicket->updated_at }}
                            </div>
                        </div>

                    </div>
                @else
                    <div
                        class="mt-2"
                        style="display: flex;direction: rtl;"
                    >
                        <img
                            src="{{ asset('assets/img/menu/support.png') }}"
                            style="border-radius: 50px;"
                            height="60"
                            width="60"
                        />
                        <div
                            class="chat-left ml-2 px-2 py-2"
                            style="min-width: 240px;"
                        >
                            <div class="font-weight-bold mb-1">{{ __('words.support') }}</div>

                            {{ $childTicket->content }}
                            @if ($childTicket->media)
                                @foreach ($childTicket->media as $media)
                                    <a
                                        href="{{ $media->getUrl() }}"
                                        target="_blank"
                                    >
                                        {{ __('words.uploaded_file') }}
                                    </a>
                                @endforeach
                            @endif
                            <div
                                class="mt-2 text-left"
                                style="direction: ltr;color: #555;"
                            >
                                {{ $childTicket->updated_at }}
                            </div>
                        </div>
                    </div>
                @endif

            @endforeach

        </div>

        <div
            class="mt-3 pt-2 text-right"
            style="font-size: 16px;"
        >
            <form enctype="multipart/form-data">
                <div class="mr-2">
                    <i class="fa fa-envelope ml-2"></i>
                    {{ __('words.message_text') }} :
                </div>
                <textarea
                    class="form-control @error('form.content') is-invalid  @enderror input-group form-input mt-1 border bg-white px-2 py-1"
                    style="height: 120px;line-height: 30px;border-radius: 10px;"
                    wire:model="form.content"
                    maxlength="1000"
                    required
                ></textarea>
                @error('form.content')
                    <div class="text-danger">
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <div class="form-sm-input">
                    <div class="mr-2 mt-2">
                        <i class="fa fa-file-alt ml-2"></i>
                        {{ __('words.file_upload') }} :
                    </div>
                    <input
                        class="form-control @error('form.file') is-invalid  @enderror input-group form-input border bg-white"
                        type="file"
                        style="border-radius: 10px;"
                        wire:model="form.file"
                        accept="file_extension|audio/*|video/*|image/*"
                    >
                    @error('form.file')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>

                <button
                    class="btn btn-success form-group mt-2 py-2"
                    type="button"
                    style="font-size: 16px;border-radius: 10px;"
                    wire:click='submitForm'
                >
                    {{ __('words.send_ticket') }}
                    <i class="fa fa-paper-plane mr-2"></i>
                </button>
            </form>
        </div>
    </div>
