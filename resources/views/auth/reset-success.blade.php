@if (session()->has('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session()->has('failure'))
<div class="alert alert-danger">
    {{ session('failure') }}
</div>
@endif
