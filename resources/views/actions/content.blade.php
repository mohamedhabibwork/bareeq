<!-- Button trigger modal -->
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#content{{$id}}"><i
        class="fa fa-eye text-white"></i></button>

<div class="modal fade" id="content{{$id}}" tabindex="-1" role="dialog" aria-labelledby="modelTitle{{ $id }}"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('main.content')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">{!! $content ?? '' !!}</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('main.close') }}</button>
                <button type="button" class="btn btn-primary">{{ __('main.save') }}</button>
            </div>
        </div>
    </div>
</div>
