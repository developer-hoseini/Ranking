@if (Session::has('message'))
    <div class="alert {{ Session::flash('alert-class', 'alert-info') }} text-center">
        <div class="alert-message">{{ Session::flash('message') }}</div>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success text-center">
        <div class="alert-message">{{ session('success') }}</div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger text-center">
        <div class="alert-message">{{ session('error') }}</div>
    </div>
@endif
