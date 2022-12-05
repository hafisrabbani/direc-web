<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disiase;
use Illuminate\Support\Facades\Auth;

class DisiaseController extends Controller
{
    public function index()
    {
        $disiases = Disiase::where('user_id', auth()->user()->id)->get();
        return view('admin.disiase.index', ['disiases' => $disiases]);
    }

    public function createDisiase(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'string|nullable',
        ], [
            'name.required' => 'Nama disiase harus diisi',
            'description.string' => 'Deskripsi harus berupa string',
        ]);
        $userId = Auth::id();

        $disiase = Disiase::create([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' =>  $userId,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ($disiase) ? 'Disiase berhasil ditambahkan' : 'Disiase gagal ditambahkan',
        ], ($disiase) ? 200 : 500);
    }

    public function deleteDisiase($id)
    {
        $disiase = Disiase::find($id);
        $disiase->delete();

        return response()->json([
            'status' => 'success',
            'message' => ($disiase) ? 'Disiase berhasil dihapus' : 'Disiase gagal dihapus',
        ], ($disiase) ? 200 : 500);
    }

    public function edit($id)
    {

        $disiase = Disiase::findOrFail($id);
        if ($disiase->user_id != auth()->user()->id) {
            return abort(404);
        }
        return response()->json([
            'status' => 'success',
            'message' => ($disiase) ? 'Disiase berhasil ditemukan' : 'Disiase gagal ditemukan',
            'data' => $disiase,
        ], ($disiase) ? 200 : 500);
    }

    public function editDisiase(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'string|nullable',
        ], [
            'name.required' => 'Nama disiase harus diisi',
            'description.string' => 'Deskripsi harus berupa string',
        ]);

        $disiase = Disiase::find($id);
        if ($disiase->user_id != auth()->user()->id) {
            return abort(404);
        }
        $disiase->update([
            'name' => $request->name,
            'description' => $request->description,
            'user_id' => request()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => ($disiase) ? 'Disiase berhasil diubah' : 'Disiase gagal diubah',
        ], ($disiase) ? 200 : 500);
    }
}
