<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\AdminModel;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\CommentModel;
use App\Models\otherCommentModel;

class AdminAuth extends ResourceController
{
    protected $adminModel;
    protected $projectModel;
    protected $commentModel; // Declare this property
    protected $othercommentModel; // Declare this property
    protected $session;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->adminModel = new AdminModel();
        $this->commentModel = new CommentModel(); // Initialize it here
        $this->othercommentModel = new otherCommentModel(); // Initialize it here
        $this->session = \Config\Services::session(); // Correctly assign session to $this->session
        
        helper(['form', 'url']);
    
    } 
    public function coreUpdate()
    {
        $projects = $this->projectModel->findAll();
        $comments = $this->commentModel->findAll();

        $projectlength['projectslen'] = [
            'allProjects' => $projects,
            'allcomments' => $comments,
        ];


        return view('core_updates',  $projectlength); 

    }
    public function otherUpdate(){
        // $projects = $this->projectModel->findAll();
        $comments = $this->othercommentModel->findAll();

        $projectlength['projectslen'] = [
           
            
            'allcomments' => $comments,
        ];

        return view('other_updates',  $projectlength); 

    }

    public function dashboard()
    {
        $projects = $this->projectModel->findAll();
        $comments = $this->commentModel->findAll();

        $projects_inprogress = $this->projectModel->where('inprogress', 'inprogress')->findAll();
        $projects_pending = $this->projectModel->where('pending', 'pending')->findAll();
        $projects_completed = $this->projectModel->where('isComplete', 1)->findAll();
        
        $projectCount =  count($projects) ?? 0;
        // $commentsCount =  count($comments) ?? 0;
        $pendinglength =  count($projects_pending) ?? 0;
        $inprogresslength =  count($projects_inprogress) ?? 0;
        $donelength = count($projects_completed) ?? 0;

        $projectlength['projectslen'] = [
            'total' => $projectCount,
            'pending' => $pendinglength,
            'inprogress' => $inprogresslength,
            'completed' => $donelength,
            'allProjects' => $projects,
            'allcomments' => $comments,
        ];
        
        return view('admin_dashboard', $projectlength);
    }

    public function login()
    {
        $projects = $this->projectModel->findAll();
        $projects_inprogress = $this->projectModel->where('inprogress', 'inprogress')->findAll();
        $projects_pending = $this->projectModel->where('pending', 'pending')->findAll();
        $projects_completed = $this->projectModel->where('isComplete', 1)->findAll();
        
        $projectCount =  count($projects) ?? 0;
        $pendinglength =  count($projects_pending) ?? 0;
        $inprogresslength =  count($projects_inprogress) ?? 0;
        $donelength = count($projects_completed) ?? 0;

        $projectlength['projectslen'] = [
            'total' => $projectCount,
            'pending' => $pendinglength,
            'inprogress' => $inprogresslength,
            'completed' => $donelength,
            'allProjects' => $projects
        ];

        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = (string) $this->request->getPost('password');

        $admin = $this->adminModel->where('fullname', $username)->first();

        if ($admin && password_verify($password, $admin['password']) && $admin['isAdmin']) {
            // Set session data
            $userdata = [
                'isLoggedIn' => true,
                'adminID' => $admin['admin_id'],
                'fullname' => $admin['fullname'],
                'isAdmin' => $admin['isAdmin'],
                'status' => "Welcome back, {$admin['fullname']}",
            
            ];
            $this->session->set($userdata);

            return redirect()->to("admin_dashboard");
        }
        $this->session->set(['status' => "Invalid credentials or not an admin.", 'status_time' => time()]);
         return redirect()->back();
        
        
    }

    public function register()
    {
        $rules = [
            'fullname' => 'required',
            'password' => 'required',
        ];
   
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        

        $fullname = $this->request->getPost('fullname');
        $password = (string) $this->request->getPost('password');
        $admin = $this->adminModel->where('fullname', $fullname)->first();
        if ($admin && password_verify($password, $admin['password'])){

            $this->session->set(['status' => "credentials already exist procced to log in .", 'status_time' => time()]);
            return redirect()->back();

        }
        $data = [
            'fullname' => $fullnam,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'isAdmin'  => true,
        ];
        

        if ($this->adminModel->save($data)) {

            $this->session->set(['status' => "Admin user created successfully.", 'status_time' => time()]);
            return redirect()->back();

           
        }

        return $this->fail('Failed to create admin user.');
    }

    public function logout()
    {
        $this->session->remove(['isLoggedIn', 'adminID', 'fullname', 'isAdmin']);
        $this->session->setFlashdata('message', 'You have been logged out.');
        return redirect()->to('/signin_login');
    }

    public function checkLoggedIn()
    {
        if ($this->session->get('isLoggedIn') && $this->session->get('isAdmin')) {
            return $this->respond(['status' => 200, 'message' => 'Logged in']);
        }

        return $this->failUnauthorized('Not logged in or not an admin.');
    }
}
