<x-layout.app :title="__('main.show',['model'=>$model->name])">
    <br>
    <div class="row">
        <div class="col-md-12 row">
            <x-dashboard.card class="col-md-10 col-sm-12">
                <div class="row">
                    @foreach($model->getAttributes() as $key => $field)
                        @if (!in_array($key,$model->getHidden()))
                            <div class="col-md-4 p-3" style="font-size: large">
                                {{ __("main.{$key}")." : " }} <b>{{ $field }}</b>
                            </div>
                        @endif
                    @endforeach
                </div>
            </x-dashboard.card>
            @if (!$model->orders()->exists())
                <x-dashboard.card class="col-md-2 col-sm-12">
                    {{ Form::open(['route'=>['dashboard.singleRequest.accept',['id'=>$model,'redirect'=>request('redirect')]]]) }}
                    <div class="form-group">
                        {{ Form::label(__('main.worker_id'), null, ['class' => 'control-label']) }}
                        {{ Form::select('worker_id',\App\Models\Worker::pluck('name','id')->toArray(),null, array_merge(['class' => 'form-control  '.($errors->has('worker_id') ? 'is-invalid' : '')],[])) }}
                        <x-dashboard.error name="worker_id"/>
                    </div>
                    {{ Form::submit(__('main.accept'),['class'=>'btn btn-sm btn-success']) }}
                    {{ Form::close() }}
                </x-dashboard.card>
            @endif
        </div>
    </div>
</x-layout.app>
