

<?php
App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');
class UsersController extends AppController {
    public function login(){
        $this->autoRender = false;
        $params = $this->request->data;

        $username = isset($params['username']) ? $params['username'] : 'ngatp.16@gmail.com';
        $password = isset($params['password']) ? $params['password'] : 'vtm4test';

        $conditions["OR"] = [
            "User.UserEmail" => $username,
            "User.UserPhone" => $username,
        ];

        $user = $this->User->find("first", [
            "conditions" => $conditions
        ]);
        if($user){
            $correctPass = $this->authSuccess($password, $user["User"]["UserPassword"]);
            if($correctPass){
                return json_encode([
                    "success" => true,
                    "data" => $user
                ]);
            }else{
                return json_encode([
                    "success" => false,
                    "message" => __("Password is Incorrect")
                ]);
            }

        }else{
            return json_encode([
                "success" => false,
                "message" => __("Unauthorised")
            ]);
        }
    }

    public function authSuccess($password, $current_password) {

        $this->autoRender = false;
        $storedHash = $current_password;
        $newHash = Security::hash($password, 'blowfish', $storedHash);
        $correct = strcmp($storedHash, $newHash) == 0;

        if(!$correct){
            return false;

        }
        return true;

    }
}