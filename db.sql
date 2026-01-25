
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(150),
    password VARCHAR(255),
    role ENUM('teacher','student') DEFAULT 'teacher',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    teacher_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);


CREATE TABLE class_students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    student_id INT,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);


CREATE TABLE works (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    teacher_id INT,
    title VARCHAR(150),
    description TEXT,
    file_path VARCHAR(255),
    due_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (teacher_id) REFERENCES users(id)
);


CREATE TABLE work_assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    work_id INT,
    student_id INT,
    FOREIGN KEY (work_id) REFERENCES works(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);


CREATE TABLE submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    work_id INT,
    student_id INT,
    content TEXT,
    file_path VARCHAR(255),
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (work_id) REFERENCES works(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);


CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT,
    grade DECIMAL(5,2),
    comment TEXT,
    graded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES submissions(id)
);


CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    student_id INT,
    date DATE,
    status ENUM('present','absent'),
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);


CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT,
    user_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

--@block

ALTER TABLE users
MODIFY role ENUM('teacher','student') DEFAULT 'teacher';




--@block

DESC users;

-- --@block
-- INSERT INTO users (name, email, password, role) VALUES
-- ('Alice Johnson', 'alice@school.com', 'hashed_pw_1', 'teacher'),
-- ('Bob Smith', 'bob@school.com', 'hashed_pw_2', 'teacher'),
-- ('Charlie Brown', 'charlie@student.com', 'hashed_pw_3', 'student'),
-- ('Diana Prince', 'diana@student.com', 'hashed_pw_4', 'student'),
-- ('Ethan Hunt', 'ethan@student.com', 'hashed_pw_5', 'student'),
-- ('Fiona Lee', 'fiona@student.com', 'hashed_pw_6', 'student');

-- INSERT INTO classes (name, teacher_id) VALUES
-- ('Math 101', 1),
-- ('Science 101', 2);

-- INSERT INTO class_students (class_id, student_id) VALUES
-- (1, 3),
-- (1, 4),
-- (2, 5),
-- (2, 6);

-- INSERT INTO works (class_id, teacher_id, title, description, file_path, due_date) VALUES
-- (1, 1, 'Algebra Homework', 'Solve equations 1–10', '/files/math_hw1.pdf', '2026-02-01'),
-- (2, 2, 'Physics Lab', 'Write lab report on motion', '/files/physics_lab.pdf', '2026-02-05');

-- INSERT INTO work_assignments (work_id, student_id) VALUES
-- (1, 3),
-- (1, 4),
-- (2, 5),
-- (2, 6);



-- INSERT INTO submissions (work_id, student_id, content, file_path) VALUES
-- (1, 3, 'My algebra answers', '/submissions/charlie_math.pdf'),
-- (1, 4, 'Homework completed', '/submissions/diana_math.pdf'),
-- (2, 5, 'Physics lab report', '/submissions/ethan_physics.pdf');



-- INSERT INTO grades (submission_id, grade, comment) VALUES
-- (1, 85.50, 'Good effort'),
-- (2, 92.00, 'Excellent work'),
-- (3, 78.00, 'Needs more explanation');



-- INSERT INTO chat_messages (class_id, user_id, message) VALUES
-- (1, 1, 'Welcome to Math 101!'),
-- (1, 3, 'Thank you, looking forward to it!'),
-- (2, 2, 'Please submit your lab reports by Friday'),
-- (2, 5, 'Got it, thanks!');


-- --@block 
-- SELECT * FROM class_students;



















