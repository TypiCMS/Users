@if ($message = session('status'))
    <div class="alert alert-info">
        <p>{{ $message }}</p>
    </div>
@endif
