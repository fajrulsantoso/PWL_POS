@extends('layouts.template')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar barang</h3>
        <div class="card-tools">
            <button onclick="modal_action('{{ url('/barang/impor') }}')" class="btn btn-info">
                Impor Barang
            </button>
            <a href="{{ url('/barang/export-excel') }}" class="btn btn-primary">
                <i class="fa fa-file-excel mr-2"></i> Ekspor Barang
            </a>
            <a href="{{ url('/barang/export-pdf') }}" class="btn btn-warning">
                <i class="fa fa-file-pdf mr-2"></i> Ekspor Barang
            </a>
            <button onclick="modal_action('{{ url('/barang/create_ajax') }}')" class="btn btn-success">
                Tambah Data (Ajax)
            </button>
        </div>
    </div>
    <div class="card-body">
        <div id="filter" class="form-horizontal filter-date p-2 border-bottom mb-2">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group form-group-sm row text-sm mb-0">
                        <label for="filter_date" class="col-md-1 col-formlabel">Filter</label>
                        <div class="col-md-3">
                            <select name="filter_kategori" class="form-control form-control-sm filter_kategori">
                                <option value="">- Semua -</option>
                                @foreach($kategori as $l)
                                <option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">Kategori Barang</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <table class="table table-bordered table-sm table-striped table-hover" id="table-barang">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div
    id="my-modal"
    class="modal fade animate shake"
    tabindex="-1"
    data-backdrop="static"
    data-keyboard="false"
    data-width="75%"
></div>
@endsection

@push('js')
<script>
    const modal_action = (url = '') => {
        $('#my-modal').load(url, () => {
            $('#my-modal').modal('show');
        });
    }

    var tableBarang;
    $(document).ready(() => {
        tableBarang = $('#table-barang').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ url('barang/list') }}",
                dataType: "json",
                type: "POST",
                data: (d) => d.filter_kategori = $('.filter_kategori').val(),
            },
            columns: [{
                data: "DT_RowIndex",
                className: "text-center",
                width: "5%",
                orderable: false,
                searchable: false
            }, {
                data: "barang_kode",
                className: "",
                width: "10%",
                orderable: true,
                searchable: true
            }, {
                data: "barang_nama",
                className: "",
                width: "37%",
                orderable: true,
                searchable: true
            }, {
                data: "harga_beli",
                className: "",
                width: "10%",
                orderable: true,
                searchable: false,
                render: (data) => {
                    return new Intl.NumberFormat('id-ID').format(data)
                },
            }, {
                data: "harga_jual",
                className: "",
                width: "10%",
                orderable: true,
                searchable: false,
                render: (data) => {
                    return new Intl.NumberFormat('id-ID').format(data)
                },
            }, {
                data: "kategori.kategori_nama",
                className: "",
                width: "14%",
                orderable: true,
                searchable: false
            }, {
                data: "aksi",
                className: "text-center",
                width: "14%",
                orderable: false,
                searchable: false
            }]
        });

        $('.filter_kategori').change(() => tableBarang.draw());
        $('#table-barang_filter input').unbind().bind().on('keyup', (e) => {
            if (e.keyCode == 13) tableBarang.search(this.value).draw();
        });
    });
</script>
@endpush