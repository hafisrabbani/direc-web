<?php

namespace App\Http\Controllers;

use App\Models\Admisi;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $profitToday = DB::table('admisis')
            ->select(DB::raw('SUM(tagihan) as total'))
            ->whereDate('created_at', date('Y-m-d'))
            ->first();
        // $profitMonth = Db::table('admisis')
        //     ->select(DB::raw('SUM(tagihan) as total'))
        //     ->whereMonth('created_at', date('m'))
        //     ->where('user_id', auth()->user()->id)
        //     ->first();
        // $profitYear = Db::table('admisis')
        //     ->select(DB::raw('SUM(tagihan) as total'))
        //     ->whereYear('created_at', date('Y'))
        //     ->where('user_id', auth()->user()->id)
        //     ->first();
        // $profitAll = Db::table('admisis')
        //     ->select(DB::raw('SUM(tagihan) as total'))
        //     ->where('user_id', auth()->user()->id)
        //     ->first();
        $pasien = Pasien::where('user_id', auth()->user()->id)->count();
        $rekamMedis = Admisi::where('user_id', auth()->user()->id)->count();
        $groupRekam = Admisi::select(DB::raw('count(*) as total, id_disiase'))
            ->where('user_id', auth()->user()->id)
            ->groupBy('id_disiase')
            ->get();
        // dd($groupRekam);
        return view('admin.dashboard.main', [
            'profitToday' => $profitToday,
            // 'profitMonth' => $profitMonth,
            // 'profitYear' => $profitYear,
            // 'profitAll' => $profitAll,
            'pasien' => $pasien,
            'rekamMedis' => $rekamMedis,
            'groupRekam' => $groupRekam,
        ]);
    }
}
