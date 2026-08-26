@props(['type' => 'info', 'message'])

<!--Closable Alerts start -->
{{-- <section id="alerts-closable">
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="demo-spacing-0"> --}}
                <div class="alert alert-{{ $type }} alert-dismissible fade show mb-2" role="alert">
                    <div class="alert-body">
                        {{ $message }}
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            {{-- </div>
        </div>
    </div> --}}
</section>
<!--Closable Alerts end -->
