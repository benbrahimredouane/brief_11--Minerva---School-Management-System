<?php
class User
{
    protected $id;
    protected $username;
    protected $email;
    protected $password;
    protected $role;
   

    public function __construct($username = '', $email = '', $password = '', $role = 'student')
    {
        $this->username = $username;
        $this->email = $email;
        $this->password = $this->hashPassword($password); 
        $this->role = $role;
      
    }

    //getters
    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return $this->role;
    }

    //setters
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword($password)
    {
        $this->password = $this->hashPassword($password);
        return $this;
    }

    public function setRole($role)
    {
        if (in_array($role, ['teacher', 'student'])) {
            $this->role = $role;
        }
        return $this;
    }



    //used methodes 
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function hashPassword($password)
    {
        //check if the password allready hashed
        if (password_get_info($password)['algo'] !== 0) {
            return $password;
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password)
    {
        return password_verify($password, $this->password);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
           
        ];
    }

    public function fromArray($data)
    {
        if (isset($data['id'])) {
            $this->id = $data['id'];
        }
        if (isset($data['username'])) {
            $this->username = $data['username'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['password'])) {
            $this->password = $data['password'];
        }
        if (isset($data['role'])) {
            $this->role = $data['role'];
        }
       
        return $this;
    }

    public static function generateRandomPassword($length = 12)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, strlen($chars) - 1)];
        }
        return $password;
    }

   
    public function validateEmail()
    {
        return filter_var($this->email, FILTER_VALIDATE_EMAIL) !== false;
    }

    
    public function validate()
    {
        $errors = [];
        
        if (empty($this->username)) {
            $errors[] = "Le nom d'utilisateur est requis";
        }
        
        if (empty($this->email) || !$this->validateEmail()) {
            $errors[] = "L'email est invalide";
        }
        
        if (empty($this->password) || strlen($this->password) < 60) { // Un hash Bcrypt fait minimum 60 caractères
            $errors[] = "Le mot de passe est invalide";
        }
        
        if (!in_array($this->role, ['teacher', 'student'])) {
            $errors[] = "Le rôle est invalide";
        }
        
        return $errors;
    }
}