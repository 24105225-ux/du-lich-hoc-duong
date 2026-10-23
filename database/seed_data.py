# -*- coding: utf-8 -*-
import random, datetime as dt

random.seed(703073)
HASH = "$2y$12$K8H9e2y8.v7pQ1wX5zR4O.uJ6mN3bV2cX1zL0kP9oI8uY7tR6eW5q"

rows = ["SET NAMES utf8mb4;", "SET FOREIGN_KEY_CHECKS = 0;", "USE dulichso;"]

# 1. Users
rows.append(f"INSERT INTO users (id, name, email, password, role, phone) VALUES (1, 'Quản trị Hệ thống', 'admin@demo.test', '{HASH}', 'admin', '0901000001');")
for i in range(2, 6): rows.append(f"INSERT INTO users (id, name, email, password, role, phone) VALUES ({i}, 'Đơn vị Tổ chức {i-1}', 'organizer{i-1}@demo.test', '{HASH}', 'organizer', '090200000{i}');")
for i in range(6, 16): rows.append(f"INSERT INTO users (id, name, email, password, role, phone) VALUES ({i}, 'Ban Giám hiệu Trường {i-5}', 'school{i-5}@demo.test', '{HASH}', 'school', '09030000{i:02d}');")
for i in range(16, 46): rows.append(f"INSERT INTO users (id, name, email, password, role, phone) VALUES ({i}, 'Phụ huynh Học sinh {i-15:02d}', 'parent{i-15:02d}@demo.test', '{HASH}', 'parent', '09040000{i:02d}');")

# 2. Schools & Classes
LEVELS = ['Tieu_hoc', 'THCS', 'THPT']
PROVS = ['Hà Nội', 'Ninh Bình', 'Quảng Ninh', 'Hải Phòng', 'Thanh Hóa', 'Đà Nẵng']
for s in range(1, 7):
    rows.append(f"INSERT INTO schools (id, user_id, name, edu_level, province, address) VALUES ({s}, {5+s}, 'Trường TH Thực nghiệm {s}', '{LEVELS[s % 3]}', '{PROVS[s-1]}', 'Số {s*10} Đường GD');")

cid = 0
for s in range(1, 7):
    for c in range(1, 3):
        cid += 1
        rows.append(f"INSERT INTO classes (id, school_id, name, grade, homeroom_teacher, student_count) VALUES ({cid}, {s}, 'Lớp {10}A{c}', 10, 'Cô Nguyễn Thị GV {cid}', 30);")

# 3. Students
for st in range(1, 61):
    rows.append(f"INSERT INTO students (id, class_id, parent_id, student_code, full_name, dob, gender, allergy_info, emergency_phone) VALUES ({st}, {((st - 1) % 12) + 1}, {16 + ((st - 1) % 30)}, 'HS2026{st:04d}', 'Học sinh {st:02d}', '2012-05-15', 'Nam', 'Không có', '09887766{st:02d}');")

# 4. Programs & Itineraries
iti_id = 0
for p in range(1, 21):
    rows.append(f"INSERT INTO programs (id, organizer_id, code, title, slug, edu_level, subject_name, learning_objectives, destination_name, province, base_price, safety_protocol) VALUES ({p}, {2 + (p % 4)}, 'PRG{p:04d}', 'Hành trình Lịch sử #{p}', 'hanh-trinh-{p}', 'THCS', 'Lịch sử', 'Hiểu biết di tích', 'Cổ Loa', 'Hà Nội', 450000, 'Có nhân viên y tế');")
    for step in range(1, 4):
        iti_id += 1
        rows.append(f"INSERT INTO itineraries (id, program_id, step_no, checkpoint_name, time_slot, edu_activity) VALUES ({iti_id}, {p}, {step}, 'Chặng {step}', '08:00', 'Hoạt động {step}');")

# 5. Registrations, Consent Forms, Attendance
cf_id = att_id = 0
for r in range(1, 26):
    rows.append(f"INSERT INTO class_registrations (id, reg_code, class_id, program_id, trip_date, assigned_teacher, registered_seats, total_amount, status) VALUES ({r}, 'REG{r:05d}', {((r - 1) % 12) + 1}, {((r - 1) % 20) + 1}, '2026-11-05', 'Thầy Phụ trách', 30, 15000000, 'confirmed');")
    for s_offset in range(1, 6):
        st_id = ((r * 2 + s_offset) % 60) + 1
        cf_id += 1
        rows.append(f"INSERT INTO consent_forms (id, registration_id, student_id, parent_id, consent_status, signed_at) VALUES ({cf_id}, {r}, {st_id}, {16 + ((st_id - 1) % 30)}, 'approved', NOW());")
        att_id += 1
        rows.append(f"INSERT INTO attendance_logs (id, registration_id, itinerary_id, student_id, status) VALUES ({att_id}, {r}, {((((r - 1) % 20) + 1) - 1) * 3 + 1}, {st_id}, 'present');")

rows.append("SET FOREIGN_KEY_CHECKS = 1;")

# GHI RA FILE TRỰC TIẾP (KHÔNG BỊ LỖI QUYỀN)
with open('seed.sql', 'w', encoding='utf-8') as f:
    f.write("\n".join(rows))

print("TẠO THÀNH CÔNG FILE seed.sql (> 400 bản ghi). Bạn hãy nạp file này vào XAMPP (phpMyAdmin) nhé!")