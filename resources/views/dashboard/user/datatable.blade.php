@push('css')
    <link rel="stylesheet"
          href="{{ asset('assets/dashboard/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/dashboard/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet"
          href="{{ asset('assets/dashboard/plugins/datatables-select/css/select.bootstrap4.min.css') }}">
@endpush

<x-dashboard.card>
    {{ $dataTable->table(['class'=>'w-100 table']) }}
</x-dashboard.card>

@push('js')

    <script src="{{ asset('assets/dashboard/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-select/js/dataTables.select.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-select/js/select.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/dashboard/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
@endpush

