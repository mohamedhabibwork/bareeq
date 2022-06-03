<div class="form-group">
    {{ Form::label(__('main.name'), null, ['class' => 'control-label']) }}
    {{ Form::text('name', old('name'), array_merge(['class' => 'form-control  '.($errors->has('name') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="name"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.wish_day'), null, ['class' => 'control-label']) }}
    {{ Form::select('wish_day',['Saturday'=>'Saturday','Sunday'=>'Sunday','Monday'=>'Monday','Tuesday'=>'Tuesday','Wednesday'=>'Wednesday','Thursday'=>'Thursday','Friday'=>'Friday'],null , array_merge(['class' => 'form-control  '.($errors->has('wish_day') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="wish_day"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.start_time'), null, ['class' => 'control-label']) }}
    {{ Form::time('start_time', old('start_time'), array_merge(['class' => 'form-control  '.($errors->has('start_time') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="start_time"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.end_time'), null, ['class' => 'control-label']) }}
    {{ Form::time('end_time', old('end_time'), array_merge(['class' => 'form-control  '.($errors->has('end_time') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="end_time"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.phone'), null, ['class' => 'control-label']) }}
    {{ Form::tel('phone', old('phone'), array_merge(['class' => 'form-control  '.($errors->has('phone') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="phone"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.password'), null, ['class' => 'control-label']) }}
    {{ Form::password('password', array_merge(['class' => 'form-control  '.($errors->has('password') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="password"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.password_confirmation'), null, ['class' => 'control-label']) }}
    {{ Form::password('password_confirmation', array_merge(['class' => 'form-control  '.($errors->has('password') ? 'is-invalid' : '')],[])) }}
</div>

<div class="form-group">
    {!! Form::hidden('status', 0, ['class' => 'form-check-input']) !!}
    {{ Form::label(__('main.status'), null, ['class' => 'control-label']) }}
    <label for="" class="form-check-label">
        {!! Form::checkbox('status', 1, null, ['class' => ' custom-checkbox checkbox_animated']) !!}
    </label>
</div>
