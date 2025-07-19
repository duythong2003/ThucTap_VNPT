<?php

namespace App\Http\Controllers;

use App\Models\LichLamViec;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function index()
    {
        $model = new LichLamViec();
        $schedules = $model->getLichLamViec();
        $employees = DB::table('nhan_vien')->select(['id_nhanvien', 'ho_ten'])->get();
        $workingTimes = DB::table('thoi_gian_lam_viec')
            ->select([
                'id_thoi_gian_lam_viec',
                'ten_thoi_gian_lam_viec',
                'tg_bat_dau',
                'tg_ket_thuc',
            ])->get();
        return view('pages.admin.schedule', [
            'schedules' => $schedules,
            'employees' => $employees,
            'workingTimes' => $workingTimes
        ]);
    }

    public function store(Request $request)
    {
        $id_nhanvien = $request->input('id_nhanvien');
        $ngay_lam = $request->input('ngay_lam');
        $id_thoi_gian_lam_viec = $request->input('id_thoi_gian_lam_viec');

        DB::table('lich_lam_viec')->insert([
            'id_nhanvien' => $id_nhanvien,
            'id_thoi_gian_lam_viec' => $id_thoi_gian_lam_viec,
            'ngay_lam' => $ngay_lam,
        ]);

        return Response::success('', 'Thêm lịch làm việc thành công !', 200);
    }

}
