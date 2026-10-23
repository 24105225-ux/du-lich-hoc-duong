-- database/schema.sql - MySQL 8.0 - ĐT-17: Du lịch học đường
CREATE DATABASE IF NOT EXISTS dulichso CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dulichso;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS audit_logs, evaluations, trip_notifications, attendance_logs,
  consent_forms, class_registrations, itineraries, programs, students, classes, schools, users;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin', 'school', 'parent', 'organizer') NOT NULL DEFAULT 'parent',
  phone VARCHAR(20) NULL,
  status ENUM('active', 'locked') NOT NULL DEFAULT 'active',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE schools (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(180) NOT NULL,
  edu_level ENUM('Tieu_hoc', 'THCS', 'THPT') NOT NULL,
  province VARCHAR(80) NOT NULL,
  address VARCHAR(255) NULL,
  CONSTRAINT fk_sch_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE classes (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  school_id BIGINT UNSIGNED NOT NULL,
  name VARCHAR(60) NOT NULL,
  grade TINYINT UNSIGNED NOT NULL,
  homeroom_teacher VARCHAR(120) NOT NULL,
  student_count SMALLINT UNSIGNED NOT NULL DEFAULT 30,
  CONSTRAINT fk_cls_sch FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE students (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  class_id BIGINT UNSIGNED NOT NULL,
  parent_id BIGINT UNSIGNED NOT NULL,
  student_code CHAR(10) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  dob DATE NOT NULL,
  gender ENUM('Nam', 'Nu') NOT NULL,
  medical_notes VARCHAR(255) DEFAULT 'Bình thường',
  allergy_info VARCHAR(255) DEFAULT 'Không có',
  emergency_phone VARCHAR(20) NOT NULL,
  CONSTRAINT fk_stu_cls FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
  CONSTRAINT fk_stu_par FOREIGN KEY (parent_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE programs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  organizer_id BIGINT UNSIGNED NOT NULL,
  code CHAR(8) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  slug VARCHAR(220) NOT NULL UNIQUE,
  edu_level ENUM('Tieu_hoc', 'THCS', 'THPT') NOT NULL,
  subject_name VARCHAR(80) NOT NULL,
  learning_objectives TEXT NOT NULL,
  destination_name VARCHAR(150) NOT NULL,
  province VARCHAR(80) NOT NULL,
  base_price DECIMAL(12,2) NOT NULL,
  capacity SMALLINT UNSIGNED NOT NULL DEFAULT 45,
  safety_protocol TEXT NOT NULL,
  status ENUM('published', 'draft') NOT NULL DEFAULT 'published',
  CONSTRAINT fk_prg_org FOREIGN KEY (organizer_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE itineraries (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  program_id BIGINT UNSIGNED NOT NULL,
  step_no TINYINT UNSIGNED NOT NULL,
  checkpoint_name VARCHAR(160) NOT NULL,
  time_slot VARCHAR(40) NOT NULL,
  edu_activity VARCHAR(255) NOT NULL,
  CONSTRAINT fk_iti_prg FOREIGN KEY (program_id) REFERENCES programs(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE class_registrations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reg_code CHAR(12) NOT NULL UNIQUE,
  class_id BIGINT UNSIGNED NOT NULL,
  program_id BIGINT UNSIGNED NOT NULL,
  trip_date DATE NOT NULL,
  assigned_teacher VARCHAR(120) NOT NULL,
  registered_seats SMALLINT UNSIGNED NOT NULL,
  total_amount DECIMAL(14,2) NOT NULL,
  status ENUM('pending_consent', 'confirmed', 'completed', 'cancelled') NOT NULL DEFAULT 'pending_consent',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_reg_cls FOREIGN KEY (class_id) REFERENCES classes(id),
  CONSTRAINT fk_reg_prg FOREIGN KEY (program_id) REFERENCES programs(id)
) ENGINE=InnoDB;

CREATE TABLE consent_forms (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  parent_id BIGINT UNSIGNED NOT NULL,
  consent_status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
  health_declaration VARCHAR(255) DEFAULT 'Sức khỏe ổn định',
  signed_at DATETIME NULL,
  CONSTRAINT fk_csf_reg FOREIGN KEY (registration_id) REFERENCES class_registrations(id) ON DELETE CASCADE,
  CONSTRAINT fk_csf_stu FOREIGN KEY (student_id) REFERENCES students(id),
  CONSTRAINT fk_csf_par FOREIGN KEY (parent_id) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE attendance_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  itinerary_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  status ENUM('present', 'absent', 'medical_leave') NOT NULL DEFAULT 'present',
  note VARCHAR(200) NULL,
  checked_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_att_reg FOREIGN KEY (registration_id) REFERENCES class_registrations(id) ON DELETE CASCADE,
  CONSTRAINT fk_att_iti FOREIGN KEY (itinerary_id) REFERENCES itineraries(id),
  CONSTRAINT fk_att_stu FOREIGN KEY (student_id) REFERENCES students(id)
) ENGINE=InnoDB;

CREATE TABLE trip_notifications (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  title VARCHAR(160) NOT NULL,
  message TEXT NOT NULL,
  sent_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_ntf_reg FOREIGN KEY (registration_id) REFERENCES class_registrations(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE evaluations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_id BIGINT UNSIGNED NOT NULL,
  student_id BIGINT UNSIGNED NOT NULL,
  score TINYINT UNSIGNED NOT NULL,
  competency_achieved VARCHAR(120) NOT NULL,
  teacher_comment VARCHAR(255) NULL,
  evaluated_at DATE NOT NULL,
  CONSTRAINT fk_evl_reg FOREIGN KEY (registration_id) REFERENCES class_registrations(id) ON DELETE CASCADE,
  CONSTRAINT fk_evl_stu FOREIGN KEY (student_id) REFERENCES students(id)
) ENGINE=InnoDB;

CREATE TABLE audit_logs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(80) NOT NULL,
  entity VARCHAR(60) NOT NULL,
  level ENUM('info', 'warning', 'error') NOT NULL DEFAULT 'info',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

SET FOREIGN_KEY_CHECKS = 1;