<!-- Default box -->
<div {{ $attributes->class('card') }}>
    @isset($header)
        <div class="card-header">
            <h3 class="card-title"> {{ $header }} </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                {{--                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">--}}
                {{--                    <i class="fas fa-times"></i>--}}
                {{--                </button>--}}
            </div>
        </div>
    @endisset
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
<!-- /.card -->
