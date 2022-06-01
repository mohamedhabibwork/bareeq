<x-layout.app>
    <div class="container">
        <div class="row">
            <x-dashboard.card class="mt-5 col-md-12">
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
            <x-dashboard.card class="mt-5 col-md-12">
                {!! $chart->container() !!}
            </x-dashboard.card>
        </div>
    </div>
    <x-slot name="js">
        <script src="{{ $chart->cdn() }}"></script>
        {{ $chart->script() }}
    </x-slot>
</x-layout.app>
