<?php

require("user/User.php");
require("db/DB.php");
require("chat/Chat.php");
require("game/Game.php");
require("gamer/Gamer.php");

class Application {
    function __construct(){
        $config = json_decode(file_get_contents('./config/config.json'),true);
        $db = new DB($config["DataBase"]);
        $this->user = new User($db);
        $this->chat = new Chat($db);
        $this->game = new Game($db);
        $this->gamer = new Gamer($db);
    }

    //функция проверки полученных значений в запросе
    function validQuery($value,$type) {
    }


    ////////////////////////////////////////
    //////////////forUser///////////////////
    ////////////////////////////////////////

    public function login($params) {
        if ($params['login'] && $params['password']) {
        return $this->user->login($params['login'],$params['password']);
        }
    }

    public function registration($params) {
        [
        'login' => $login,
        'password' => $password,
        'name' => $name
        ] = $params;
        if ($login && $password && $name) {
            return $this->user->registration($login,$password,$name);
        }
    }

    public function logout($params) {
            $user=$this->user->getUser($params['token']);
            if ($user){
                return $this->user->logout($user);
            }
    }


    ////////////////////////////////////////
    //////////////forChat///////////////////
    ////////////////////////////////////////
    
    public function sendMessage($params) {
        ['token'=>$token,
        'message'=>$message,
        'messageTo'=>$messageTo
        ] = $params;
        $user = $this->user->getUser($token);
        if ($user && $message) {
            return $this->chat->sendMessage($user, $message, $messageTo);
        }
    }
    
    public function getMessages($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            return $this->chat->getMessages($params['hash'], $user);
        }
    }

    public function getLoggedUsers($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            return $this->chat->getLoggedUsers();
        }
    }

    ////////////////////////////////////////
    //////////////forGame///////////////////
    ////////////////////////////////////////


    public function getMap($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            return $this->game->getMap();
        }
    }

    public function getScene($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            return $this->game->getScene($params['updates'], $params['unitsHash'], $params['castlesHash']);
        }
    }

    ////////////////////////////////////////
    //////////////forGamer//////////////////
    ////////////////////////////////////////

    public function getCastle($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            $gamer = $this->gamer->getGamer($user);
            if (!$gamer) {
                $this->gamer->addCastle($user);
            }
            return $this->gamer->getCastle($gamer);
        }
    }

    public function upgradeCastle($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            $gamer = $this->gamer->getGamer($user);
            return $this->gamer->upgradeCastle($gamer);
        }
    }

    public function buyUnit($params) {
        $user = $this->user->getUser($params['token']);
        if ($user) {
            $gamer = $this->gamer->getGamer($user);
            if ($gamer) {
                return $this->game->buyUnit($user, $params['unitType']);
            }
        } 
    }
}