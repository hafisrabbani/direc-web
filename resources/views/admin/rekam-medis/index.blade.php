@extends('admin.layout.main')

@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Data Pasien')
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/pages/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/pages/datatables.css') }}" />
@endsection
@section('content')
<div class="card-body">
    <form method="get" action="">
        <div class="row">
            <div class="col">
                <label for="order">Tampilkan</label>
                <select name="order" id="order" class="form-control" onchange="this.form.submit()">
                    <option value="10" {{ request()->order == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request()->order == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request()->order == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request()->order == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="cat">Penyakit</label>
                    <select name="cat" id="cat" class="form-control">
                        <option value="">Semua</option>
                        @foreach ($disiase as $item)
                        <option value="{{ $item->id }}" {{ $item->id == Request::get('cat') ? 'selected' : '' }}>{{
                            $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="start">Mulai</label>
                    <input type="date" name="start" id="start" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <label for="end">Sampai</label>
                    <input type="date" name="end" id="end" class="form-control">
                </div>
            </div>
            <div class="col">
                <div class="form-group">
                    <button type="submit" class="mt-4 btn btn-primary form-control" style="width: auto;"><i
                            class="bi bi-search"></i></button>
                    <a href="{{ route('rekam-list') }}" class="mt-4 btn btn-secondary" style="max-width: 75px;">X</a>
                </div>
            </div>
    </form>
    <table class="table" id="myTable">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th>Nama Pasien</th>
                <th>Penyakit</th>
                <th>Tagihan</th>
                <th>Tgl Masuk</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $item)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $item->pasien->nama }}</td>
                <td><span class="badge text-bg-primary">{{ $item->disiase->name }}</span></td>
                <td>{{ $item->tagihan }}</td>
                <td>{{ date('d-m-Y',strtotime($item->tgl_masuk)) }}</td>
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
<script src="{{ asset('assets/js/pages/datatables.js') }}"></script>

<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
@endpush
