<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    public function index()
    {
        $data = Pasien::where('user_id', auth()->user()->id)->get();
        return view('admin.pasien.index', [
            'pasien' => $data,
        ]);
    }

    public function create()
    {
        return view('admin.pasien.create');
    }

    public function createPasien(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'email|unique:pasiens|max:255',
            'day' => 'required|numeric',
            'month' => 'required|numeric',
            'year' => 'required|numeric',
            'jenkel' => 'required|in:L,P',
            'no_tlp' => 'numeric',
            'alamat' => 'required|string',
        ], [
            'nama.required' => 'Nama Harus Diisi',
            'nama.string' => 'Nama Harus Berupa String',
            'nama.max' => 'Nama Maksimal 255 Karakter',
            'email.email' => 'Email Harus Berupa Email',
            'email.unique' => 'Email Sudah Terdaftar',
            'email.max' => 'Email Maksimal 255 Karakter',
            'day.required' => 'Tanggal Lahir Harus Diisi',
            'day.numeric' => 'Tanggal Lahir Harus Berupa Angka',
            'month.required' => 'Bulan Lahir Harus Diisi',
            'month.numeric' => 'Bulan Lahir Harus Berupa Angka',
            'year.required' => 'Tahun Lahir Harus Diisi',
            'year.numeric' => 'Tahun Lahir Harus Berupa Angka',
            'jenkel.required' => 'Jenis Kelamin Harus Diisi',
            'jenkel.in' => 'Jenis Kelamin Harus L/P',
            'no_tlp.numeric' => 'Nomor Telepon Harus Berupa Angka',
            'alamat.required' => 'Alamat Harus Diisi',
            'alamat.string' => 'Alamat Harus Berupa String',
        ]);
        $date = $request->year . '-' . $request->month . '-' . $request->day;
        $pasien = Pasien::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'tgl_lahir' => $date,
            'alamat' => $request->alamat,
            'no_tlp' => $request->no_tlp,
            'jenkel' => $request->jenkel,
            'tanggal_lahir' => $request->tanggal_lahir,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => ($pasien) ? true : false,
            'message' => ($pasien) ? 'Data berhasil ditambahkan' : 'Data gagal ditambahkan',
        ], ($pasien) ? 200 : 400);
    }

    public function edit($id)
    {
        $data = Pasien::findOrFail($id)->toArray();
        if ($data['user_id'] != auth()->user()->id) {
            return abort(404);
        }
        $data['day'] = date('d', strtotime($data['tgl_lahir']));
        $data['month'] = date('m', strtotime($data['tgl_lahir']));
        $data['year'] = date('Y', strtotime($data['tgl_lahir']));
        return view('admin.pasien.edit', [
            'pasien' => $data
        ]);
    }

    public function editPasien(Request $request, $id)
    {
        $data = Pasien::findOrFail($id)->toArray();
        if ($data['user_id'] != auth()->user()->id) {
            return abort(404);
        }
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:pasiens,email,' . $id,
            'day' => 'required|numeric',
            'month' => 'required|numeric',
            'year' => 'required|numeric',
            'jenkel' => 'required|in:L,P',
            'no_tlp' => 'numeric',
            'alamat' => 'required|string',
        ], [
            'nama.required' => 'Nama Harus Diisi',
            'nama.string' => 'Nama Harus Berupa String',
            'nama.max' => 'Nama Maksimal 255 Karakter',
            'email.unique' => 'Email Sudah Terdaftar',
            'email.email' => 'Email Harus Berupa Email',
            'email.max' => 'Email Maksimal 255 Karakter',
            'day.required' => 'Tanggal Lahir Harus Diisi',
            'day.numeric' => 'Tanggal Lahir Harus Berupa Angka',
            'month.required' => 'Bulan Lahir Harus Diisi',
            'month.numeric' => 'Bulan Lahir Harus Berupa Angka',
            'year.required' => 'Tahun Lahir Harus Diisi',
            'year.numeric' => 'Tahun Lahir Harus Berupa Angka',
            'jenkel.required' => 'Jenis Kelamin Harus Diisi',
            'jenkel.in' => 'Jenis Kelamin Harus L/P',
            'no_tlp.numeric' => 'Nomor Telepon Harus Berupa Angka',
            'alamat.required' => 'Alamat Harus Diisi',
            'alamat.string' => 'Alamat Harus Berupa String',
        ]);
        $date = $request->year . '-' . $request->month . '-' . $request->day;
        $pasien = Pasien::findOrFail($id)->update(
            [
                'nama' => $request->nama,
                'email' => $request->email,
                'tgl_lahir' => $date,
                'alamat' => $request->alamat,
                'no_tlp' => $request->no_tlp,
                'jenkel' => $request->jenkel,
                'tanggal_lahir' => $request->tanggal_lahir,
            ]
        );
        return response()->json([
            'status' => ($pasien) ? true : false,
            'message' => ($pasien) ? 'Data berhasil diubah' : 'Data gagal diubah',
        ], ($pasien) ? 200 : 400);
    }

    public function deletePasien($id)
    {
        $pasien = Pasien::findOrFail($id);
        if ($pasien->user_id != auth()->user()->id) {
            return abort(404);
        }
        $pasien->delete();
        return response()->json([
            'status' => ($pasien) ? true : false,
            'message' => ($pasien) ? 'Data berhasil dihapus' : 'Data gagal dihapus',
        ], ($pasien) ? 200 : 400);
    }
}
