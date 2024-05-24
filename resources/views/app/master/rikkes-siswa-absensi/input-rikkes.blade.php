@extends('admin.layouts.master')
@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }} ">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/flatpicker/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <style>
    </style>
@endpush
@section('header')
    <x-header title="{{ $jadwal->nama }}" back-button="true"></x-header>
  
  
@endsection
@section('content')
 
    <div class="col-sm-12">
        <form id="form_sample" method="post">
            @csrf
            <div class="card">
                <div class="card-body">
                    <div class="card-body">

                        <x-datatable id="datatable" :th="['No', 'Nama', 'NOSIS', 'PELETON','Tensi','tinggi','bb','imt','nilai','keterangan' ,'Aksi']" style="width: 100%"></x-datatable>
                    </div>
                </div>

            </div>
        </form>
    </div>
    @include('app.master.rikkes-siswa-absensi.modal-input-rikkes')
@endsection
@push('js')
    {{-- filepond --}}
    {{-- masking input currency,date input --}}
    <script src="{{ asset('plugins/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    {{-- flatcpiker format date input --}}
    <script src="{{ asset('plugins/flatpicker/flatpickr.min.js') }}"></script>
    <script src="{{ asset('plugins/flatpicker/id.min.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- password toggle show/hide --}}
    <script src="{{ asset('plugins/toggle-password.js') }}"></script>
    <script>
        $(function() {
            $('.select2bs4').select2({
                theme: 'bootstrap4',
            })

            $('#form_input_rikkes').submit(function(e) {
                e.preventDefault();
                const formData = new FormData(this);
                $.ajax({
                    type: 'POST',
                    url: route('rikkes-siswa-absensi.store'),
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    beforeSend: function() {
                        _showLoading()
                    },
                    success: (response) => {
                        if (response) {
                            $('#modal_input_rikkes').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: response.message,
                                showCancelButton: true,
                                allowEscapeKey: false,
                                showCancelButton: false,
                                allowOutsideClick: false,
                            }).then((result) => {
                                datatable.ajax.reload()
                            })
                        }
                    },
                    error: function(response) {
                        _showError(response)
                    }
                })
            })

            let datatable = $("#datatable").DataTable({
                serverSide: true,
                processing: true,
                searching: true,
                pageLength: 30,
                lengthChange: true,
                paging: true,
                info: true,
                ordering: true,
                aaSorting: [],
                // order: [3, 'desc'],
                scrollX: true,

                ajax: route('rikkes-siswa-absensi.input', @json($jadwal->id)),
                columns: [{
                        data: "DT_RowIndex",
                        orderable: false,
                        searchable: false,
                        width: '1%'
                    },
                    {
                        data: 'nama',
                        name: 'nama',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'nosis',
                        name: 'nosis',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'peleton_id',
                        name: 'peleton_id',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'tensi',
                        name: 'tensi',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'tinggi',
                        name: 'tinggi',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'bb',
                        name: 'bb',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'imt',
                        name: 'imt',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'nilai',
                        name: 'nilai',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan',
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "action",

                        orderable: false,
                        searchable: false,
                    },
                ]
            })


            $('#datatable').on('click', '.btn_input', function(e) {
                e.preventDefault()
                _clearInput()
                let user = JSON.parse($(this).attr('data-user'));
                $('#modal_input_rikkes').modal('show');
                $('#nama').val(user.nama)
                $('#user_id').val(user.id)
                $('#nosis').val(user.nosis)
                $('#rikkes_siswa_jadwal_id').val($(this).attr('data-jadwal'))

               
                
            })
        })
    </script>
@endpush
