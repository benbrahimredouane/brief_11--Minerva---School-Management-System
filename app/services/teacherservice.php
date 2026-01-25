<?php
require __DIR__ . '/../core/database.php';
 
class TeacherService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getTeacherClasses($teacherId)
    {
        $stmt = $this->db->prepare('SELECT * FROM classes WHERE teacher_id = :teacher_id');
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClassById($classId)
    {
        $stmt = $this->db->prepare('SELECT * FROM classes WHERE id = :id');
        $stmt->execute([':id' => $classId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getClassStudents($classId)
    {
        $stmt = $this->db->prepare('
            SELECT u.* FROM users u
            INNER JOIN class_students cs ON u.id = cs.student_id
            WHERE cs.class_id = :class_id
        ');
        $stmt->execute([':class_id' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClassWorks($classId)
    {
        $stmt = $this->db->prepare('SELECT * FROM works WHERE class_id = :class_id ORDER BY created_at DESC');
        $stmt->execute([':class_id' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentWorks($teacherId)
    {
        $stmt = $this->db->prepare('
            SELECT * FROM works 
            WHERE teacher_id = :teacher_id 
            ORDER BY created_at DESC 
            LIMIT 5
        ');
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPendingSubmissions($teacherId)
    {
        $stmt = $this->db->prepare('
            SELECT s.*, w.title, u.name as student_name
            FROM submissions s
            INNER JOIN works w ON s.work_id = w.id
            INNER JOIN users u ON s.student_id = u.id
            WHERE w.teacher_id = :teacher_id AND s.id NOT IN (
                SELECT submission_id FROM grades
            )
            ORDER BY s.submitted_at DESC
            LIMIT 10
        ');
        $stmt->execute([':teacher_id' => $teacherId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createClass($name, $teacherId)
    {
        try {
            $stmt = $this->db->prepare('INSERT INTO classes (name, teacher_id) VALUES (:name, :teacher_id)');
            return $stmt->execute([
                ':name' => $name,
                ':teacher_id' => $teacherId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function createWork($classId, $teacherId, $title, $description, $filePath, $dueDate)
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO works (class_id, teacher_id, title, description, file_path, due_date)
                VALUES (:class_id, :teacher_id, :title, :description, :file_path, :due_date)
            ');
            return $stmt->execute([
                ':class_id' => $classId,
                ':teacher_id' => $teacherId,
                ':title' => $title,
                ':description' => $description,
                ':file_path' => $filePath,
                ':due_date' => $dueDate
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getWorkById($workId)
    {
        $stmt = $this->db->prepare('SELECT * FROM works WHERE id = :id');
        $stmt->execute([':id' => $workId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getWorkSubmissions($workId)
    {
        $stmt = $this->db->prepare('
            SELECT s.*, u.name as student_name, u.email,
                   g.grade, g.comment, g.graded_at
            FROM submissions s
            INNER JOIN users u ON s.student_id = u.id
            LEFT JOIN grades g ON s.id = g.submission_id
            WHERE s.work_id = :work_id
            ORDER BY s.submitted_at DESC
        ');
        $stmt->execute([':work_id' => $workId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSubmissionById($submissionId)
    {
        $stmt = $this->db->prepare('
            SELECT s.*, u.name as student_name, w.title as work_title,
                   g.grade, g.comment
            FROM submissions s
            INNER JOIN users u ON s.student_id = u.id
            INNER JOIN works w ON s.work_id = w.id
            LEFT JOIN grades g ON s.id = g.submission_id
            WHERE s.id = :id
        ');
        $stmt->execute([':id' => $submissionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function gradeSubmission($submissionId, $grade, $comment)
    {
        try {
            // Check if grade already exists
            $checkStmt = $this->db->prepare('SELECT id FROM grades WHERE submission_id = :submission_id');
            $checkStmt->execute([':submission_id' => $submissionId]);
            $existingGrade = $checkStmt->fetch();

            if ($existingGrade) {
                // Update existing grade
                $stmt = $this->db->prepare('
                    UPDATE grades SET grade = :grade, comment = :comment, graded_at = NOW()
                    WHERE submission_id = :submission_id
                ');
            } else {
                // Insert new grade
                $stmt = $this->db->prepare('
                    INSERT INTO grades (submission_id, grade, comment)
                    VALUES (:submission_id, :grade, :comment)
                ');
            }

            return $stmt->execute([
                ':submission_id' => $submissionId,
                ':grade' => $grade,
                ':comment' => $comment
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function saveAttendance($classId, $date, $attendance)
    {
        try {
            // Delete existing attendance for this date
            $deleteStmt = $this->db->prepare('DELETE FROM attendance WHERE class_id = :class_id AND date = :date');
            $deleteStmt->execute([
                ':class_id' => $classId,
                ':date' => $date
            ]);

            // Insert new attendance records
            $insertStmt = $this->db->prepare('
                INSERT INTO attendance (class_id, student_id, date, status)
                VALUES (:class_id, :student_id, :date, :status)
            ');

            foreach ($attendance as $studentId => $status) {
                $insertStmt->execute([
                    ':class_id' => $classId,
                    ':student_id' => $studentId,
                    ':date' => $date,
                    ':status' => $status
                ]);
            }

            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAttendanceRecords($classId)
    {
        $stmt = $this->db->prepare('
            SELECT a.*, u.name as student_name
            FROM attendance a
            INNER JOIN users u ON a.student_id = u.id
            WHERE a.class_id = :class_id
            ORDER BY a.date DESC
        ');
        $stmt->execute([':class_id' => $classId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStudentToClass($classId, $studentId)
    {
        try {
            $stmt = $this->db->prepare('
                INSERT INTO class_students (class_id, student_id)
                VALUES (:class_id, :student_id)
            ');
            return $stmt->execute([
                ':class_id' => $classId,
                ':student_id' => $studentId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function removeStudentFromClass($classId, $studentId)
    {
        try {
            $stmt = $this->db->prepare('
                DELETE FROM class_students
                WHERE class_id = :class_id AND student_id = :student_id
            ');
            return $stmt->execute([
                ':class_id' => $classId,
                ':student_id' => $studentId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllStudents()
    {
        $stmt = $this->db->prepare('SELECT id, name, email FROM users WHERE role = "student" ORDER BY name ASC');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
