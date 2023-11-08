@if(!empty($errors) && $errors?->any())
    <div class="alert alert-danger text-center">
        @foreach ($errors->all() as $error)
            <h4>{{ $error }}</h4>
        @endforeach
    </div>
@endif
