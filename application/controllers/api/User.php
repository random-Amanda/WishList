<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class User extends \Restserver\Libraries\REST_Controller
{
    function __construct()
    {
        // Construct the parent class.
        parent::__construct();
        $this->load->model('UserModel');
    }

    public function index_get($id)
    {
        if ($id === NULL || $id <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id, set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized($this->input->get_request_header('Authorization'), $id)) {
            $id = (int) $id;
            // Check if the user exists.
            log_message('info', "[INFO]: getUserWithList method called with parameters " . $id);
            $user = $this->UserModel->getUserWithList($id);
            if ($user) {
                // Set the response and exit.
                $this->response(
                    //'user' => $user
                    $user,
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else {
                // Set the response and exit.
                $this->response([
                    'message' => 'No such User was found.'
                ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            // Not authorized.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function view_get($id)
    {
        if ($id === NULL || $id <= 0) {
            // Invalid id, set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        $id = (int) $id;
        // Check if the user exists.
        $user = $this->UserModel->getUserWithList($id);
        if ($user) {
            // Set the response and exit.
            $this->response(
                //'user' => $user
                $user,
                \Restserver\Libraries\REST_Controller::HTTP_OK
            );
        } else {
            // Set the response and exit.
            $this->response([
                'message' => 'No such User was found.'
            ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function index_post()
    {
        // validate request.
        if (
            $this->post() && $this->post('userName')
            && $this->post('password')
        ) {
            // validate username.
            if (!$this->UserModel->isExistingUserName($this->post('userName'))) {
                //creating user.
                $userid = $this->UserModel->createUser($this->post());
                if ($userid) {
                    // Report success.
                    $this->response([
                        // 'id' => $userid
                        'uid' => $userid
                    ], \Restserver\Libraries\REST_Controller::HTTP_OK);
                } else {
                    //Report failure.
                    $this->response([
                        'message' => 'Could not create user.'
                    ], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                $this->response([
                    'message' => "That name is taken, sorry. Try again."
                ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            // Invalid id, set the response and exit.
            $this->response([
                'message' => "Invalid request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put()
    {
        log_message('info', "[INFO]: inside User/index_put() ");
        // validate request.
        if (
            $this->put() && $this->put('userName')
            && $this->put('password')
        ) {
            log_message('info', "[INFO]: getAuthenticatedUser method called with parameters " . print_r($this->put(), TRUE));
            $user = $this->UserModel->getAuthenticatedUser($this->put());
            // validate username.
            if ($user !== NULL) {
                //authenticate user.
                if ($user) {
                    //return user token.
                    // $this->response(['id' => $user['uid'], 'token' => $user['jwt']], \Restserver\Libraries\REST_Controller::HTTP_OK);
                    $this->response(['uid' => $user['uid'], 'token' => $user['jwt']], \Restserver\Libraries\REST_Controller::HTTP_OK);
                } else {
                    // report authentication failure.
                    $this->response([
                        'message' => "Incorrect Password."
                    ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
                }
            } else {
                // report user validation failure.
                $this->response([
                    'message' => "Invalid Username."
                ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            // report bad request.
            $this->response([
                'message' => "Invalid request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_delete($uid)
    {
        // validate request.
        if ($uid === NULL || $uid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id, set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized($this->input->get_request_header('Authorization'), $uid)) {
            // update user status.
            if ($this->UserModel->updateUserStatus($uid, "FALSE")) {
                $this->response([
                    'status' => true,
                    'message' => "User logged out successfully."
                ], \Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                // report user logout failure.
                $this->response([
                    'message' => "Server encounted an internal error. Try again later."
                ], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            // User not logged in, set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}
