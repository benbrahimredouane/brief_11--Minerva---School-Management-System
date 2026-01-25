<?php
require __DIR__ . '/../core/Contoller.php';
require __DIR__ . '/../services/teacherservice.php';

class TeacherController extends BaseController
{
    private $teacherService;

    public function __construct()
    {
        $this->teacherService = new TeacherService();
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        $teacherId = $_SESSION['user_id'];
        $classes = $this->teacherService->getTeacherClasses($teacherId);
        $recentWorks = $this->teacherService->getRecentWorks($teacherId);
        $submissions = $this->teacherService->getPendingSubmissions($teacherId);

        $this->render('TeacherDashboard', [
            'classes' => $classes,
            'recentWorks' => $recentWorks,
            'submissions' => $submissions
        ]);
    }

    public function addStudentToClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $classId = $_POST['class_id'] ?? 0;
            $studentId = $_POST['student_id'] ?? 0;

            if (!empty($classId) && !empty($studentId)) {
                if ($this->teacherService->addStudentToClass($classId, $studentId)) {
                    $_SESSION['success'] = 'Student added to class successfully!';
                } else {
                    $_SESSION['error'] = 'Error adding student to class.';
                }
            } else {
                $_SESSION['error'] = 'Please select a valid student.';
            }
        }

        header('Location: /teacher/dashboard');
        exit;
    }   
     

    public function viewClass($classId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        $class = $this->teacherService->getClassById($classId);
        $students = $this->teacherService->getClassStudents($classId);
        $works = $this->teacherService->getClassWorks($classId);
        $availableStudents = $this->teacherService->getAllStudents();

        // Filter out students already in the class
        $classStudentIds = array_column($students, 'id');
        $availableStudents = array_filter($availableStudents, function($student) use ($classStudentIds) {
            return !in_array($student['id'], $classStudentIds);
        });

        $this->render('ViewClass', [
            'class' => $class,
            'students' => $students,
            'works' => $works,
            'availableStudents' => array_values($availableStudents)
        ]);
    }

    public function createClass()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $teacherId = $_SESSION['user_id'];

            if ($this->teacherService->createClass($name, $teacherId)) {
                header('Location: /teacher/dashboard');
                exit;
            }
        }

        $this->render('CreateClass');
    }

    public function createWork($classId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST['title'] ?? '';
            $description = $_POST['description'] ?? '';
            $dueDate = $_POST['due_date'] ?? '';
            $teacherId = $_SESSION['user_id'];

            $filePath = null;
            if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
                $filePath = $this->uploadFile($_FILES['file']);
            }

            if ($this->teacherService->createWork($classId, $teacherId, $title, $description, $filePath, $dueDate)) {
                header('Location: /teacher/class/' . $classId);
                exit;
            }
        }

        $class = $this->teacherService->getClassById($classId);
        $this->render('CreateWork', ['class' => $class]);
    }

    public function viewSubmissions($workId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        $work = $this->teacherService->getWorkById($workId);
        $submissions = $this->teacherService->getWorkSubmissions($workId);

        $this->render('ViewSubmissions', [
            'work' => $work,
            'submissions' => $submissions
        ]);
    }

    public function gradeSubmission($submissionId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $grade = $_POST['grade'] ?? 0;
            $comment = $_POST['comment'] ?? '';

            if ($this->teacherService->gradeSubmission($submissionId, $grade, $comment)) {
                header('Location: /teacher/submissions');
                exit;
            }
        }

        $submission = $this->teacherService->getSubmissionById($submissionId);
        $this->render('GradeSubmission', ['submission' => $submission]);
    }

    public function manageAttendance($classId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $date = $_POST['date'] ?? date('Y-m-d');
            $attendance = $_POST['attendance'] ?? [];

            $this->teacherService->saveAttendance($classId, $date, $attendance);
            header('Location: /teacher/class/' . $classId);
            exit;
        }

        $class = $this->teacherService->getClassById($classId);
        $students = $this->teacherService->getClassStudents($classId);
        $attendanceRecords = $this->teacherService->getAttendanceRecords($classId);

        $this->render('ManageAttendance', [
            'class' => $class,
            'students' => $students,
            'attendance' => $attendanceRecords
        ]);
    }

    private function uploadFile($file)
    {
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid('work_') . '_' . basename($file['name']);
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/' . $filename;
        }

        return null;
    }




    public function removeStudentFromClass($classId)
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = $_POST['student_id'] ?? 0;

            if (!empty($studentId)) {
                if ($this->teacherService->removeStudentFromClass($classId, $studentId)) {
                    $_SESSION['success'] = 'Student removed from class.';
                } else {
                    $_SESSION['error'] = 'Error removing student from class.';
                }
            }
        }

        header('Location: /teacher/class/' . $classId);
        exit;
        
        }
        }


