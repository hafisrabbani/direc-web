<?php

namespace App\Http\Controllers;

use App\Models\Admisi;
use Illuminate\Http\Request;
use App\Models\Disiase;

class RekamListController extends Controller
{
    public function index(Request $request)
    {
        $data = Admisi::where('user_id', auth()->user()->id)
            ->where(function ($query) use ($request) {
                if (!empty($request->all())) {
                    if ($request->cat != null) {
                        $query->where('id_disiase', $request->cat);
                    }
                    if ($request->start != null && $request->end != null) {
                        $query->whereBetween('created_at', [$request->start, $request->end]);
                    }
                }
            })
            ->get();

        return view('admin.rekam-medis.index', [
            'rekap' => $data,
            'disiase' => Disiase::where('user_id', auth()->user()->id)->get(['id', 'name']),
        ]);
    }
}
