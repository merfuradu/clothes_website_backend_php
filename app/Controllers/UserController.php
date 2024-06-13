<?php

namespace App\Controllers;
use CodeIgniter\API\ResponseTrait;

class UserController extends BaseController {
    use ResponseTrait;
    

    public function register()
     {
        $requestData = $this->request->getJSON(true);

        $validation = $this->validateDataRegister($requestData);

        if ($validation !== true) {
            return $this->failValidationErrors($validation);
        }

        if ($this->emailExists($requestData['email'])) {
            return $this->failValidationErrors('Email already exists');
        }

        $hashedPassword = password_hash($requestData['password'], PASSWORD_DEFAULT);

        $success = $this->saveToDatabase($requestData['email'], $hashedPassword);
        if (!$success) {
            return $this->failServerError('Failed to save user data');
        }
        return $this->respondCreated(['message' => 'User registered successfully']);
    }

    public function login() 
{
    $requestData = $this->request->getJSON(true);

    $validation = $this->validateDataLogin($requestData);
    if ($validation !== true) {
        return $this->failValidationErrors($validation);
    }

    $user = $this->getUserByEmail($requestData['email']);
    if (!$user) {
        return $this->failNotFound('User not found');
    }

    if (!password_verify($requestData['password'], $user['password'])) {
        return $this->failUnauthorized('Invalid password');
    }

    // Set session data
    $sessionData = [
        'loggedIn' => true,
        'userEmail' => $user['email'],
    ];
    session()->set($sessionData);

    return $this->respond(['message' => 'Login successful', 'sessionData' => $sessionData]);
}

    public function logout() 
    {
        session()->destroy();
        return $this->respond(['message' => 'Logout successful']);
    }

    protected function validateDataRegister(array $data) 
    {
        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors = 'Invalid email format';
        }
        if (empty($data['password'])) {
            $errors = 'Password is required';
        }
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*]).{8,}$/', $data['password'])) {
            $errors = 'Password must contain at least 8 characters, one uppercase letter, one lowercase letter, one digit, and one special character (!@#$%^&*)';
        }

        return empty($errors) ? true : $errors;
    }

    protected function validateDataLogin(array $data) 
    {
        $errors = [];
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors = 'Invalid email format';
        }
        if (empty($data['password'])) {
            $errors = 'Password is required';
        }
        return empty($errors) ? true : $errors;
    }

    protected function emailExists(string $email) 
    {
        $db = db_connect();
        $builder = $db->table('users');
        $user = $builder->getWhere(['email' => $email])->getRow();
        return $user !== null;
    }

    protected function saveToDatabase(string $email, string $hashedPassword) 
    {
        $db = db_connect();
        $builder = $db->table('users');
        $data = [
            'email' => $email,
            'password' => $hashedPassword
        ];
        $builder->insert($data);

        return $db->affectedRows() === 1;
    }

    protected function getUserByEmail(string $email) 
    {
        $db = db_connect();
        $builder = $db->table('users');
        $user = $builder->where('email', $email)->get()->getRowArray();
        return $user;
    }
}
