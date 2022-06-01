<div class="form-group">
    {{ Form::label(__('main.user_id'), null, ['class' => 'control-label']) }}
    {{ Form::select('user_id',\App\Models\User::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('user_id') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="user_id"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.plan_id'), null, ['class' => 'control-label']) }}
    {{ Form::select('plan_id',\App\Models\Plan::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('plan_id') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="plan_id"/>
</div>
