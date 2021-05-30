<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Wishlist extends \Restserver\Libraries\REST_Controller
{
    function __construct()
    {
        // Construct the parent class.
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('ListModel');
    }

    public function index_get($lid)
    {
        if ($lid === NULL || $lid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized(
            $this->input->get_request_header('Authorization'),
            $this->ListModel->getUID($lid)
        )) {
            // Check if the list exists.
            $listWithItems = $this->ListModel->getListWithItems($lid);
            log_message('info', "[INFO]: getListWithItems method called with parameters " . $lid);
            if ($listWithItems) {
                $this->response(
                    $listWithItems,
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else {
                $list = $this->ListModel->getList($lid);
                if ($list) {
                    $this->response(
                        $list,
                        \Restserver\Libraries\REST_Controller::HTTP_OK
                    );
                } else {
                    // Set the response and exit.
                    $this->response([
                        'message' => 'Could not find List.'
                    ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
                }
            }
        } else {
            // User not authorized , set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function view_get($lid, $uid)
    {
        if ($lid === NULL || $lid <= 0 || $uid === NULL || $uid <= 0) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if (
            $this->ListModel->getUID($lid) == $uid
        ) {
            // Check if the list exists.
            $listWithItems = $this->ListModel->getListWithItems($lid);
            if ($listWithItems) {
                $this->response(
                    $listWithItems,
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else {
                $list = $this->ListModel->getList($lid);
                if ($list) {
                    $this->response(
                        $list,
                        \Restserver\Libraries\REST_Controller::HTTP_OK
                    );
                } else {
                    // Set the response and exit.
                    $this->response([
                        'message' => 'Could not find List.'
                    ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
                }
            }
        } else {
            // User not authorized , set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function items_get($lid)
    {
        if ($lid === NULL || $lid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized(
            $this->input->get_request_header('Authorization'),
            $this->ListModel->getUID($lid)
        )) {
            // Check if the list exists.
            $listItems = $this->ListModel->getItems($lid);
            if ($listItems) {
                $this->response([
                    $listItems
                ], \Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                // Set the response and exit.
                $this->response([
                    'message' => 'Could not find items of List.'
                ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            // User not authorized , set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_post()
    {
        // validate request.
        if (
            $this->post() && $this->post('listName')
            && $this->post('listdesc') && $this->post('uid')
            && $this->input->get_request_header('Authorization')
        ) {
            // check if user is logged in && if the access token is valid
            if ($this->UserModel->isAuthorized(
                $this->input->get_request_header('Authorization'),
                $this->post('uid')
            )) {
                //creating list.
                $listid = $this->ListModel->createList($this->post());
                if ($listid) {
                    // Report success.
                    $this->response([
                        'id' => $listid
                    ], \Restserver\Libraries\REST_Controller::HTTP_OK);
                } else {
                    //Report failure.
                    $this->response([
                        'message' => 'Could not create List.'
                    ], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                // User not logged in, set the response and exit.
                $this->response([
                    'message' => "Unauthorized request."
                ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            // Invalid id, set the response and exit.
            $this->response([
                'message' => "Invalid request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }
}
