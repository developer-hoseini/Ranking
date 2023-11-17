<?php

namespace App\Livewire\Pages\Profile;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Models\Country;
use App\Models\Profile;
use App\Models\State;
use App\Services\Actions\User\Achievement\ReceiveCoin;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule as ValidationRule;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompleteProfile extends Component
{
    use WithFileUploads;

    #[Rule([
        'accountInformation.fname' => 'required',
        'accountInformation.lname' => 'required',
        'accountInformation.mobile' => 'nullable',
        'accountInformation.avatar_name' => 'required',
        'accountInformation.avatar' => 'image|max:1024',
    ])]
    public $accountInformation = [
        'fname' => '',
        'lname' => '',
        'mobile' => '',
        'avatar_name' => '',
        'avatar' => '',
    ];

    public $locationInformation = [
        'country_id' => '',
        'state_id' => '',
        'gender' => '',
        'bio' => '',
        'birth_date' => '',
    ];

    public $step = 'account';

    public $countries = [];

    public $states = [];

    public $gender = [];

    public function mount()
    {
        $authUser = auth()->user();
        $accountInformation = $authUser->profile?->only(['fname', 'lname', 'mobile', 'avatar_name']) ?? [];
        $locationInformation = $authUser->profile?->only(['state_id', 'gender', 'bio', 'birth_date']) ?? [];

        $this->accountInformation = [...$accountInformation];
        $this->locationInformation = [
            ...$locationInformation,
            'country_id' => $authUser?->profile?->state?->country_id,
            'birth_date' => $authUser?->profile?->birth_date?->toDateString(),
        ];

        $this->countries = Country::select(['id', 'name'])->get()->toArray();

        if ($authUser?->profile?->state_id) {
            $this->states = $this->getStates();
        }

        $this->gender = GenderEnum::getSelectBoxTransformItems()->toArray();
    }

    public function updatedLocationInformationCountryId()
    {
        $this->locationInformation['state_id'] = '';
        $this->states = $this->getStates();
    }

    public function saveAccountInformation()
    {
        $this->validate();

        $user = auth()->user();

        $this->validate([
            'accountInformation.avatar_name' => 'unique:'.Profile::class.',avatar_name,'.$user?->profile?->id,
            ...$user->media('avatar')->first() ? [] : ['accountInformation.avatar' => 'required|image|max:1024'],
        ]);

        if ($user?->profile) {
            $user?->profile?->update([
                'fname' => $this->accountInformation['fname'],
                'lname' => $this->accountInformation['lname'],
                'mobile' => $this->accountInformation['mobile'] ?? '',
                'avatar_name' => $this->accountInformation['avatar_name'],
            ]);
        } else {
            $user?->profile()->create([
                'fname' => $this->accountInformation['fname'],
                'lname' => $this->accountInformation['lname'],
                'mobile' => $this->accountInformation['mobile'] ?? '',
                'avatar_name' => $this->accountInformation['avatar_name'],
            ]);
        }

        if (isset($this->accountInformation['avatar'])) {
            // dd($this->accountInformation['avatar']);
            $user->addMedia($this->accountInformation['avatar']?->getRealPath())
                ->toMediaCollection('avatar');

            $this->reset('accountInformation.avatar');
        }

        $this->step = 'location';

    }

    public function saveLocationInformation()
    {

        $this->validate([
            'locationInformation.country_id' => ['required', 'exists:countries,id'],
            'locationInformation.state_id' => ['required', 'exists:states,id'],
            'locationInformation.gender' => ['required', ValidationRule::in(GenderEnum::getAllValues()->toArray())],
            'locationInformation.bio' => ['string', 'max:255'],
            'locationInformation.birth_date' => ['required', 'date'],
        ]);

        $user = \Auth::user();

        DB::beginTransaction();

        try {
            if ($user?->profile) {
                $user?->profile?->update([
                    'state_id' => $this->locationInformation['state_id'],
                    'gender' => $this->locationInformation['gender'],
                    'birth_date' => $this->locationInformation['birth_date'],
                    'bio' => $this->locationInformation['bio'] ?? '',
                ]);
            } else {
                $user?->profile?->create([
                    'state_id' => $this->locationInformation['state_id'],
                    'gender' => $this->locationInformation['gender'],
                    'birth_date' => $this->locationInformation['birth_date'],
                    'bio' => $this->locationInformation['bio'] ?? '',
                ]);
            }

            if (! $user?->achievements()->completedProfileScope()->count() > 0) {
                ReceiveCoin::handle(
                    $user,
                    config('ranking.rules.coin.user.complete_profile'),
                    StatusEnum::ACHIEVEMENT_COMPLETE_PROFILE
                );
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }

        $this->step = 'complete';

    }

    public function render()
    {
        return view('livewire.pages.profile.complete-profile');
    }

    private function getStates()
    {
        return State::query()
            ->select(['id', 'name'])
            ->where('country_id', $this->locationInformation['country_id'])
            ->get()
            ->toArray();
    }
}
