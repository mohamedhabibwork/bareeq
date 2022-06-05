<x-layout.app :title="__('main.edit',['model'=>__('main.city')])" onload="init()">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card>
                <x-dashboard.errors/>
                {{ Form::model($model,['url'=> route('dashboard.cities.update',$model),'method'=>'PUT','files'=>true]) }}
                @include('dashboard::city.fields')
                {{ Form::submit(__('main.update'),['class'=>'btn btn-sm btn-warning']) }}
                {{ Form::close() }}
            </x-dashboard.card>
        </div>
    </div>
</x-layout.app>
