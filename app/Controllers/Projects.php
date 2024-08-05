<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\CommentModel;

class Projects extends ResourceController
{
    public $formatters = [
        'application/json' => \CodeIgniter\Format\JSONFormatter::class,
        'application/xml'  => \CodeIgniter\Format\XMLFormatter::class,
    ];
    protected $projectModel;
    protected $session;

    public function __construct()
    {
        $this->projectModel = new ProjectModel();
        $this->session = \Config\Services::session();
        helper(['url', 'form']);
    }

    public function adminProject()
    {
        $userModel = new UserModel();
        $data['users'] = $userModel->findAll();
        return view('Admin_create_project', $data);
    }
    public function adminsignin()
    {
        
        return view('Admin_form');
    }

    public function createProject()
    {
        $request = \Config\Services::request();
        
        $userIds = $request->getPost('developers') ?? [];
        $userModel = new UserModel();
        $users = $userModel->whereIn('user_id', $userIds)->findAll();
        
        if (count($users) !== count($userIds)) {
            return $this->response->setJSON(['message' => 'Some users not found'])->setStatusCode(404);
        }

        $developers = array_map(function($user) {
            return ['id' => $user['user_id'], 'fullname' => $user['fullname']];
        }, $users);

        if (isset($_POST['completed'])) {
           
            $isCompleted = $_POST['completed'] === 'true';
        } else {
            
            $isCompleted = false;
        }



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


        $data = [
            'project_name' => $request->getPost('project_name'),
            'project_desc' => $request->getPost('project_desc'),
            'isComplete' => $isCompleted,
            'pending' => $isCompleted == true ? null : 'pending',
            'inprogress' => null,
            'execute_date' =>$isCompleted == true  ? $request->getPost('execute_date') : "0000-00-00",
            'developers' => json_encode($developers),
        ];

        if ($this->projectModel->save($data)) {
            $this->session->set(['status' => "Project created and user will be notify.", 'status_time' => time()]);
         return redirect()->back();
           
        } else {
            $this->session->set(['status' => "Failed to add project. Kindly check the input fields.", 'status_time' => time()]);
            return redirect()->back();
        }
    }

    public function getProjects()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        $projects = $this->projectModel->findAll();
        $projects_inprogress = $this->projectModel->where('inprogress', 'inprogress')->findAll();
        $projects_pending = $this->projectModel->where('pending', 'pending')->findAll();
        $projects_completed = $this->projectModel->where('isComplete', 1)->findAll();
        
        $projectCount =  count($projects) ?? 0;
        $pendinglength =  count($projects_pending) ?? 0;
        $inprogresslength =  count($projects_inprogress) ?? 0;
        $donelength = count($projects_completed) ?? 0;

        return $this->respond([
            'total' => $projectCount,
            'pending' => $pendinglength,
            'inprogress' => $inprogresslength,
            'completed' => $donelength,
            'allProjects' =>$projects
        ]);
    }

    public function getPending()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        $projects_pending = $this->projectModel->where('pending', 'pending')->findAll();
        return $this->respond($projects_pending);
    }

    public function getInprogress()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        $projects_inprogress = $this->projectModel->where('inprogress', 'inprogress')->findAll();
        return $this->respond($projects_inprogress);
    }

    public function getCompleted()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        $projects_completed = $this->projectModel->where('completed', 1)->findAll();
        return $this->respond($projects_completed);
    }

    public function getProject($id = null)
    {

        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");

        $project = $this->projectModel->find($id);
        if (!$project) {
            return $this->response->setJSON('Project not found')->setStatusCode(404);
        }
        return $this->respond($project);
    }

    public function update($id = null)
    {
        $request = \Config\Services::request();
        $updateCompleted = $request->getPost('completed') ? 1 : 0;

        if ($updateCompleted === null) {
            return $this->failValidationErrors('The completed field is required.');
        }

        $data = [
            'completed' => $updateCompleted,
            'pending' => $updateCompleted ? null : 'pending',
            'inprogress' => null,
        ];

        if ($this->projectModel->update($id, $data)) {
            return $this->respondUpdated(['project_id' => $id, 'completed' => $updateCompleted], 'Project updated successfully.');
        } else {
            return $this->fail($this->projectModel->errors());
        }
    }

    public function addComment()
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");


        $request = \Config\Services::request();
        $commentModel = new CommentModel();
        $projectModel = new ProjectModel();
        $userModel = new UserModel();

        $post = $this->request->getPost();
        $post = json_decode(file_get_contents('php://input'), true);

        $user_id = $post['user_id'] ?? null;
        $project_id = $post['project_id'] ?? null;
        $comment_text = $post['comment_text'] ?? '';
        $user_location = $post['user_location'] ?? '';
        $user_country = $post['user_country'] ?? '';
        $user_state = $post['user_state'] ?? '';
        $user_city = $post['user_city'] ?? 'unknown city';

        if ($project_id === null || $user_id === null) {
            return $this->failValidationErrors('Both project_id and user_id are required.');
        }

        $project = $projectModel->find($project_id);
        if (!$project) {
            return $this->failNotFound('Project not found.');
        }

        $developersJson = $project['developers'];
        $developerIds = $this->getDeveloperIds($developersJson);

        if (!in_array($user_id, $developerIds)) {
            return $this->failForbidden('You are not assigned to this project and cannot comment on it.');
        }

        $user = $userModel->find($user_id);
        $data = [
            'user_id' => $user_id,
            'project_id' => $project_id,
            'comment_text' => $comment_text,
            'user_fullname' => $user['fullname'],
            'user_location' => $user_location,
            'user_country' => $user_country,
            'user_state' => $user_state,
            'user_city' => $user_city,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        if ($commentModel->save($data)) {
            $this->projectModel->update( $project_id, ["inprogress" => "inprogress"]);
            $this->projectModel->update( $project_id, ["pending" => null]);
            return $this->response->setJSON(['status' => 200, 'messages' => 'Comment added successfully.']);
        } else {
            return $this->response->setJSON(['status' => 400, 'messages' => 'Failed to add comment. Kindly check the input fields.']);
        }
    }

    public function addothercomments(){
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Credentials: true");
        $commentModel = new otherCommentModel();

        $request = \Config\Services::request();
        $post = $this->request->getPost();
        $post = json_decode(file_get_contents('php://input'), true);

        $dev_id = $post['dev_id'] ?? null;
        $learning = $post['learning'] ?? null;
        $comment_text = $post['comment_text'] ?? '';
        $fullname = $post['fullname'] ?? '';
        $user_location = $post['user_location'] ?? '';
        $user_country = $post['user_country'] ?? '';
        $user_state = $post['user_state'] ?? '';
        $user_city = $post['user_city'] ?? 'unknown city';
        

        $startTime = new \DateTime($post['startTime']);
        $endTime = new \DateTime($post['endTime']);

            $interval = $startTime->diff($endTime);
            $hours = $interval->h + ($interval->days * 24) + ($interval->i / 60);


        $data = [
            'dev_id' => $dev_id,
            'learning' => $learning,
            'comment_text' => $comment_text,
            'user_fullname' => $fullname,
            'user_location' => $user_location,
            'user_country' => $user_country,
            'user_state' => $user_state,
            'user_city' => $user_city,
            'created_at' => date('Y-m-d H:i:s'),
            'timeSpend' => $hours,
        ];

        if ($commentModel->save($data)) {
            return $this->response->setJSON(['status' => 200, 'messages' => 'Comment added successfully.']);
        } else {
            return $this->response->setJSON(['status' => 400, 'messages' => 'Failed to add comment. Kindly check the input fields.']);
        }


    }

    private function getDeveloperIds($developersJson)
    {
        $developersArray = json_decode($developersJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Invalid JSON string');
        }
        return array_map('intval', array_column($developersArray, 'id'));
    }
}
