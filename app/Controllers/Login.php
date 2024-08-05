<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\UserModel;


class Login extends BaseController
{
    public $formatters = [
    'application/json' => \CodeIgniter\Format\JSONFormatter::class,
    'application/xml'  => \CodeIgniter\Format\XMLFormatter::class,
];

    public function __construct()
  {
      helper(['url', 'form']);

     
 
  }    public function authenticate() {

        // Set appropriate CORS headers for regular requests
         header("Access-Control-Allow-Origin: *");
         header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
         header("Access-Control-Allow-Headers: Content-Type");
         header("Access-Control-Allow-Credentials: true");




          $users = new UserModel;
          $post = $this->request->getPost();
          $post = json_decode(file_get_contents('php://input'), true);
        
        // Retrieve username and password from POST request
          $username = $post['username'];
          $password = $post['password'];

        // Authenticate user using user_model
       

      $user = $users->where('username', $username)->first();
        
        if ($user) {
             // User found, now check the password
            if (password_verify($password, $user['password'])) {
            // Password is correct, proceed to login
            
            // Prepare the response
            $response['success'] = true;
            $response['title'] = " {$username}!";
            $response['message'] = "Logged successful. Welcome back {$username}!";
            $response['user'] = $user;
            return $this->response->setJSON($response);
         } else {
            // Password is incorrect
            $response['success'] = false;
            $response['title'] = "Think {$username}.";
            $response['message'] = 'The provided password does not match our records.';
            return $this->response->setJSON($response, 401); // 401 Unauthorized
        }
        } else {
            // User not found
            $response['success'] = false;
            $response['usernotfound'] = true;
            $response['title'] = 'you\'re not Register.';
            $response['message'] = 'No account found with that username.';
            
            return $this->response->setJSON($response, 404); // 404 Not Found
        }
        
        
       
        
    }  
} 
