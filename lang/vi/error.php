<?php

return [
    'toggle_status' => 'Thay đổi trạng thái thất bại.',
    'delete' => 'Xóa lựa chọn thất bại.',
    'appendix_contract' => [
        'store' => 'Thêm mới phụ lục hợp đồng thất bại.',
        'update' => 'Chỉnh sửa phụ lục hợp đồng thất bại.',
    ],
    'role' => [
        'store' => 'Thêm mới vai trò thất bại.',
        'update' => 'Chỉnh sửa vai trò thất bại.',
    ],
    'user' => [
        'store' => 'Thêm mới người dùng thất bại.',
        'update' => 'Chỉnh sửa người dùng thất bại.',
        'is_admin' => 'Không thể đặt lại mật khẩu cho quản trị viên cấp cao và quản trị viên.',
    ],
    'candidate' => [
        'store' => 'Thêm mới ứng viên thất bại.',
        'update' => 'Chỉnh sửa ứng viên thất bại.',
    ],
    'customer' => [
        'store' => 'Thêm mới khách hàng thất bại.',
        'update' => 'Chỉnh sửa khách hàng thất bại.',
        'exists_in_project'  => 'Mã khách hàng đang tồn tại trong dự án. Bạn không thể xoá.',
    ],
    'contract_type' => [
        'store' => 'Thêm mới loại hợp đồng thất bại.',
        'update' => 'Chỉnh sửa loại hợp đồng thất bại.',
        'contracts_count' => 'Không thể xóa vì đã có hợp đồng được tạo.',
    ],
    'contract_attribute' => [
        'store' => 'Thêm mới thuộc tính hợp đồng thất bại.',
        'update' => 'Chỉnh sửa thuộc tính hợp đồng thất bại.',
        'delete' => 'Xóa thuộc tính hợp đồng thất bại.',
        'contract_types_count' => 'Không thể xóa vì đã có loại hợp đồng sử dụng.',
    ],
    'contract' => [
        'store' => 'Thêm mới hợp đồng thất bại.',
        'update_status' => 'Chỉnh sửa trạng thái hợp đồng thất bại.',
    ],
    'password' => [
        'update' => 'Cập nhật thông tin mật khẩu thất bại.',
    ],
    'template' => [
        'store' => 'Thêm mới mẫu email thất bại.',
        'update' => 'Chỉnh sửa mẫu email thất bại.',
    ],
    'template_email' => [
        'send' => 'Gửi email thất bại.',
        'empty' => 'Không có đối tượng nào được chọn.',
    ],
    'domain' => [
        'store' => 'Thêm mới tên miền thất bại.',
        'update' => 'Chỉnh sửa tên miền thất bại.',
        'delete' => 'Xóa tên miền thất bại.',
    ],
    'project' => [
        'store' => 'Thêm mới dự án thất bại.',
        'update' => 'Chỉnh sửa dự án thất bại.',
        'delete' => 'Xóa dự án thất bại.',
        'foreign_key' => 'Dự án hiện đang sử dụng một tên miền tồn tại.',
    ],
    'project_employee_role' => [
        'update' => 'Cập nhật nhân viên dự án thất bại.',
    ],
    'domain_accounts' => [
        'update' => 'Chỉnh sửa tài khoản của tên miền thất bại.',
    ],
    'send_email' => [
        'customer' => 'Gửi email tới khách hàng thất bại.',
    ],
    'salary' => [
        'store' => 'Thêm mới lương thất bại.',
        'update' => 'Chỉnh sửa lương thất bại.',
    ],
    'department' => [
        'store' => 'Thêm mới phòng ban thất bại.',
        'update' => 'Chỉnh sửa phòng ban thất bại.',
        'delete' => 'Xóa phòng ban thất bại.',
        'foreign_key' => 'Không thể xóa vì tồn tại danh sách nhân viên.',
    ],
    'title' => [
        'store' => 'Thêm mới vị trí thất bại.',
        'update' => 'Chỉnh sửa vị trí thất bại.',
        'user_foreign_key' => 'Không thể xóa vì tồn tại danh sách nhân viên.',
        'candidate_foreign_key' => 'Không thể xóa vì tồn tại danh sách ứng viên.',
    ],
    'project_type' => [
        'store' => 'Thêm mới loại dự án thất bại.',
        'update' => 'Chỉnh sửa loại dự án thất bại.',
        'delete' => 'Xóa loại dự án thất bại.',
        'projects_count' => 'Không thể xóa vì tồn tại danh sách dự án.',
    ],
    'income_and_expense_type' => [
        'store' => 'Thêm mới loại thu phí thất bại.',
        'update' => 'Chỉnh sửa loại thu phí thất bại.',
        'delete' => 'Xóa loại thu phí thất bại.',
    ],
    'income_and_expense' => [
        'store' => 'Thêm mới thu phí thất bại.',
        'update' => 'Chỉnh sửa thu phí thất bại.',
        'delete' => 'Xóa thu phí thất bại.',
    ],
    'employee_type' => [
        'store' => 'Thêm mới loại nhân viên thất bại.',
        'update' => 'Chỉnh sửa loại nhân viên thất bại.',
        'delete' => 'Xóa loại nhân viên thất bại.',
        'exists_in_employee'  => 'Xoá không thành công do Loại nhân viên đang có dữ liệu dàng buộc.',
    ],
    'payment_process' => [
        'store' => 'Thêm mới tiến trình thanh toán thất bại.',
        'update' => 'Chỉnh sửa tiến trình thanh toán thất bại.',
        'delete' => 'Xóa tiến trình thanh toán thất bại.',
    ],
    'meeting_type' => [
        'store' => 'Thêm mới loại cuộc họp thất bại.',
        'update' => 'Chỉnh sửa loại cuộc họp thất bại.',
        'delete' => 'Xóa loại cuộc họp thất bại.',
    ],
    'meeting_schedule' => [
        'store' => 'Thêm mới sự kiện thất bại.',
        'update' => 'Chỉnh sửa sự kiện thất bại.',
        'delete' => 'Xóa sự kiện thất bại.',
    ],
    'preference' => [
        'store' => 'Thêm mới cấu hình thất bại.',
        'update' => 'Chỉnh sửa cấu hình thất bại.',
        'uploadBannerLogin' => 'Cập nhật hình ảnh thất bại.',
    ],
    'holiday_schedule' => [
        'store' => 'Thêm mới sự kiện thất bại.',
        'update' => 'Chỉnh sửa sự kiện thất bại.',
        'delete' => 'Xóa sự kiện thất bại.',
    ],
    'furlough_type' => [
        'store' => 'Thêm mới loại nghỉ phép thất bại.',
        'update' => 'Chỉnh sửa loại nghỉ phép thất bại.',
        'delete' => 'Xóa loại nghỉ phép thất bại.',
    ],
    'furlough' => [
        'store' => 'Thêm mới nghỉ phép thất bại.',
        'update' => 'Chỉnh sửa nghỉ phép thất bại.',
        'delete' => 'Xóa nghỉ phép thất bại.',
    ],
    'bill' => [
        'store' => 'Thêm mới hoá đơn thất bại.',
        'update' => 'Chỉnh sửa hoá đơn thất bại.',
        'delete' => 'Xóa hoá đơn thất bại.',
    ],
    'document_type' => [
        'store' => 'Thêm mới loại tài liệu thất bại.',
        'update' => 'Chỉnh sửa loại tài liệu thất bại.',
        'delete' => 'Xóa loại tài liệu thất bại.',
    ],
    'document' => [
        'store' => 'Thêm mới tài liệu thất bại.',
        'update' => 'Chỉnh sửa tài liệu thất bại.',
        'delete' => 'Xóa tài liệu thất bại.',
    ],
    'evaluation' => [
        'store' => 'Thêm mới đánh giá thất bại.',
        'update' => 'Chỉnh sửa đánh giá thất bại.',
        'delete' => 'Xóa đánh giá thất bại.',
    ],
    'survey' => [
        'store' => 'Thêm mới khảo sát thất bại.',
        'update' => 'Chỉnh sửa khảo sát thất bại.',
        'delete' => 'Xóa khảo sát thất bại.',
        'response' => 'Gửi khảo sát thất bại.',
    ],
];
