@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $page->title }}</h3>
            <div class="card-tools">
                <button onclick="modal_action('{{ url('/kategori/import') }}')" class="btn btn-sm btn-info mt-1">
                    Impor Kategori
                </button>
                <a href="{{ url('/kategori/export-excel') }}" class="btn btn-sm btn-primary mt-1">
                    <i class="fa fa-file-excel mr-2"></i> Ekspor Kategori
                </a> 
                <a href="{{ url('/kategori/export-pdf') }}" class="btn btn-sm btn-warning mt-1">
                    <i class="fa fa-file-pdf mr-2"></i> Ekspor Kategori
                </a> 
                <button onclick="modal_action('{{ url('kategori/create-ajax') }}')" class="btn btn-sm btn-success mt-1">
                    Tambah AJAX
                </button>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <table class="table table-bordered table-striped table-hover table-sm" id="table_kategori">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
    <div
        id="my-modal"
        class="modal fade animate shake"
        tabindex="-1"
        role="dialog"
        databackdrop="static"
        data-keyboard="false"
        data-width="75%"
        aria-hidden="true"
    ></div>
@endsection

@push('css')
@endpush

@push('js')
    <script>
        const modal_action = (url = '') => $('#my-modal').load(url, () => $('#my-modal').modal('show'));
        $(document).ready(function() {
            let data_kategori = $('#table_kategori').DataTable({
                serverSide: true,
                ajax: {
                    "url": "{{ url('/kategori/list') }}",
                    "dataType": "json",
                    "type": "POST",
                    "data": d => {
                        d.kategori_id = $('#kategori_id').val();
                    },
                },
                columns: [{
                        data: "DT_RowIndex",
                        className: "text-center",
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: "kategori_kode",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "kategori_nama",
                        className: "",
                        orderable: true,
                        searchable: true
                    },
                    {
                        data: "aksi",
                        className: "",
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            $('#kategori_id').on('change', () => data_kategori.ajax.reload());
        });
    </script>
@endpush