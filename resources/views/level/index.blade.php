@extends('layouts.template')

@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('level/create') }}">Tambah</a>
            <button onclick="modalAction('{{ url('level/create_ajax') }}')" class="btn btn-sm btn-success mt-1">Tambah Ajax</button>
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
                        <select class="form-control" id="level_nama" name="level_nama">
                            <option value="">- Semua -</option>
                            @foreach($level as $item)
                                <option value="{{ $item->level_nama }}">{{ $item->level_nama }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Nama Level</small>
                    </div>
                </div>
            </div>
        </div>

        <table class="table table-bordered table-hover table-striped" id="table_level">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Level</th>
                    <th>Kode Level</th>
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

var data_level;
    $(document).ready(() => {
       data_level = $('#table_level').DataTable({
            serverSide: true,
            ajax: {
                url: "{{ url('level/list') }}",
                type: "POST",
                data: function(d) {
                    d.level_nama = $('#level_nama').val();
                }
            },
            columns: [
                { data: "level_id", className: "text-center", orderable: true },
                { data: "level_nama", orderable: true },
                { data: "level_kode", orderable: true },
                { data: "aksi", orderable: false }
            ]
        });

        $('#level_nama').on('change', function() {
            data_level.ajax.reload();
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
@endpush