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
        $userData=[];
        try {
            $email = $this->request->getVar('email');
            $password = $this->request->getVar('password');
            if (!$email) {
                $userData =[
                    'message'=>'Enter Your Email Id.',
                    'data'=>''
                ];
                return $this->respond($userData);
            }
            if(!$password){
                $userData =[
                    'message'=>'Enter your Password',
                    'data'=>''
                ];
                return $this->respond($userData);
            }
            $userData = $this->usersModel->userApiauthenticate($email, $password);
            if ($userData =='passnotfound') {
                $userData =[
                    'message'=>'Please enter correct password',
                    'data'=>''
                ];
                return $this->respond($userData);
            }
            if ($userData =='emailnotfound') {
                $userData =[
                    'message'=>'Please enter correct Email',
                    'data'=>''
                ];
                return $this->respond($userData);
            }
            $userData =[
                'message'=>'Login Successfully',
                'data'=>$userData
            ];
            return $this->respond($userData);

        } catch (\Exception $e) {
            $userData =[
                'message'=>$e->getMessage(),
                'data'=>''
            ];
            return $this->respond($userData);
        }
    }

    public function clockIn(){
        $clockINdata = [];
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
            'out_time' => $clockinData->out_time,
            'location' => $clockinData->location
        ];
    }
    

}
?>
