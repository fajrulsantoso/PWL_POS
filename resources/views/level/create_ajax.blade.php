<form action="{{ url('/level/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Level</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Level</label>
                    <input type="text" name="level_kode" id="level_kode" class="form-control">
                    <small class="text-danger error-text" id="error-level_kode"></small>
                </div>
                <div class="form-group">
                    <label>Nama Level</label>
                    <input type="text" name="level_nama" id="level_nama" class="form-control">
                    <small class="text-danger error-text" id="error-level_nama"></small>
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
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                level_kode: { required: true, minlength: 2, maxlength: 10 },
                level_nama: { required: true, minlength: 3, maxlength: 100 }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: response.message });
                            $('#table_level').DataTable().ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.message_field, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({ icon: 'error', title: 'Terjadi Kesalahan', text: response.message });
                        }
                    }
                });
                return false;
            }
        });
    });
</script>