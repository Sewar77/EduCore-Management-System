
BEGIN;

-- ==========================================
-- 1. STUDENTS (20 Records)
-- ==========================================

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


-- ==========================================
-- 2. TEACHERS (20 Records)
-- ==========================================

INSERT INTO teachers
(full_name, email, phone)
VALUES
('Mohammad Nasser', 'm.nasser@example.com', '0780002001'),
('Rania Ahmad', 'r.ahmad@example.com', '0780002002'),
('Khaled Yaseen', 'k.yaseen@example.com', '0780002003'),
('Huda Saleh', 'h.saleh@example.com', '0780002004'),
('Fadi Ibrahim', 'f.ibrahim@example.com', '0780002005'),
('Dima Hassan', 'd.hassan@example.com', '0780002006'),
('Samer Ali', 's.ali@example.com', '0780002007'),
('Maha Abdullah', 'm.abdullah@example.com', '0780002008'),
('Tareq Omar', 't.omar@example.com', '0780002009'),
('Laila Mahmoud', 'l.mahmoud@example.com', '0780002010'),
('Yazan Khalil', 'y.khalil@example.com', '0780002011'),
('Reem Adel', 'r.adel@example.com', '0780002012'),
('Bassam Hani', 'b.hani@example.com', '0780002013'),
('Alaa Nabil', 'a.nabil@example.com', '0780002014'),
('Nour Zaid', 'n.zaid@example.com', '0780002015'),
('Osama Fares', 'o.fares@example.com', '0780002016'),
('Sawsan Hamdan', 's.hamdan@example.com', '0780002017'),
('Majd Sami', 'm.sami@example.com', '0780002018'),
('Abeer Yousef', 'a.yousef@example.com', '0780002019'),
('Ibrahim Fawzi', 'i.fawzi@example.com', '0780002020')
ON CONFLICT DO NOTHING;


-- ==========================================
-- 3. SUBJECTS (20 Records)
-- ==========================================

INSERT INTO subjects
(name, code, description)
VALUES
('Mathematics', 'MATH101', 'Algebra, geometry, and mathematical reasoning'),
('English', 'ENG101', 'English reading, writing, and grammar'),
('Arabic', 'ARB101', 'Arabic language and literature'),
('Physics', 'PHY101', 'Introduction to physical sciences'),
('Chemistry', 'CHEM101', 'Chemical elements, compounds, and reactions'),
('Biology', 'BIO101', 'Living organisms and biological systems'),
('Computer Science', 'CS101', 'Programming and computer science fundamentals'),
('History', 'HIS101', 'World and regional history'),
('Geography', 'GEO101', 'Physical and human geography'),
('Information Technology', 'IT101', 'Digital technology and information systems'),
('Statistics', 'STAT101', 'Statistical analysis and probability'),
('Economics', 'ECO101', 'Basic economic principles'),
('Business Studies', 'BUS101', 'Introduction to business and management'),
('Art', 'ART101', 'Drawing, painting, and creative expression'),
('Music', 'MUS101', 'Music theory and practice'),
('Physical Education', 'PE101', 'Fitness, sports, and healthy living'),
('Environmental Science', 'ENV101', 'Environmental systems and sustainability'),
('Psychology', 'PSY101', 'Introduction to human behavior'),
('Civics', 'CIV101', 'Citizenship, government, and civic responsibility'),
('French', 'FR101', 'French language fundamentals')
ON CONFLICT DO NOTHING;

COMMIT;
