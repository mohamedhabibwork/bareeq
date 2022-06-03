<x-layout.app>
    <div class="row p-3">
        <div class="col-md-6 col-sm-12 p-3">
            @if ($users->count())
                <x-dashboard.card>
                    <x-slot name="header">
                        {{ __('main.user_available_today') }}
                    </x-slot>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('main.name')</th>
                            <th>@lang('main.phone')</th>
                            <th>@lang('main.assign_worker')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td scope="row">{{ $user->id }}</td>
                                <td> {{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td><a href="{{ route('dashboard.worker_users.create',['user_id'=>$user->id]) }}"
                                       class="btn btn-sm btn-success">{{ __('main.assign') }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </x-dashboard.card>
            @else
                <div class="text-center"><h2>{{ __('main.no_orders_available_today') }}</h2></div>
            @endif
        </div>
        <div class="col-md-6 col-sm-12 p-3">
            <x-dashboard.card>
                <x-slot name="header">
                    {{ __('main.order_today') }}
                </x-slot>
                @if ($orders->count())
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>@lang('main.name')</th>
                            <th>@lang('main.phone')</th>
                            <th>@lang('main.assign_worker')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td scope="row">{{ $order->id }}</td>
                                <td> {{ $order->user->name }}</td>
                                <td>{{ $order->user->phone }}</td>
                                <td><a href="{{ route('dashboard.worker_users.edit',$order) }}"
                                       class="btn btn-sm btn-success">{{ __('main.assign') }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center"><h2>{{ __('main.no_orders_today') }}</h2></div>
                @endif
            </x-dashboard.card>
        </div>

        <x-dashboard.card class="mt-5 col-md-12">
            {!! $chart->container() !!}
        </x-dashboard.card>

    </div>
    <x-slot name="js">
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}
    </x-slot>
</x-layout.app>
