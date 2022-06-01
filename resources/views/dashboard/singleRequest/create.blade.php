<x-layout.app :title="__('main.create',['model'=>__('main.singleRequest')])">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card>
                <x-dashboard.errors/>
                {{ Form::model($model,['url'=> route('dashboard.singleRequest.store'),'files'=>true]) }}
                @include('dashboard::singleRequest.fields')

                {{ Form::submit(__('main.save'),['class'=>'btn btn-sm btn-primary']) }}
                {{ Form::close() }}
            </x-dashboard.card>
        </div>
        {{--        <div class="col-md-4">--}}

        {{--        </div>--}}
    </div>
</x-layout.app>
