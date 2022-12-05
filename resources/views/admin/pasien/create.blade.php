@extends('admin.layout.main')
@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Data Pasien')
@section('css')
@endsection
@section('content')
<a href="{{ URL::previous() }}">
    <h3 class="badge bg-primary"><i class="bi bi-arrow-left"></i></h3>
</a>

<form id="create">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="alamat" class="form-control">
            </div>
            <div class="form-group">
                <label>Tgl Lahir</label>
                <div class="row g-0">
                    <div class="col">
                        <input type="number" name="day" class="form-control" min="1" max="31" placeholder="dd">
                    </div>
                    <div class="col">
                        <input type="number" name="month" class="form-control" min="1" max="12" placeholder="mm">
                    </div>
                    <div class="col">
                        <input type="number" name="year" class="form-control" min="1900" placeholder="yyyy">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="jenkel">Jenis Kelamin</label>
                <select name="jenkel" id="jenkel" class="form-control">
                    <option selected disabled>-- Pilih Jenis Kelamin --</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
            <div class="form-group">
                <label for="nohp">No. HP</label>
                <input type="text" name="no_tlp" id="nohp" class="form-control">
            </div>
            <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea name="alamat" id="alamat" class="form-control" rows="5"></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary form-control mt-2">Submit</button>
</form>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        $('input[name="year"').attr('max', new Date().getFullYear());
        $('#create').submit(function (e) {
            e.preventDefault();
            var data = $(this).serialize();
            $('.err').hide();
            $.ajax({
                url: "{{ route('pasien.create') }}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                data: data,
                success: function (response) {
                    swal({
                        title: "Berhasil!",
                        text: "Data berhasil ditambahkan!",
                        icon: "success",
                        button: "OK",
                    }).then(function () {
                        location.reload();
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                }, error: function (err) {
                    if (err.status == 422) {
                        swal({
                            title: 'Gagal',
                            text: 'Pastikan inputan anda benar',
                            icon: 'error'
                        });
                        $.each(err.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="err" style="color: red;">' + error[0] + '</span>'));
                        });
                    }
                }
            });
        });
    });
</script>
@endpush
