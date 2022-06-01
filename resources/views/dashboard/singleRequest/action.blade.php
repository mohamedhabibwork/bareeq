@if (Route::has('dashboard.singleRequest.destroy'))
    {!! Form::open(['route' => ['dashboard.singleRequest.destroy',$model], 'method' => 'delete']) !!}
@endif
<div class='btn-group'>
    @if (Route::has('dashboard.singleRequest.show'))
        <a href="{{ route('dashboard.singleRequest.show',$model) }}"
           class='btn btn-primary btn-md btn-pill btn-air-primary text-white'>
            <i class="fa fa-eye text-white"></i>
        </a>
    @endif
    @if (Route::has('dashboard.singleRequest.edit'))
        <a href="{{ route('dashboard.singleRequest.edit',$model) }}"
           class='btn btn-warning btn-md btn-pill btn-air-warning text-white'>
            <i class="fa fa-edit text-white"></i>
        </a>
    @endif
    @if (Route::has('dashboard.singleRequest.destroy'))
        {!! Form::button('<i class="fa fa-trash text-white"></i>', [
            'type' => 'submit',
            'class' => 'btn btn-danger btn-md btn-pill btn-air-danger text-white',
            'onclick' => 'return confirm("'.__('crud.are_you_sure').'")'
        ]) !!}
    @endif
</div>

@if (Route::has('dashboard.singleRequest.destroy'))
    {!! Form::close() !!}
@endif
