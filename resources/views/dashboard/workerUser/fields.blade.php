{{--@if (request()->has('user_id'))--}}
    {{ Form::hidden('user_id',null, array_merge(['class' => 'form-control  '.($errors->has('user_id') ? 'is-invalid' : '')],[])) }}
    {{ Form::hidden('plan_id',null, array_merge(['class' => 'form-control  '.($errors->has('plan_id') ? 'is-invalid' : '')],[])) }}
@if (request()->has('redirect'))
    {{ Form::hidden('redirect',request('redirect'))}}
@endif
{{--@else--}}
    <div class="form-group">
        {{ Form::label(__('main.user_id'), null, ['class' => 'control-label']) }}
        {{ Form::select('user_id',\App\Models\User::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('user_id') ? 'is-invalid' : '')],['disabled'=>true,])) }}
        <x-dashboard.error name="user_id"/>
    </div>

{{--    <div class="form-group">--}}
{{--        {{ Form::label(__('main.plan_id'), null, ['class' => 'control-label']) }}--}}
{{--        {{ Form::select('plan_id',\App\Models\Plan::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('plan_id') ? 'is-invalid' : '')],['disabled'=>true,])) }}--}}
{{--        <x-dashboard.error name="plan_id"/>--}}
{{--    </div>--}}
{{--@endif--}}

<div class="form-group">
    {{ Form::label(__('main.worker_id'), null, ['class' => 'control-label']) }}
    {{ Form::select('worker_id',\App\Models\Worker::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('worker_id') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="worker_id"/>
</div>
