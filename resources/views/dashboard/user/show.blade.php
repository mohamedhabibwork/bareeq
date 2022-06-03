<x-layout.app :title="__('main.show',['model'=>$model->name])">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card class="">
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
        </div>
    </div>
</x-layout.app>
