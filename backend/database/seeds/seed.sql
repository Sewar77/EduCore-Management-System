
BEGIN;

-- ==================================================
-- EDUCORE DEMO SEED
-- Students, Teachers, Subjects and Teacher Subjects
-- Run after database migrations.
-- Does not delete or reset existing records.
-- ==================================================


-- ==================================================
-- 1. STUDENTS (20)
-- ==================================================

INSERT INTO students
(full_name, email, phone, age, grade_class)
VALUES
('Ahmad Khaled', 'ahmad.khaled@example.com', '0790001001', 16, 'Grade 10'),
('Sara Mohammad', 'sara.mohammad@example.com', '0790001002', 17, 'Grade 11'),
('Omar Ibrahim', 'omar.ibrahim@example.com', '0790001003', 15, 'Grade 9'),
('Lina Ahmad', 'lina.ahmad@example.com', '0790001004', 18, 'Grade 12'),
('Yousef Ali', 'yousef.ali@example.com', '0790001005', 14, 'Grade 8'),
('Noor Hassan', 'noor.hassan@example.com', '0790001006', 16, 'Grade 10'),
('Khaled Mahmoud', 'khaled.mahmoud@example.com', '0790001007', 17, 'Grade 11'),
('Hala Nasser', 'hala.nasser@example.com', '0790001008', 15, 'Grade 9'),
('Zaid Abdullah', 'zaid.abdullah@example.com', '0790001009', 13, 'Grade 7'),
('Rama Tareq', 'rama.tareq@example.com', '0790001010', 18, 'Grade 12'),
('Anas Saleh', 'anas.saleh@example.com', '0790001011', 16, 'Grade 10'),
('Dana Samer', 'dana.samer@example.com', '0790001012', 14, 'Grade 8'),
('Hamza Adel', 'hamza.adel@example.com', '0790001013', 17, 'Grade 11'),
('Maya Fadi', 'maya.fadi@example.com', '0790001014', 15, 'Grade 9'),
('Laith Yazan', 'laith.yazan@example.com', '0790001015', 16, 'Grade 10'),
('Salma Bilal', 'salma.bilal@example.com', '0790001016', 13, 'Grade 7'),
('Adam Hani', 'adam.hani@example.com', '0790001017', 18, 'Grade 12'),
('Jana Bassam', 'jana.bassam@example.com', '0790001018', 14, 'Grade 8'),
('Rami Wael', 'rami.wael@example.com', '0790001019', 17, 'Grade 11'),
('Farah Mustafa', 'farah.mustafa@example.com', '0790001020', 15, 'Grade 9')
ON CONFLICT DO NOTHING;


-- ==================================================
-- 2. SUBJECTS (20)
-- ==================================================

INSERT INTO subjects
(name, code, description)
VALUES
('Physics', 'PHY', 'Mechanics, electricity and physical sciences'),
('math', 'math', 'Algebra, geometry and mathematical reasoning'),
('English', 'ENG101', 'English reading, writing and communication'),
('Chemistry', 'CHEM101', 'Chemical elements, reactions and compounds'),
('arabic', 'arb', 'Arabic grammar, language and literature'),
('Biology', 'BIO101', 'Living organisms and biological systems'),
('Computer Science', 'CS101', 'Programming and computer fundamentals'),
('History', 'HIS101', 'World and regional history'),
('Geography', 'GEO101', 'Physical and human geography'),
('Information Technology', 'IT101', 'Technology and information systems'),
('Statistics', 'STAT101', 'Probability and statistical analysis'),
('Economics', 'ECO101', 'Introduction to economic principles'),
('Business Studies', 'BUS101', 'Business and management fundamentals'),
('Art', 'ART101', 'Drawing, painting and creative expression'),
('Music', 'MUS101', 'Music theory and practical skills'),
('Physical Education', 'PE101', 'Sports, health and fitness'),
('Environmental Science', 'ENV101', 'Environmental systems and sustainability'),
('Psychology', 'PSY101', 'Human behavior and psychological principles'),
('Civics', 'CIV101', 'Citizenship, government and civic responsibility'),
('French', 'FR101', 'French language and communication')
ON CONFLICT DO NOTHING;


-- ==================================================
-- 3. TEACHERS (20)
-- All teacher fields included
-- ==================================================

INSERT INTO teachers
(
    full_name,
    email,
    phone,
    status,
    qualification,
    hire_date,
    created_at,
    updated_at
)
VALUES
('Mohammad Nasser', 'm.nasser@example.com', '0780002001',
 'active', 'MSc Physics', '2023-08-15',
 '2026-11-01 09:00:00', '2026-11-10 10:30:00'),

('Sarah Ahmad', 'sarah.teacher@example.com', '0791234568',
 'inactive', 'BSc Mathematic', '2026-11-13',
 '2026-11-13 20:28:57.734074', '2026-11-15 13:41:22.005151'),

('Rania Ahmad', 'r.ahmad@example.com', '0780002003',
 'active', 'BA English Language', '2021-09-01',
 '2026-11-02 09:00:00', '2026-11-12 10:00:00'),

('Khaled Yaseen', 'k.yaseen@example.com', '0780002004',
 'active', 'MSc Mathematics', '2020-08-25',
 '2026-11-02 09:15:00', '2026-11-12 10:15:00'),

('Huda Saleh', 'h.saleh@example.com', '0780002005',
 'inactive', 'MA Arabic Language', '2019-09-10',
 '2026-11-03 09:00:00', '2026-11-13 11:00:00'),

('Fadi Ibrahim', 'f.ibrahim@example.com', '0780002006',
 'active', 'BSc Chemistry', '2022-02-15',
 '2026-11-03 09:30:00', '2026-11-13 11:30:00'),

('Dima Hassan', 'd.hassan@example.com', '0780002007',
 'active', 'MSc Biology', '2021-08-20',
 '2026-11-04 08:30:00', '2026-11-14 09:00:00'),

('Samer Ali', 's.ali@example.com', '0780002008',
 'active', 'BSc Computer Science', '2024-01-10',
 '2026-11-04 10:00:00', '2026-11-14 10:00:00'),

('Maha Abdullah', 'm.abdullah@example.com', '0780002009',
 'inactive', 'BA History', '2018-09-01',
 '2026-11-05 09:00:00', '2026-11-12 12:00:00'),

('Tareq Omar', 't.omar@example.com', '0780002010',
 'active', 'BSc Geography', '2020-02-01',
 '2026-11-05 09:30:00', '2026-11-13 12:00:00'),

('Laila Mahmoud', 'l.mahmoud@example.com', '0780002011',
 'active', 'BA French Language', '2023-09-01',
 '2026-11-06 08:45:00', '2026-11-14 08:45:00'),

('Yazan Khalil', 'y.khalil@example.com', '0780002012',
 'active', 'MSc Statistics', '2022-08-25',
 '2026-11-06 09:15:00', '2026-11-14 09:15:00'),

('Reem Adel', 'r.adel@example.com', '0780002013',
 'inactive', 'BA Economics', '2021-03-15',
 '2026-11-07 09:00:00', '2026-11-12 14:00:00'),

('Bassam Hani', 'b.hani@example.com', '0780002014',
 'active', 'MBA Business Administration', '2019-08-10',
 '2026-11-07 10:00:00', '2026-11-13 14:00:00'),

('Alaa Nabil', 'a.nabil@example.com', '0780002015',
 'active', 'BFA Fine Arts', '2024-02-01',
 '2026-11-08 09:00:00', '2026-11-14 09:30:00'),

('Nour Zaid', 'n.zaid@example.com', '0780002016',
 'active', 'BA Music', '2023-01-15',
 '2026-11-08 10:00:00', '2026-11-14 10:30:00'),

('Osama Fares', 'o.fares@example.com', '0780002017',
 'active', 'BSc Sports Science', '2020-09-01',
 '2026-11-09 08:30:00', '2026-11-14 11:00:00'),

('Sawsan Hamdan', 's.hamdan@example.com', '0780002018',
 'active', 'MSc Environmental Science', '2022-09-10',
 '2026-11-09 09:00:00', '2026-11-14 11:30:00'),

('Majd Sami', 'm.sami@example.com', '0780002019',
 'active', 'MA Psychology', '2024-08-20',
 '2026-11-10 09:00:00', '2026-11-14 12:00:00'),

('Abeer Yousef', 'a.yousef@example.com', '0780002020',
 'inactive', 'BA Political Science', '2021-09-15',
 '2026-11-10 10:00:00', '2026-11-14 12:30:00')
ON CONFLICT DO NOTHING;


-- ==================================================
-- 4. TEACHER SUBJECTS (32)
-- Links teachers to existing subjects.
-- Uses email/code instead of fixed IDs.
-- ==================================================

INSERT INTO teacher_subjects (teacher_id, subject_id)
SELECT t.id, s.id
FROM (
    VALUES
    ('m.nasser@example.com', 'PHY'),

    ('sarah.teacher@example.com', 'arb'),
    ('sarah.teacher@example.com', 'math'),
    ('sarah.teacher@example.com', 'PHY'),

    ('r.ahmad@example.com', 'ENG101'),
    ('r.ahmad@example.com', 'FR101'),

    ('k.yaseen@example.com', 'math'),
    ('k.yaseen@example.com', 'STAT101'),

    ('h.saleh@example.com', 'arb'),
    ('h.saleh@example.com', 'CIV101'),

    ('f.ibrahim@example.com', 'CHEM101'),

    ('d.hassan@example.com', 'BIO101'),
    ('d.hassan@example.com', 'ENV101'),

    ('s.ali@example.com', 'CS101'),
    ('s.ali@example.com', 'IT101'),

    ('m.abdullah@example.com', 'HIS101'),
    ('m.abdullah@example.com', 'CIV101'),

    ('t.omar@example.com', 'GEO101'),

    ('l.mahmoud@example.com', 'FR101'),

    ('y.khalil@example.com', 'STAT101'),
    ('y.khalil@example.com', 'math'),

    ('r.adel@example.com', 'ECO101'),

    ('b.hani@example.com', 'BUS101'),
    ('b.hani@example.com', 'ECO101'),

    ('a.nabil@example.com', 'ART101'),

    ('n.zaid@example.com', 'MUS101'),

    ('o.fares@example.com', 'PE101'),

    ('s.hamdan@example.com', 'ENV101'),
    ('s.hamdan@example.com', 'BIO101'),

    ('m.sami@example.com', 'PSY101'),

    ('a.yousef@example.com', 'CIV101'),
    ('a.yousef@example.com', 'HIS101')
) AS mapping(teacher_email, subject_code)
JOIN teachers t ON t.email = mapping.teacher_email
JOIN subjects s ON s.code = mapping.subject_code
ON CONFLICT DO NOTHING;

COMMIT;
