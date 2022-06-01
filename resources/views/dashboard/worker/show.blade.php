<x-layout.app :title="__('main.show',['model'=>$model->name])">
    <div class="row">
        <div class="col-md-12">
            <x-dashboard.card>
                @foreach($model->getAttributes() as $key => $field)
                    @if (!in_array($key,$model->getHidden()))
                        <li>{{ "$key => $field" }}</li>
                    @endif
                @endforeach
            </x-dashboard.card>
        </div>
    </div>
</x-layout.app>
