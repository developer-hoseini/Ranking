<div>
    <div class="border-bottom border-dark pb-2">
        <butten
            class="ticket_create_btn btn btn-success cursor-pointer"
            style="float:left;"
            wire:click='toggleForm'
        >
            {{ __('words.new_ticket') }}
            <i class="fa fa-paper-plane mr-2"></i>
        </butten>
        <div
            class="text-bold text-dark mr-3"
            style="font-size: 25px;"
        >
            {{ __('words.support') }}
        </div>
        <div class="mr-3 mt-2">
            {{ __('message.ask_your_questions') }}
        </div>
    </div>
    <div
        class="border-bottom border-dark px-lg-5 px-md-3 px-2 py-2"
        id="ticket_create_div"
        style="font-size: 18px;background-color: #f2f2f2;"
    >
        @if ($showForm)
            <form>
                @csrf
                <div class="form-sm-input">
                    <div class="mr-2 mt-2">
                        <i class="fa fa-align-right ml-2"></i>
                        {{ __('words.title') }} :
                    </div>
                    <input
                        class="form-control @error('form.subject') is-invalid  @enderror input-group form-input mt-1 border bg-white px-2 py-2"
                        type="text"
                        style="border-radius: 10px;"
                        wire:model="form.subject"
                        maxlength="100"
                        required
                    >
                    @error('form.subject')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
                <div
                    class="form-sm-input mb-2"
                    style="margin-left: 0%;"
                >
                    <div class="mr-2 mt-2">
                        <i class="fa fa-user ml-2"></i>
                        {{ __('words.support_section') }} :
                    </div>
                    <select
                        class="form-control @error('form.ticket_category_id') is-invalid  @enderror input-group form-input mt-1 border bg-white px-2 py-2"
                        style="border-radius: 10px;"
                        wire:model="form.ticket_category_id"
                    >
                        <option value=""></option>
                        @foreach ($ticketCategories as $ticketCategory)
                            <option value="{{ $ticketCategory['id'] ?? '' }}">
                                {{ $ticketCategory['name'] ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('form.ticket_category_id')
                        <div class="text-danger">
                            <span>{{ $message }}</span>
                        </div>
                    @enderror
                </div>
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
        @endif
    </div>

</div>
