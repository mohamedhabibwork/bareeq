@if (request()->has('redirect'))
    {{ Form::hidden('redirect',request('redirect'))}}
@endif
<div class="form-group">
    {{ Form::label(__('main.car_name'), null, ['class' => 'control-label']) }}
    {{ Form::text('car_name', old('car_name'), array_merge(['class' => 'form-control  '.($errors->has('car_name') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="car_name"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.car_type'), null, ['class' => 'control-label']) }}
    {{ Form::text('car_type', old('car_type'), array_merge(['class' => 'form-control  '.($errors->has('car_type') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="car_type"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.phone'), null, ['class' => 'control-label']) }}
    {{ Form::tel('phone', old('phone'), array_merge(['class' => 'form-control  '.($errors->has('phone') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="phone"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.address'), null, ['class' => 'control-label']) }}
    {{ Form::textarea('address', old('address'), array_merge(['class' => 'form-control '.($errors->has('address') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="address"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.car_area'), null, ['class' => 'control-label']) }}
    {{ Form::textarea('car_area', old('car_area'), array_merge(['class' => 'form-control '.($errors->has('car_area') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="car_area"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.user_id'), null, ['class' => 'control-label']) }}
    {{ Form::select('user_id',\App\Models\User::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('user_id') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="user_id"/>
</div>
