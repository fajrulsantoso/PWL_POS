@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
            <button onclick="modalAction('{{ url('barang/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
        </div>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control-label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id">
                            <option value="">- Semua -</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Kategori Barang</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
  function modalAction(url = '') {
    $.ajax({
        url: url,
        type: "GET",
        success: function(response) {
            $('#myModal').html(response).modal('show');
        },
        error: function() {
            alert('Gagal memuat modal. Coba lagi!');
        }
    });
}

var data_barang;
    $(document).ready(() => {
       data_barang = $('#table_barang').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('barang/list') }}",
                type: "POST",
                data: function(d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [
                { data: "barang_id", className: "text-center", orderable: true },
                { data: "barang_kode", orderable: true },
                { data: "barang_nama", orderable: true },
                { data: "kategori.kategori_nama", orderable: false },
                { data: "harga_beli", orderable: true },
                { data: "harga_jual", orderable: true },
                { data: "aksi", orderable: false }
            ]
        });

        $('#kategori_id').on('change', function() {
            data_barang.ajax.reload();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endpush
