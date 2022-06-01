<div class="form-group">
    {{ Form::label(__('main.type'), null, ['class' => 'control-label']) }}
    {{ Form::text('type', old('type'), array_merge(['class' => 'form-control  '.($errors->has('type') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="type"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.color'), null, ['class' => 'control-label']) }}
    {{ Form::text('color', old('color'), array_merge(['class' => 'form-control  '.($errors->has('color') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="color"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.plate_number'), null, ['class' => 'control-label']) }}
    {{ Form::text('plate_number',old('plate_number'), array_merge(['class' => 'form-control  '.($errors->has('plate_number') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="plate_number"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.image'), null, ['class' => 'control-label']) }}
    {{ Form::file('image', array_merge(['class' => 'form-control  '.($errors->has('image') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="image"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.user_id'), null, ['class' => 'control-label']) }}
    {{ Form::select('user_id',\App\Models\User::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('user_id') ? 'is-invalid' : '')],[])) }}
    <x-dashboard.error name="user_id"/>
</div>
