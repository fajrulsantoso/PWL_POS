@empty($kategori)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
                <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!</h5>
                    Data yang anda cari tidak ditemukan
                </div>
                <a href="{{ url('/kategori') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ url('/kategori/' . $kategori->kategori_id . '/update-ajax') }}" method="POST" id="form-edit">
        @csrf
        @method('PUT')
        <div id="modal-master" class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" arialabel="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kode</label>
                        <input
                            value="{{ $kategori->kategori_kode }}"
                            type="text"
                            name="kategori_kode"
                            id="kategori_kode"
                            class="form-control"
                            required
                        />
                        <small id="error-kategori_kode" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input
                            value="{{ $kategori->kategori_nama }}"
                            type="text"
                            name="kategori_nama"
                            id="kategori_nama"
                            class="form-control"
                            required
                        />
                        <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>
    <script>
        $(document).ready(() => {
            $("#form-edit").validate({
                rules: {
                    kategori_kode: {
                        required: true,
                        maxlength: 6,
                        pattern: /^[A-Z0-9]+$/
                    },
                    kategori_nama: {
                        required: true,
                        minlength: 3,
                        maxlength: 50,
                        pattern: /^[a-zA-Z\s]+$/
                    },
                },
                submitHandler: (form) => {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize(),
                        success: (response) => {
                            if (response.status) {
                                $('#my-modal').modal('hide');
                                Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            } else {
                                $('.error-text').text('');
                                $.each(response.message_field, (prefix, val) => $('#error-' + prefix).text(val[0]));
                                Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
                            }
                        }
                    });
                    return false;
                },
                errorElement: 'span',
                errorPlacement: (error, element) => {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: (element) => $(element).addClass('is-invalid'),
                unhighlight: (element) => $(element).removeClass('is-invalid'),
            });
        });
    </script>
@endempty