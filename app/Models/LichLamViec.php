<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LichLamViec extends Model
{
    public function getLichLamViec()
    {
        return DB::table('lich_lam_viec')
            ->join('nhan_vien', 'nhan_vien.id_nhanvien', '=', 'lich_lam_viec.id_nhanvien')
            ->join('thoi_gian_lam_viec', 'thoi_gian_lam_viec.id_thoi_gian_lam_viec', '=', 'lich_lam_viec.id_thoi_gian_lam_viec')
            ->join('tai_khoan', 'tai_khoan.id_taikhoan', '=', 'nhan_vien.id_taikhoan')
            ->select([
                'nhan_vien.id_nhanvien',
                'nhan_vien.ho_ten',
                'nhan_vien.chuc_vu',
                'nhan_vien.don_vi',
                'nhan_vien.trang_thai',
                'tai_khoan.so_dien_thoai',
                'tai_khoan.email',
                'thoi_gian_lam_viec.ten_thoi_gian_lam_viec',
                'thoi_gian_lam_viec.tg_bat_dau',
                'thoi_gian_lam_viec.tg_ket_thuc',
                'lich_lam_viec.ngay_lam',
            ])->get();
    }
}
