<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class Signup extends ResourceController
{
    public $formatters = [
        'application/json' => \CodeIgniter\Format\JSONFormatter::class,
        'application/xml'  => \CodeIgniter\Format\XMLFormatter::class,
    ];

    public function __construct()
    {
        helper(['url', 'form']);
    }

    public function register()
    {
        // Set appropriate CORS headers for regular requests
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        // Continue with your existing register logic
        $user = new UserModel();
        $post = $this->request->getPost();
        $post = json_decode(file_get_contents('php://input'), true);

        $data = [
            'fullname' => $post["fullname"],
            'username' => $post["username"],
            'email' => $post["email"],
            'companyid' => $post["companyid"],
            'password' => password_hash($post["password"], PASSWORD_DEFAULT),
        ];

        $success = $user->save($data);

        if ($success > 0) {
            $response = [
                'status' => 200,
                'code' => '123a',
                'messages' => [
                    'User registered successfully',
                    'You can now proceed to log in',
                ],
            ];
        } else {
            $response = [
                'status' => 400,
                'code' => '321a',
                'messages' => [
                    'User registration failed',
                    'Kindly check for sign up credentials',
                ],
            ];
        }

        return $this->response->setJSON($response)->setStatusCode($response['status']);
    }

    public function users()
    {
        $userModel = new UserModel();
        $users = $userModel->findAll();
        if(!$users){
             return $this->respond("no users");
        }
        return $this->respond($users);
    }
}
