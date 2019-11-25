@if ($errors->any())
    <div class="alert alert-warning">
        @foreach ($errors->all() as $error)
            <p class="mb-0">{{ $error }}</p>
        @endforeach
    </div>
@endif

@if (session('api_errors'))
    <div class="alert alert-warning">
        @foreach (session('api_errors') as $errors)
            @foreach ($errors as $error)
                <p class="mb-0">{{ $error }}</p>
            @endforeach
        @endforeach
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success">
        {!! session('success') !!}
    </div>
@endif

@if (session('warning'))
    <div class="alert alert-warning">
        {!! session('warning') !!}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
