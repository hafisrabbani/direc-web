@extends('admin.layout.main')
@section('title', 'Halaman Pasien')
@section('sub-title', 'Manajemen Data Pasien')
@section('css')
<link rel="stylesheet" href="{{ asset('drawing/style.css') }}">
<script src="{{ asset('drawing/script.js') }}" defer></script>
<script src="https://kit.fontawesome.com/1d954ea888.js"></script>
@endsection
@section('content')
<div class="row">
    <dic class="col-2 toolkit">
        <h4 class="text-center py-2">Drawing Tools</h4>
        <div class="container">
            <ul class="list-unstyled">
                <li class="option tools actived" id="brush">
                    <div class="form-group">
                        <i class="fas fa-paint-brush"></i> Brush
                    </div>
                </li>
                <li class="option tools" id="eraser">
                    <div class="form-group"><i class="fas fa-eraser"></i> Eraser</div>
                </li>
                <li class="option">
                    <div class="form-group">
                        <span>Size</span>
                        <input type="range" class="form-control-range" id="size" min="1" max="20" value="10">
                    </div>
                </li>
                <li class="option">
                    <!-- input color -->
                    <div class="form-group">
                        <span>Brush Color</span>
                        <input type="color" class="form-control" id="brushColor" value="#000000">
                    </div>
                </li>
            </ul>
            <div class="row mb-2">
                <div class="btn-group d-inline text-center">
                    <button class="btn btn-secondary" onclick="undo()"><i class="fas fa-undo"></i></button>
                    <button class="btn btn-secondary"><i class="fas fa-redo"></i></button>
                </div>
            </div>
            <button class="btn btn-primary w-100" onclick="save()">Save</button>
            <button class="btn btn-info w-100 mt-2" onclick="load()">Load</button>
            <button class="btn btn-danger mt-2 w-100" onclick="reset()">Reset</button>
        </div>
    </dic>
    <div class="col-10">
        <div class="draw">
            <canvas></canvas>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.1.min.js"
    integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endpush
