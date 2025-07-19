@extends('layouts.admin')

@section('title', 'Lịch làm việc')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <button class="btn btn-success" onclick="showModal('#modal-schedule', 'Thêm lịch làm việc')">
                    <i class="bi bi-calendar-plus me-2"></i>
                    <span>Thêm lịch làm việc</span>
                </button>
            </div>
            <div class="card-body">
                <table id="schedule-table"
                       class="display nowrap w-100 table table-striped table-hover table-head-fixed table-bordered">
                    <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>SĐT</th>
                        <th>Chức vụ</th>
                        <th>Đơn vị</th>
                        <th>Ngày làm</th>
                        <th>Ca làm</th>
                        <th>Thời gian bắt đầu</th>
                        <th>Thời gian kết thúc</th>
                        <th>Trạng thái</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

        {{--    Modal add    --}}
        <x-modal id="modal-schedule" title="Modal title">
            <form id="form-schedule">
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
                    <button type="submit" form="form-schedule" class="btn btn-primary">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </x-slot:footer>
            </form>
        </x-modal>

        <x-modal id="modal-delete" title="Xóa lịch làm việc">
            <div class="text-danger">Bạn có chắc chắn xóa lịch làm việc không ?</div>
            <x-slot:footer>
                <button type="submit" id="confirm-delete" class="btn btn-primary">Xác nhận</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </x-slot:footer>
        </x-modal>

    </div>
    <script>
        $(document).ready(function () {
            $('#schedule-table').DataTable({
                processing: true,
                serverSide: false,
                responsive: true,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/vi.json'
                },
                ajax: '{{ route('schedule.data') }}',
                columns: [
                    {data: 'ho_ten'},
                    {data: 'chuc_vu'},
                    {data: 'don_vi'},
                    {data: 'so_dien_thoai'},
                    {data: 'email'},
                    {data: 'ngay_lam'},
                    {data: 'ten_thoi_gian_lam_viec'},
                    {data: 'tg_bat_dau'},
                    {data: 'tg_ket_thuc'},
                    {data: 'trang_thai'},
                    {data: 'action', orderable: false, searchable: false}
                ],
                layout: {
                    bottomEnd: {
                        paging: {
                            firstLast: false
                        }
                    }
                },
            });
        });

        let isUpdate = false;
        let currentEditId = null;

        $(document).ready(function () {
            $("#form-schedule").submit(function (e) {
                e.preventDefault();
                const data = {
                    _token: "{{ csrf_token() }}",
                    id_nhanvien: $("#id_nhanvien").val(),
                    ngay_lam: $("#ngay_lam").val(),
                    id_thoi_gian_lam_viec: $("#id_thoi_gian_lam_viec").val()
                };

                let method = isUpdate ? "PUT" : "POST";
                let url = isUpdate ? `/lich-lam-viec/${currentEditId}` : `{{ route('schedule.store') }}`;

                $.ajax({
                    type: method,
                    url: url,
                    data: data,
                    dataType: "json",
                    cache: false,
                    success: function (response) {
                        if (response.status) {
                            $('#modal-schedule').modal('hide');
                            $('#form-schedule')[0].reset();
                            $('#schedule-table').DataTable().ajax.reload();
                            toastr.success(isUpdate ? 'Cập nhật thành công' : 'Thêm thành công')

                            // Reset trạng thái về thêm mới
                            isUpdate = false;
                            currentEditId = null;
                        }
                    },
                    error: function (err) {
                        console.log(err)
                    }
                });
            });

            $('#schedule-table').on('click', '.edit-btn', function () {
                showModal('#modal-schedule', 'Cập nhật lịch làm việc');

                const table = $('#schedule-table').DataTable();
                const rowData = table.row($(this).closest('tr')).data();

                currentEditId = rowData.id_lich_lam_viec;
                isUpdate = true;

                $('#id_nhanvien').val(rowData.id_nhanvien);
                $('#ngay_lam').val(rowData.ngay_lam);
                $('#id_thoi_gian_lam_viec').val(rowData.id_thoi_gian_lam_viec);
            });
        });


        $('#schedule-table').on('click', '.delete-btn', function () {
            showModal('#modal-delete', 'Xóa lịch làm việc')
            deleteId = $(this).data('id');
            $('#confirm-delete').on('click', function () {
                if (!deleteId) return;

                $.ajax({
                    url: `/lich-lam-viec/${deleteId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('#schedule-table').DataTable().ajax.reload(null, false);
                        $('#modal-delete').modal('hide');
                        toastr.success('Xóa thành công')
                    },
                    error: function (err) {
                        console.error(err);
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
