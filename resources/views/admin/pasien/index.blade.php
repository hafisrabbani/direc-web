@extends('admin.layout.main')

@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Data Pasien')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}" />
@endsection
@section('content')
<a href="{{ route('pasien.create') }}" class="btn btn-primary">+ Pasien</a>
<div class="card-body">
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>City</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pasien as $item)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->nama }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->no_tlp }}</td>
                <td>{{ $item->alamat }}</td>
                <td>
                    <div class="btn-group">
                        <a href="{{ route('pasien.edit', $item->id) }}" class="btn btn-warning"><i
                                class="bi bi-pen"></i></a>
                        &nbsp;
                        <a href="{{ route('admisi',$item->id) }}" class="btn btn-info">
                            <i class="bi bi-archive"></i>
                        </a>
                        &nbsp;
                        <button class="btn btn-danger d-inline" onclick="delConfirm('{{ $item->id }}')"><i
                                class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@push('js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="assets/js/pages/datatables.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });

    function delConfirm(id) {
        swal({
            title: "Apakah anda yakin?",
            text: "Setelah dihapus, Anda tidak akan dapat memulihkan data ini!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    swal("Data berhasil dihapus!", {
                        icon: "success",
                    });
                    $.ajax({
                        url: '/pasien/delete/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function (response) {
                            swal("Data berhasil dihapus!", {
                                icon: "success",
                            }).then(function () {
                                location.reload();
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 1500);
                        }, error: function (error) {
                            swal("Data gagal dihapus!", {
                                icon: "error",
                            });
                        }
                    });
                }
            });
    }
</script>
@endpush
