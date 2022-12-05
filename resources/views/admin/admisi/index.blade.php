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
    <a href="{{ route('admisi.create',$pasien->id) }}" class="btn btn-primary"><strong>+</strong> Rekam Medis</a>
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
        </div>
        <div class="col-md-8">
            <table class="table" id="myTable">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th>Penyakit</th>
                        <th>Diagnosa</th>
                        <th>Tindakan</th>
                        <th>Tgl Masuk</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admisi as $item)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $item->disiase->name }}</td>
                        <td>{{ $item->diagnosa }}</td>
                        <td>{{ $item->tindakan }}</td>
                        <td>{{ date('d-m-Y',strtotime($item->tgl_masuk)) }}</td>
                        <td>
                            <div class="btn-group">

                                <a href="{{ route('admisi.edit',[$pasien->id,$item->id]) }}"
                                    class="btn btn-warning btn-sm"><i class="bi bi-pen"></i></a>
                            </div>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@push('js')
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>
<script src="{{ asset('assets/js/pages/datatables.jss') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
@endpush
