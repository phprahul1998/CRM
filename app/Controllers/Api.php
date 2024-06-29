<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;

class Api extends ResourceController
{
    protected $usersModel;

    public function __construct()
    {
        $this->usersModel = model('App\Models\Users_model');
        $this->attendanceModel = model('App\Models\Attendance_model');

    }

    public function get_emplogin()
    {
        if ($this->request->getMethod() !== 'post') {
            return $this->respond([
                'message' => 'Method Not Allowed',
                'data' => ''
            ], 405); 
        }
    
        try {
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
    
            // Validate email and password
            if (empty($email)) {
                return $this->respond([
                    'message' => 'Enter Your Email Id.',
                    'data' => ''
                ]);
            }
    
            if (empty($password)) {
                return $this->respond([
                    'message' => 'Enter your Password',
                    'data' => ''
                ]);
            }
            $userData = $this->usersModel->userApiauthenticate($email, $password);    
            switch ($userData) {
                case 'passnotfound':
                    $response = [
                        'message' => 'Please enter correct password',
                        'data' => ''
                    ];
                    break;
                case 'emailnotfound':
                    $response = [
                        'message' => 'Please enter correct Email',
                        'data' => ''
                    ];
                    break;
                default:
                    $response = [
                        'message' => 'Login Successfully',
                        'data' => $userData
                    ];
                    break;
            }
    
            return $this->respond($response);
    
        } catch (\Exception $e) {
            return $this->respond([
                'message' => $e->getMessage(),
                'data' => ''
            ]);
        }
    }
    

    public function clockIn(){
        $clockINdata = [];
        if ($this->request->getMethod() !== 'post') {
            return $this->respond([
                'message' => 'Method Not Allowed',
                'data' => ''
            ], 405); // 405 Method Not Allowed status code
        }
        $user_id = $this->request->getVar('user_id');
        $location = $this->request->getVar('location');
        if (!$user_id) {
            $clockINdata = [
                'message' => 'Enter Your user Id.',
                'data' => ''
            ];
            return $this->respond($clockINdata);
        }

        if (!$location) {
            $clockINdata = [
                'message' => "Location can't be Empty.",
                'data' => ''
            ];
            return $this->respond($clockINdata);
        }
        try {
            $checkuser = $this->attendanceModel->check_user_isclockin($user_id);
            if ($checkuser) {
                $clockINdata = [
                    'message' => 'You have already clocked in for today.',
                    'data' => $this->formatClockinData($checkuser),
                ];
            } else {
                $clockIndata = $this->attendanceModel->log_time($user_id, 'bye',$location);
                $clockinData = $this->attendanceModel->getUserInfoClockin($user_id, $clockIndata);
                $clockINdata = [
                    'message' => 'Clock in successfully.',
                    'data' => $this->formatClockinData($clockinData),
                ];
            }
    
            return $this->respond($clockINdata);
    
        } catch (\Exception $e) {
            $userData = [
                'message' => $e->getMessage(),
                'data' => ''
            ];
            return $this->respond($userData);
        }
    }
    
    private function formatClockinData($clockinData) {
        return [
            'id' => $clockinData->id,
            'status' =>  $clockinData->status,
            'user_id' => $clockinData->user_id,
            'in_time' => format_to_time($clockinData->in_time),
            'in_date' => convertDate($clockinData->in_time),
            'location' => $clockinData->location
        ];
    }

    public function clockOut(){
        $clockOutdata = [];
        if ($this->request->getMethod() !== 'post') {
            return $this->respond([
                'message' => 'Method Not Allowed',
                'data' => ''
            ], 405); // 405 Method Not Allowed status code
        }
        $user_id = 1;
        if (!$user_id) {
            $clockOutdata = [
                'message' => 'Enter Your user Id.',
                'data' => ''
            ];
            return $this->respond($clockOutdata);
        }

        try {
            $checkuser = $this->attendanceModel->check_user_isclockin($user_id);
            if ($checkuser) {
                $attId = $checkuser->id;
                $status = $checkuser->status;
                if($status=='incomplete'){
                    $udpateclockout = $this->attendanceModel->getUpdateclockout($user_id,$attId);
                }else{
                    $udpateclockout = $this->attendanceModel->check_user_isclockin($user_id);
                }
                 $clockOutdata = [
                    'message' => 'Clock out successfully.',
                    'data' => $this->formatClockOutData($udpateclockout),
                ];
               
            }
    
            return $this->respond($clockOutdata);
    
        } catch (\Exception $e) {
            $userData = [
                'message' => $e->getMessage(),
                'data' => ''
            ];
            return $this->respond($userData);
        }
    }

    private function formatClockOutData($clockinData) {
        return [
            'id' => $clockinData->id,
            'status' =>  $clockinData->status,
            'user_id' => $clockinData->user_id,
            'out_time' => format_to_time($clockinData->out_time),
            'out_date' => convertDate($clockinData->out_time),
            'location' => $clockinData->location
        ];
    }
    

}
?>
