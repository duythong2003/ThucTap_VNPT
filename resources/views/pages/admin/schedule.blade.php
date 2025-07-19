@extends('layouts.admin')

@section('title', 'Lịch làm việc')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-success" onclick="showModal('#modal-add', 'Thêm lịch làm việc')">
                    <i class="bi bi-calendar-plus me-2"></i>
                    <span>Thêm lịch làm việc</span>
                </button>
            </div>
            <div class="card-body">
                <table id="schedules-table"
                       class="display nowrap w-100 table table-striped table-hover table-head-fixed table-bordered">
                    <thead>
                    <tr>
                        <th>STT</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chức vụ</th>
                        <th>Đơn vị</th>
                        <th>Ngày làm</th>
                        <th>Ca làm</th>
                        <th>Thời gian bắt đầu - kết thúc</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($schedules as $index=>$schedule)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$schedule->ho_ten}}</td>
                            <td>{{$schedule->email}}</td>
                            <td>{{$schedule->so_dien_thoai}}</td>
                            <td>{{$schedule->chuc_vu}}</td>
                            <td>{{$schedule->don_vi}}</td>
                            <td>{{\Carbon\Carbon::parse($schedule->ngay_lam)->format('d-m-Y')}}</td>
                            <td>{{$schedule->ten_thoi_gian_lam_viec}}</td>
                            <td>
                                {{\Carbon\Carbon::parse($schedule->tg_bat_dau)->format('H:i')}}
                                -
                                {{\Carbon\Carbon::parse($schedule->tg_ket_thuc)->format('H:i')}}
                            </td>
                            <td>{{$schedule->trang_thai}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--    Modal add    --}}
        <x-modal id="modal-add" title="Modal title">
            <form id="form-add" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="id_nhanvien" class="form-label">Nhân viên</label>
                    <select id="id_nhanvien" class="form-select">
                        <option selected>Chọn nhân viên</option>
                        @foreach($employees as $employee)
                            <option value="{{$employee->id_nhanvien}}">{{$employee->ho_ten}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="ngay_lam" class="form-label">Ngày làm việc</label>
                    <input type="date" class="form-control" id="ngay_lam">
                </div>
                <div class="mb-3">
                    <label for="id_thoi_gian_lam_viec">Thời gian làm việc</label>
                    <select id="id_thoi_gian_lam_viec" class="form-select">
                        <option selected>Chọn thời gian làm viêc</option>
                        @foreach($workingTimes as $workingTime)
                            <option value="{{$workingTime->id_thoi_gian_lam_viec}}">
                                {{$workingTime->ten_thoi_gian_lam_viec}}
                                (
                                {{\Carbon\Carbon::parse($workingTime->tg_bat_dau)->format('H:i')}}
                                -
                                {{\Carbon\Carbon::parse($workingTime->tg_ket_thuc)->format('H:i')}}
                                )
                            </option>
                        @endforeach
                    </select>
                </div>
                <x-slot:footer>
                    <button type="submit" form="form-add" class="btn btn-primary" id="modal-add-save">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </x-slot:footer>
            </form>
        </x-modal>

    </div>
    <script>
        $(document).ready(function () {
            $('#schedules-table').DataTable({
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
                },
                layout: {
                    bottomEnd: {
                        paging: {
                            firstLast: false
                        }
                    }
                }
            });
        });

        $(document).ready(function () {
            $("#form-add").submit(function (e) {
                e.preventDefault();
                const data = {
                    _token: "{{ csrf_token() }}",
                    id_nhanvien: $("#id_nhanvien").val(),
                    ngay_lam: $("#ngay_lam").val(),
                    id_thoi_gian_lam_viec: $("#id_thoi_gian_lam_viec").val()
                };
                $.ajax({
                    type: "POST",
                    url: "{{route('schedule.store')}}",
                    data: data,
                    dataType: "json",
                    cache: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-add').modal('hide');
                            $('#form-add')[0].reset();
                            window.location.reload()
                        }
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            });
        });


        function showModal(modalId, title = '') {
            const modalEl = document.querySelector(modalId);
            const modal = new bootstrap.Modal(modalEl);

            const titleEl = modalEl.querySelector('.modal-title');
            if (titleEl) {
                titleEl.textContent = title;
            }

            modal.show();
        }
    </script>
    <style>
        .page-link {
            padding: 5px 10px !important;
            font-size: 14px !important;
        }
    </style>
@endsection
