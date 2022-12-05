@extends('admin.layout.main')

@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Data Penyakit')
@section('css')
<link rel="stylesheet" href="{{ asset('/assets/css/pages/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/assets/css/pages/datatables.css') }}" />
@endsection
@section('content')
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
    + Penyakit
</button>


<div class="card-body">
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th>Nama Penyakit</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($disiases as $item)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->name }}</td>
                <td>
                    <div class="btn-group">
                        <button class="btn btn-warning" onclick="showEdit('{{ $item->id }}')"><i class="bi bi-pen"></i>
                        </button>
                        &nbsp;
                        <button class="btn btn-danger d-inline" onclick="delConfirm('{{ $item->id }}')"><i
                                class="bi bi-trash"></i></button>
                    </div>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


<!-- Modal Edit -->
<div class="modal fade text-left" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Edit Data
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="name">Nama Penyakit</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Penyakit">
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Edit -->

<!-- Modal Create -->
<div class="modal fade text-left" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel160"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title white" id="myModalLabel160">Tambah Data
                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="create">
                    <div class="form-group">
                        <label for="name">Nama Penyakit</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama Penyakit">
                    </div>
                    <div class="form-group">
                        <label for="description">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    <i class="bx bx-x d-block d-sm-none"></i>
                    <span class="d-none d-sm-block">Close</span>
                </button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Create -->
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="{{ asset('assets/js/pages/datatables.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();

        $('#create').submit(function (e) {
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('disiase.create') }}",
                type: "POST",
                data: $('#create').serialize(),
                success: function (response) {
                    swal({
                        title: "Berhasil!",
                        text: "Data Berhasil Ditambahkan!",
                        icon: "success",
                        button: "Ok!",
                    }).then(function () {
                        location.reload();
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (error) {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Ditambahkan!",
                        icon: "error",
                        button: "Ok!",
                    });
                    if (error.status == 422) {
                        console.log(error.responseJSON);
                        $('#create').find('span.error-text').text('');
                        $.each(error.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="err" style="color: red;">' + error[0] + '</span>'));
                        });
                    }
                }
            });
        });

        $('#edit').submit(function (e) {
            e.preventDefault();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('disiase.edit','') }}" + '/' + $('#edit').find('input[name="id"]').val(),
                type: "POST",
                data: $('#edit').serialize(),
                success: function (response) {
                    swal({
                        title: "Berhasil!",
                        text: "Data Berhasil Diubah!",
                        icon: "success",
                        button: "Ok!",
                    }).then(function () {
                        location.reload();
                    });
                    setTimeout(function () {
                        location.reload();
                    }, 2000);
                },
                error: function (error) {
                    swal({
                        title: "Gagal!",
                        text: "Data Gagal Diubah!",
                        icon: "error",
                        button: "Ok!",
                    });
                    if (error.status == 422) {
                        console.log(error.responseJSON);
                        $('#edit').find('span.error-text').text('');
                        $.each(error.responseJSON.errors, function (i, error) {
                            var el = $(document).find('[name="' + i + '"]');
                            el.after($('<span class="err" style="color: red;">' + error[0] + '</span>'));
                        });
                    }
                }
            });
        });

    });


    function showEdit(id) {
        var modalEdit = new bootstrap.Modal(document.getElementById('editModal'), {});
        $.ajax({
            url: "{{ route('disiase.edit','') }}" + '/' + id,
            type: "GET",
            success: function (response) {
                $('#edit').find('input[name="id"]').val(response.data.id);
                $('#edit').find('input[name="name"]').val(response.data.name);
                $('#edit').find('textarea[name="description"]').val(response.data.description);
                modalEdit.show();
            }
        });
    }

    function delConfirm(id) {
        swal({
            title: "Apakah Anda Yakin?",
            text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('disiase.delete','') }}" + '/' + id,
                    type: "DELETE",
                    success: function (response) {
                        swal({
                            title: "Berhasil!",
                            text: "Data Berhasil Dihapus!",
                            icon: "success",
                            button: "Ok!",
                        }).then(function () {
                            location.reload();
                        });
                        setInterval(function () {
                            location.reload();
                        }, 2000);
                    },
                    error: function (error) {
                        swal({
                            title: "Gagal!",
                            text: "Data Gagal Dihapus!",
                            icon: "error",
                            button: "Ok!",
                        });
                    }
                });
            } else {
                swal("Data Anda Aman!");
            }
        });
    }
</script>
@endpush
