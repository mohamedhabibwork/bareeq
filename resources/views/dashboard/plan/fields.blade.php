<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.name'), null, ['class' => 'control-label']) }}
            {{ Form::text('name', old('name'), array_merge(['class' => 'form-control  '.($errors->has('name') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="name"/>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.type'), null, ['class' => 'control-label']) }}
            {{ Form::select('type',array_flip(\App\Models\Plan::TYPE),null , array_merge(['class' => 'form-control  '.($errors->has('wishing_count') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="wishing_count"/>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.wishing_count'), null, ['class' => 'control-label']) }}
            {{ Form::number('wishing_count', old('wishing_count'), array_merge(['class' => 'form-control  '.($errors->has('wishing_count') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="wishing_count"/>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">

        <div class="form-group">
            {{ Form::label(__('main.price'), null, ['class' => 'control-label']) }}
            {{ Form::number('price', old('price'), array_merge(['class' => 'form-control '.($errors->has('price') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="price"/>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.images'), null, ['class' => 'control-label']) }}
            {{ Form::file('images[]',array_merge(['class' => 'form-control  '.($errors->has('images') ? 'is-invalid' : '')],['multiple'=>true])) }}
            <x-dashboard.error name="images"/>
        </div>

    </div>

    <div class="form-group">
        {!! Form::hidden('status', 0, ['class' => 'form-check-input']) !!}
        {{ Form::label(__('main.status'), null, ['class' => 'control-label']) }}
        <label for="" class="form-check-label">
            {!! Form::checkbox('status', 1, null, ['class' => ' custom-checkbox checkbox_animated']) !!}
        </label>
    </div>

    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            {{ Form::label(__('main.description'), null, ['class' => 'control-label']) }}
            {{ Form::textarea('description', old('description'), array_merge(['class' => 'form-control  '.($errors->has('description') ? 'is-invalid' : '')],['required'=>true])) }}
            <x-dashboard.error name="description"/>
        </div>
    </div>


</div>
