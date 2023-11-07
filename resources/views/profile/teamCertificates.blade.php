@if($certificates->count()>0)
  @foreach($certificates as $certificate)
    <div class="d-inline-block p-3">
      <a href="{{route('team_cert_info',['cert_id'=>$certificate->id, 'en_teamname'=>$certificate->team->dashed_en_name])}}" target="_blank">
        <img src="{{$certificate->small_image}}" class="rounded img_cert" width="250">
      </a>
      <div class="p-2">
        <img src="/img/cup_{{$certificate->place}}.png" width="32">
        {{ trans_choice('words.tournament_place_number', $certificate->place) }}
      </div>
      <div class="pb-2">{{$certificate->bracket->competition->name}}</div>
      <div class="pb-2"><a href="{{route('team_profile', ['teamname'=>$certificate->team->name])}}" target="_blank">{{$certificate->team->name}}</a></div>
    </div>
  @endforeach
@else
  <div class="w-100 text-center pt-4">{{__('words.dont_yet_any_certificate')}} ...</div>
@endif