<form action="{{ url('/kategori/import-ajax') }}" method="POST" id="form-import" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Impor Data Kategori</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Download Template</label>
                    <a href="{{ asset('template-kategori.xlsx') }}" class="btn btn-info btn-sm" download><i class="fa fa-file-excel"></i> Download</a>
                    <small id="error-kategori_id" class="error-text form-text text danger"></small>
                </div>
                <div class="form-group">
                    <label>Pilih File</label>
                    <input type="file" name="file_kategori" id="file_kategori" class="form-control" required>
                    <small id="error-file_kategori" class="error-text form-text text danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Unggah</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(() => {
        $("#form-import").validate({
            rules: {
                file_kategori: { required: true, extension: "xlsx" },
            },
            submitHandler: (form) => {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: new FormData(form),
                    processData: false,
                    contentType: false,
                    success: (response) => {
                        if (response.status) {
                            $('#my-modal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                        } else {
                            $('.error-text').text('');
                            $.each(response.message_field, (prefix, val) => $('#error-' + prefix).text(val[0]));
                            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
                        }
                    },
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