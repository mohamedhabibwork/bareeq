<x-layout.app :title="__('main.create',['model'=>__('main.workerUser')])">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card>
                <x-dashboard.errors/>
                {{ Form::model($model,['url'=> route('dashboard.worker_users.store'),'files'=>true]) }}
                @include('dashboard::workerUser.fields')

                {{ Form::submit(__('main.save'),['class'=>'btn btn-sm btn-primary']) }}
                {{ Form::close() }}
            </x-dashboard.card>
        </div>
        {{--        <div class="col-md-4">--}}

        {{--        </div>--}}
    </div>
</x-layout.app>
