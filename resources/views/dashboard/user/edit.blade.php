<x-layout.app :title="__('main.edit',['model'=>__('main.user')])">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card>
                <x-dashboard.errors/>
                {{ Form::model($model,['url'=> route('dashboard.users.update',$model),'method'=>'PUT']) }}
                @include('dashboard::user.fields')
                {{ Form::submit(__('main.update'),['class'=>'btn btn-sm btn-warning']) }}
                {{ Form::close() }}
            </x-dashboard.card>
        </div>
    </div>
</x-layout.app>
