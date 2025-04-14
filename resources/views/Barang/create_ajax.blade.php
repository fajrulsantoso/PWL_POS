<form action="{{ url('/barang/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="example-modal-label">Tambah Data Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @csrf
                <div class="form-group">
                    <label>Kategori</label>
                    <select class="form-control" id="kategori_id" name="kategori_id" required>
                        <option value="">- Pilih Kategori -</option>
                        @foreach ($kategori as $item)
                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_nama }}</option>
                        @endforeach
                    </select>
                    <small id="error-kategori_id" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Kode Barang</label>
                    <input type="text" name="barang_kode" id="barang_kode" class="form-control" required>
                    <small id="error-barang_kode" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <input type="text" name="barang_nama" id="barang_nama" class="form-control" required>
                    <small id="error-barang_nama" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Beli</label>
                    <input type="number" class="form-control" id="harga_beli" name="harga_beli" required min="1">
                    <small id="error-harga_beli" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Harga Jual</label>
                    <input type="number" class="form-control" id="harga_jual" name="harga_jual" required min="1">
                    <small id="error-harga_jual" class="form-text text-danger"></small>
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
        $("#form-tambah").submit(function (event) {
            event.preventDefault(); // Mencegah form dari submit biasa

            let formData = new FormData(this);
            
            $.ajax({
                url: $(this).attr("action"),
                type: $(this).attr("method"),
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: () => {
                    $(".error-text").text(""); // Reset error sebelum request
                },
                success: (response) => {
                    if (response.status) {
                        $("#my-modal").modal("hide");
                        Swal.fire({ icon: "success", title: "Berhasil", text: response.message });
                        $("#form-tambah")[0].reset(); // Reset form setelah berhasil
                    } else {
                        $.each(response.message_field, (prefix, val) => {
                            $("#error-" + prefix).text(val[0]);
                        });
                        Swal.fire({ icon: "error", title: "Terjadi Kesalahan", text: response.message });
                    }
                },
                error: (xhr) => {
                    Swal.fire({ icon: "error", title: "Terjadi Kesalahan", text: "Gagal mengirim data!" });
                }
            });
        });
    });
</script>
