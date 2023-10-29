@if(Session::has('message'))
    <div class="alert {{ Session::flash('alert-class', 'alert-info') }} text-center">
        <div class="alert-message">{{ Session::flash('message') }}</div>
    </div>
@endif
