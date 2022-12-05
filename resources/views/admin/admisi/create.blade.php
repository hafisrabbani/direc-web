@extends('admin.layout.main')

@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Rekam Medis Pasien')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}" />
@endsection
@section('content')
<div class="card-body">
    <a href="{{ route('admisi',$pasien->id) }}" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
    <div class="row mt-3">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title text-white">{{ $pasien->nama }}</h4>
                        <ul class="list-unstyled">
                            <li>
                                <small>Jenis Kelamin : {{ $pasien->jenkel }}</small>
                            </li>
                            <li>
                                <small>Tgl Lahir : {{ date('d-m-Y',strtotime($pasien->tgl_lahir)) }} ({{
                                    App\Http\Controllers\AdmisiController::age($pasien->tgl_lahir) }} thn)</small>
                            </li>
                            <li>
                                <small>No telp : {{ $pasien->no_tlp }}</small>
                            </li>
                            <li>
                                <small>Alamat : {{ $pasien->alamat }}</small>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div id="preview"></div>
        </div>
        <div class="col-md-8">
            <form id="form" enctype="multipart/form-data">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="penyakit">Penyakit</label>
                            <select name="penyakit" id="penyakit" class="form-control">
                                <option selected disabled>-- Pilih Penyakit --</option>
                                @foreach($disiases as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="keluhan">Keluhan</label>
                            <textarea class="form-control" id="keluhan" name="keluhan" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="type">Tipe Gambar</label>
                            <select name="type" id="type" class="form-control">
                                <option selected disabled>-- Tipe Gambar --</option>
                                <option value="image">Upload Gambar</option>
                                <option value="drawing">Gambar</option>
                            </select>
                            <div id="extra"></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="diagnosa">Diagnosa</label>
                            <input type="text" class="form-control" id="diagnosa" name="diagnosa">
                        </div>
                        <div class="form-group">
                            <label for="tindakan">Tindakan</label>
                            <textarea class="form-control" id="tindakan" name="tindakan" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tgl">Tanggal</label>
                            <input type="date" class="form-control" id="tgl" name="tgl" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="tagihan">Tagihan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="number" class="form-control" id="tagihan" name="tagihan" value="0">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                    <p id="tes"></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function () {
        $(window).bind('storage', function (e) {
            if (e.originalEvent.key == 'canvas') {
                if ($('#preview').length) {
                    $('#preview img').attr('src', localStorage.getItem('canvas'));
                }
            }
        });
        var input = $('#type');
        input.on('change', function () {
            $('#extra').empty();
            $('#preview').empty();
            if (this.value == 'image') {
                $('#extra').append('<div class="form-group"><label for="image">Gambar</label><input type="file" class="form-control" id="image" name="image"></div>');
                $('#image').on('change', function () {
                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onloadend = function () {
                        $('#preview').html('<img src="' + reader.result + '" alt="preview" width="100%">');
                    }
                    reader.readAsDataURL(file);
                });
            } else if (this.value == 'drawing') {
                $('#extra').append('<a class="btn btn-primary my-2" href="{{ route("admisi.drawing",$pasien->id) }}" target="_blank">Manual</a>');
                $('#preview').append('<a><img src="' + localStorage.getItem('canvas') + '" class="img-fluid" alt="drawing"></a>');
                $('#extra').append('<input type="hidden" name="drawing" value="' + localStorage.getItem('canvas') + '">');
            }
        });
        $('#form').on('submit', function (e) {
            e.preventDefault();
            $('.err').remove();
            $.ajax({
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admisi.create',$pasien->id) }}",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (response) {
                    swal("Berhasil!", "Data berhasil ditambahkan!", "success");
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                    localStorage.removeItem('canvas');
                },
                error: function (err) {
                    if (err.status == 422) {
                        swal("Gagal!", "Data gagal ditambahkan!", "error");
                        $.each(err.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="err" style="color: red;">' + error[0] + '</span>'));
                        });
                    } else if (err.status == 500) {
                        swal("Gagal!", "Kesalahan Server", "error");
                    }
                }
            });
        });
    });
</script>
@endpush
