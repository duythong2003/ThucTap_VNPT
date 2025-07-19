<?php

namespace App\Http\Controllers;

use App\Models\LichLamViec;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ScheduleController extends Controller
{
    public function index()
    {
        $employees = DB::table('nhan_vien')->select(['id_nhanvien', 'ho_ten'])->get();
        $workingTimes = DB::table('thoi_gian_lam_viec')
            ->select(['id_thoi_gian_lam_viec', 'ten_thoi_gian_lam_viec', 'tg_bat_dau', 'tg_ket_thuc'])
            ->get();

        return view('pages.admin.schedule', [
            'employees' => $employees,
            'workingTimes' => $workingTimes
        ]);
    }

    public function getSchedules(Request $request)
    {
        return DataTables::of(LichLamViec::getLichLamViec())
            ->addColumn('action', function ($row) {
                return '
                <button class="btn btn-sm btn-primary edit-btn" data-id="' . $row->id_lich_lam_viec . '"><i class="bi bi-pen"></i></button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id_lich_lam_viec . '"><i class="bi bi-trash"></i></button>
            ';
            })
            ->rawColumns(['action'])
            ->make(true);
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

    public function update(Request $request, $id_lich_lam_viec)
    {
        $id_nhanvien = $request->input('id_nhanvien');
        $ngay_lam = $request->input('ngay_lam');
        $id_thoi_gian_lam_viec = $request->input('id_thoi_gian_lam_viec');

        DB::table('lich_lam_viec')
            ->where('id_lich_lam_viec', $id_lich_lam_viec)
            ->update([
                'id_nhanvien' => $id_nhanvien,
                'ngay_lam' => $ngay_lam,
                'id_thoi_gian_lam_viec' => $id_thoi_gian_lam_viec,
            ]);

        return Response::success('', 'Cập nhật lịch làm việc thành công!', 200);
    }

    public function destroy($id)
    {
        DB::table('lich_lam_viec')
            ->where('id_lich_lam_viec', $id)
            ->delete();

        return Response::success('', 'Xoá lịch làm việc thành công!', 200);
    }

}
