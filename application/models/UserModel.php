<?php
require_once 'jwt/php-jwt-master/src/BeforeValidException.php';
require_once 'jwt/php-jwt-master/src/ExpiredException.php';
require_once 'jwt/php-jwt-master/src/SignatureInvalidException.php';
require_once 'jwt/php-jwt-master/src/JWT.php';

use \Firebase\JWT\JWT;

require APPPATH . '/config/token.php';


class UserModel extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    private function isLoggedIn($uid)
    {
        $this->db->select('LOGIN_STATUS');
        $this->db->from('USER');
        $this->db->where('UID', $uid);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            if ($query->row()->LOGIN_STATUS == 'TRUE') {
                return true;
            } else {
                return false;
            }
        } else {
            return null;
        }
    }

    public function updateUserStatus($uid, $status)
    {
        log_message('info', "[INFO]: Updating LOGIN_STATUS of user with UID ".$uid);
        $data = [
            'LOGIN_STATUS' => $status,
        ];
        $this->db->where('UID', $uid);
        $this->db->update('USER', $data);
        log_message('info', "[INFO]: SQL query : " . print_r($this->db->last_query(), TRUE));
        if ($this->db->trans_status() === FALSE) {
            log_message('error', "DB ERROR: " . print_r($this->db->error(), TRUE));
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            log_message('info', "[INFO]: Successfully updated user status.");
            return true;
        }
    }

    public function getUserWithList($userId)
    {
        $this->db->select('
        USER_NAME,
        LOGIN_STATUS,
        LIST.ID AS LIST_ID');
        $this->db->from('USER');
        $this->db->join('LIST', 'LIST.UID = USER.UID');
        $this->db->where('USER.UID', $userId);
        $query = $this->db->get();
        log_message('info', "[INFO]: SQL query : " . print_r($this->db->last_query(), TRUE));
        log_message('info', "[INFO]: SQL query result : " . print_r($query, TRUE));
        if ($query->num_rows() != 0) {
            $user = (object) [
                'userName' => $query->row()->USER_NAME,
                'loggedIn' => true,
                'lid' => $query->row()->LIST_ID
            ];
            return $user;
        } else {
            return null;
        }
    }

    public function getUser($userId)
    {
        $this->db->select('
        USER_NAME,
        LOGIN_STATUS');
        $this->db->from('USER');
        $this->db->where('USER.UID', $userId);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            $user = (object) [
                'userName' => $query->row()->USER_NAME,
                'loggedIn' => true,
            ];
            return $user;
        } else {
            return null;
        }
    }

    public function isExistingUserName($userName)
    {
        $this->db->select('
        USER_NAME,
        UID');
        $this->db->from('USER');
        $this->db->where('USER_NAME', $userName);
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return true;
        } else {
            return false;
        }
    }

    public function isAuthorized($authHeader, $uid)
    {
        $jwt = explode(" ", $authHeader)[1];
        if ($jwt) {

            try {
                $decoded = JWT::decode($jwt, Token::SECRET_KEY, array('HS256'));
                if ($decoded->data->Uid == $uid) {
                    if ($this->isLoggedIn($uid)) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function createUser($payload)
    {
        $userObject = (object) [
            'USER_NAME' => $payload['userName'],
            'PASSWORD' => $payload['password'],
            'LOGIN_STATUS' => "FALSE"
        ];
        $userObject->PASSWORD = password_hash($userObject->PASSWORD, PASSWORD_DEFAULT);
        $this->db->trans_begin();
        $this->db->insert('USER', $userObject);
        $user_id = $this->db->insert_id();
        if ($this->db->trans_status() === FALSE) {
            log_message('error', "DB ERROR: " . print_r($this->db->error(), TRUE));
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        if (!is_null($user_id)) {
            return $user_id;
        } else {
            return null;
        }
    }

    public function getAuthenticatedUser($user)
    {
        $this->db->trans_begin();
        $this->db->select('
        USER_NAME,
        PASSWORD,
        UID');
        $this->db->from('USER');
        $this->db->where('USER_NAME', $user['userName']);
        $query = $this->db->get();
        log_message('info', "[INFO]: SQL query : " . print_r($this->db->last_query(), TRUE));
        log_message('info', "[INFO]: SQL query result : " . print_r($query, TRUE));
        if ($query->num_rows() != 0) {
            if (!password_verify($user['password'], $query->row()->PASSWORD)) {
                log_message('error', "[ERROR]: Passwords did not match");
                return false;
            } else {
                //get access token
                log_message('info', "[INFO]: Generating JWT");
                $authenticatedUser['uid']  = $query->row()->UID;
                $secret_key = Token::SECRET_KEY;
                $issuer_claim = Token::SERVER_NAME;
                $issuedat_claim = time(); // time of token creation
                $notbefore_claim = $issuedat_claim; //not valid before 1 seconds
                $expire_claim = $issuedat_claim + 600; // expires in 10 minuites
                $token = array(
                    "iss" => $issuer_claim,
                    "iat" => $issuedat_claim,
                    "nbf" => $notbefore_claim,
                    "exp" => $expire_claim,
                    "data" => array(
                        "Uid" => $query->row()->UID
                    )
                );
                $authenticatedUser['jwt']  =  JWT::encode($token, $secret_key);
                if ($this->updateUserStatus($query->row()->UID, "TRUE")) {
                    return $authenticatedUser;
                } else {
                    return false;
                }
            }
        } else {
            return null;
        }
    }
}
