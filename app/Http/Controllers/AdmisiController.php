<?php

namespace App\Http\Controllers;

use App\Models\Admisi;
use App\Models\Pasien;
use Illuminate\Http\Request;
use App\Models\Disiase;
use Illuminate\Support\Facades\DB;

class AdmisiController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $data = DB::table('pasiens')
                ->select('id')
                ->where('user_id', auth()->user()->id)
                ->where('id', request()->route('pasien'))
                ->get();
            if (!Pasien::find(request()->route('pasien')) && $data->isEmpty()) {
                abort(404);
            }
            return $next($request);
        });
    }
    public function getUserInfo()
    {
        $data = DB::table('pasiens')
            ->select('id')
            ->where('user_id', auth()->user()->id)
            ->where('id', request()->route('pasien'))
            ->first();

        return $data;
    }
    public function index($pasien)
    {
        return view('admin.admisi.index', [
            'pasien' => Pasien::find($pasien),
            'admisi' => Admisi::where('pasien_id', $pasien)->orderBy('created_at', 'desc')->get(),
        ]);
    }

    public function create($pasien)
    {
        return view('admin.admisi.create', [
            'pasien' => Pasien::find($pasien),
            'disiases' => Disiase::where('user_id', auth()->user()->id)->get(['id', 'name']),
        ]);
    }

    public static function age($date)
    {
        $date = new \DateTime($date);
        $now = new \DateTime();
        $interval = $now->diff($date);
        return $interval->y;
    }

    public function createAdmisi(Request $request, $pasien)
    {
        $request->validate([
            'penyakit' => 'required',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'tagihan' => 'required|numeric',
            'type' => 'required',
            'image' => 'required_if:type,image|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'drawing' => 'required_if:type,text',
        ], [
            'penyakit.required' => 'Penyakit Harus Diisi',
            'keluhan.required' => 'Keluhan Harus Diisi',
            'keluhan.string' => 'Keluhan Harus Berupa String',
            'diagnosa.required' => 'Diagnosa Harus Diisi',
            'diagnosa.string' => 'Diagnosa Harus Berupa String',
            'tindakan.required' => 'Tindakan Harus Diisi',
            'tindakan.string' => 'Tindakan Harus Berupa String',
            'tagihan.required' => 'Tagihan Harus Diisi',
            'tagihan.numeric' => 'Tagihan Harus Berupa Angka',
            'type.required' => 'Type Harus Diisi',
            'image.required_if' => 'Image Harus Diisi',
            'image.image' => 'Image Harus Berupa Gambar',
            'image.mimes' => 'Image Harus Berupa Gambar',
            'image.max' => 'Image Harus Berupa Gambar',
            'drawing.required_if' => 'Drawing Harus Diisi',
        ]);

        if ($request->type == 'image') {
            $imageName = time() . '.' . $request->image->extension();
            $request->file('image')->storeAs('public/img-admisi', $imageName);
        } else {
            $imageName = $request->drawing;
        }

        $data = Admisi::create([
            'pasien_id' => $pasien,
            'id_disiase' => $request->penyakit,
            'keluhan' => $request->keluhan,
            'diagnosa' => $request->diagnosa,
            'tindakan' => $request->tindakan,
            'tgl_masuk' => $request->tgl,
            'tagihan' => (!$request->tagihan) ? 0 : $request->tagihan,
            'type' => $request->type,
            'image' => $imageName,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ($data) ? 'Data Berhasil Ditambahkan' : 'Data Gagal Ditambahkan',
            'data' => $data,
        ], ($data) ? 200 : 400);
    }

    public function deleteAdmisi($id)
    {
        $admisi = Admisi::find($id);
        $admisi->delete();
        return response()->json([
            'status' => ($admisi) ? true : false,
            'message' => ($admisi) ? 'Data berhasil dihapus' : 'Data gagal dihapus',
        ], ($admisi) ? 200 : 400);
    }

    public function edit($pasien, $id)
    {
        return view('admin.admisi.edit', [
            'pasien' => Pasien::select([
                'id'
            ])->findOrFail($pasien),
            'admisi' => Admisi::find($id),
            'disiases' => Disiase::where('user_id', auth()->user()->id)->get(['id', 'name']),
        ]);
    }

    public function editAdmisi(Request $request, $pasien, $id)
    {
        $request->validate([
            'penyakit' => 'required',
            'keluhan' => 'required|string',
            'diagnosa' => 'required|string',
            'tindakan' => 'required|string',
            'tagihan' => 'required|numeric',
            'type' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'drawing' => 'text',
        ], [
            'penyakit.required' => 'Penyakit Harus Diisi',
            'keluhan.required' => 'Keluhan Harus Diisi',
            'keluhan.string' => 'Keluhan Harus Berupa String',
            'diagnosa.required' => 'Diagnosa Harus Diisi',
            'diagnosa.string' => 'Diagnosa Harus Berupa String',
            'tindakan.required' => 'Tindakan Harus Diisi',
            'tindakan.string' => 'Tindakan Harus Berupa String',
            'tagihan.required' => 'Tagihan Harus Diisi',
            'tagihan.numeric' => 'Tagihan Harus Berupa Angka',
            'type.required' => 'Type Harus Diisi',
            'image.image' => 'Image Harus Berupa Gambar',
            'image.mimes' => 'Image Harus Berupa Gambar',
            'image.max' => 'Image Harus Berupa Gambar',
        ]);

        if (!$request->hasFile('image') && $request->drawing == null) {
            $imageName = Admisi::find($id)->image;
        } else {
            if ($request->type == 'image') {
                $imageName = time() . '.' . $request->image->extension();
                $request->file('image')->storeAs('public/img-admisi', $imageName);
            } else {
                $imageName = $request->drawing;
            }
        }

        $data = Admisi::where('id', $id)->update([
            'pasien_id' => $pasien,
            'id_disiase' => $request->penyakit,
            'keluhan' => $request->keluhan,
            'diagnosa' => $request->diagnosa,
            'tindakan' => $request->tindakan,
            'tgl_masuk' => $request->tgl,
            'tagihan' => (!$request->tagihan) ? 0 : $request->tagihan,
            'type' => $request->type,
            'image' => $imageName,
            'user_id' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ($data) ? 'Data Berhasil Diubah' : 'Data Gagal Diubah',
            'data' => $data,
        ], ($data) ? 200 : 400);
    }

    public function drawing()
    {
        return view('admin.admisi.drawing');
    }
}
