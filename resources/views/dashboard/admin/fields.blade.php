<div class="form-group">
    {{ Form::label(__('main.name'), null, ['class' => 'control-label']) }}
    {{ Form::text('name', old('name'), array_merge(['class' => 'form-control  '.($errors->has('name') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="name"/>
</div>

<div class="form-group">
    {{ Form::label(__('main.email'), null, ['class' => 'control-label']) }}
    {{ Form::email('email', old('email'), array_merge(['class' => 'form-control  '.($errors->has('email') ? 'is-invalid' : '')],['required'=>true])) }}
    <x-dashboard.error name="email"/>
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
