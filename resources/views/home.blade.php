<x-layout.app>
    <div class="row p-3">
        <div class="col-md-4 col-sm-12 p-3">
                <x-dashboard.card>
                    <x-slot name="header">
                        {{ __('main.user_available_today') }}
                    </x-slot>
                    @if ($users->count())
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
                                <td scope="row">{{ $loop->index + ($users->perPage() * ($users->currentPage() - 1) ) + 1 }} / #{{ $user->id }}</td>
                                <td> {{ $user->name }}</td>
                                <td>{{ $user->phone }}</td>
                                <td><a href="{{ route('dashboard.worker_users.create',['user_id'=>$user->id,'redirect'=>url()->current()]) }}"
                                       class="btn btn-sm btn-success">{{ __('main.assign') }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                        <div>
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="text-center"><h2>{{ __('main.no_requests_available_today') }}</h2></div>
                    @endif
                </x-dashboard.card>

        </div>
        <div class="col-md-3 col-sm-12 p-3">
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
                                <td scope="row">{{ $loop->index + ($orders->perPage() * ($orders->currentPage() - 1) ) + 1 }} / #{{ $order->id }}</td>
                                <td> {{ $order->user->name }}</td>
                                <td>{{ $order->user->phone }}</td>
                                <td><a href="{{ route('dashboard.worker_users.edit',['id'=>$order->id,'redirect'=>url()->current()]) }}"
                                       class="btn btn-sm btn-success">{{ __('main.assign') }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center"><h2>{{ __('main.no_orders_today') }}</h2></div>
                @endif
            </x-dashboard.card>
        </div>
        <div class="col-md-5 col-sm-12 p-3">
            <x-dashboard.card>
                <x-slot name="header">
                    {{ __('main.request_today') }}
                </x-slot>
                @if ($requests->count())
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
                        @foreach($requests as $request)
                            <tr>
                                <td scope="row">{{ $loop->index + ($requests->perPage() * ($requests->currentPage() - 1) ) + 1 }} / #{{ $request->id }}</td>
                                <td> {{ $request->user->name }}</td>
                                <td>{{ $request->user->phone }}</td>
                                <td><a href="{{ route('dashboard.singleRequest.show',['id'=>$request,'redirect'=>url()->current()]) }}"
                                       class="btn btn-sm btn-success">{{ __('main.assign') }}</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div>
                        {{ $requests->links() }}
                    </div>
                @else
                    <div class="text-center"><h2>{{ __('main.no_requests_today') }}</h2></div>
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
