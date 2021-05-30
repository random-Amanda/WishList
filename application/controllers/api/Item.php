<?php

defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class Item extends \Restserver\Libraries\REST_Controller
{
    function __construct()
    {
        // Construct the parent class.
        parent::__construct();
        $this->load->model('UserModel');
        $this->load->model('ItemModel');
        $this->load->model('ListModel');
    }

    public function index_get($itemid)
    {
        if ($itemid === NULL || $itemid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized(
            $this->input->get_request_header('Authorization'),
            $this->ItemModel->getUID($itemid)
        )) {
            // Check if the Item exists.
            $item = $this->ItemModel->getItem($itemid);
            if ($item) {
                $this->response(
                    $item,
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else {
                // Set the response and exit.
                $this->response([
                    'message' => 'Could not find Item.'
                ], \Restserver\Libraries\REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            // User not authorized , set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function view_get($itemid, $uid)
    {
        if ($itemid === NULL || $itemid <= 0 || $uid === NULL || $uid <= 0) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if (
            $this->ItemModel->getUID($itemid) == $uid
        ) {
            // Check if the Item exists.
            $item = $this->ItemModel->getItem($itemid);
            if ($item) {
                $this->response(
                    $item,
                    \Restserver\Libraries\REST_Controller::HTTP_OK
                );
            } else {
                // Set the response and exit.
                $this->response([
                    'message' => 'Could not find Item.'
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
            $this->post() && $this->post('title') && $this->post('url') && $this->post('price')
            && $this->post('priority') && $this->post('lid') && $this->post('qty')
            && $this->input->get_request_header('Authorization')
        ) {
            // check if user is logged in && if the access token is valid
            if ($this->UserModel->isAuthorized(
                $this->input->get_request_header('Authorization'),
                $this->ListModel->getUID($this->post('lid'))
            )) {
                //creating item.
                $itemid = $this->ItemModel->createItem($this->post());
                if ($itemid) {
                    // Report success.
                    $this->response([
                        'itemid' => $itemid
                    ], \Restserver\Libraries\REST_Controller::HTTP_OK);
                } else {
                    //Report failure.
                    $this->response([
                        'message' => 'Could not create item.'
                    ], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
                }
            } else {
                // User not logged in, set the response and exit.
                $this->response([
                    'message' => "Unauthorized request."
                ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
            }
        } else {
            // Incorrect request.
            $this->response([
                'message' => "Invalid request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function index_put($itemid)
    {
        // validate request.
        if ($itemid === NULL || $itemid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized(
            $this->input->get_request_header('Authorization'),
            $this->ItemModel->getUID($itemid)
        )) {
            //editing item.
            $itemid = $this->ItemModel->updateItem($this->put(), $itemid);
            if ($itemid) {
                // Report success.
                $this->response([
                    'itemid' => $itemid
                ], \Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                //Report failure.
                $this->response([
                    'message' => 'Could not edit item.'
                ], \Restserver\Libraries\REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
            }
        } else {
            // User not logged in, set the response and exit.
            $this->response([
                'message' => "Unauthorized request."
            ], \Restserver\Libraries\REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function index_delete($itemid)
    {
        // validate request.
        if ($itemid === NULL || $itemid <= 0 || !$this->input->get_request_header('Authorization')) {
            // Invalid id or no header set the response and exit.
            $this->response([
                'message' => "Invalid Request."
            ], \Restserver\Libraries\REST_Controller::HTTP_BAD_REQUEST);
        }
        // check if user is logged in && if the access token is valid
        if ($this->UserModel->isAuthorized(
            $this->input->get_request_header('Authorization'),
            $this->ItemModel->getUID($itemid)
        )) {
            //deleting item.
            if ($this->ItemModel->deleteItem($itemid)) {
                // Report success.
                $this->response([
                    'status' => true
                ], \Restserver\Libraries\REST_Controller::HTTP_OK);
            } else {
                //Report failure.
                $this->response([
                    'message' => 'Could not delete item.'
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
