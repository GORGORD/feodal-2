<?php
//ничего не меняем в этом файле
header("Access-Control-Allow-Origin: *");
require("application/Application.php");

function router($params) {
    $method = $params['method'];
    if ($method) {
        $app = new Application();
        switch ($method) {
            case 'check' : return true;
            ////////
            //USER//
            ////////
            case 'login': return $app->login($params);
            case 'logout': return $app->logout($params);
            case 'registration': return $app->registration($params);
            ////////
            //CHAT//
            ////////
            case 'getLoggedUsers': return $app->getLoggedUsers($params);
            case 'sendMessageAll': {
                $params['messageTo'] = "NULL";
                return $app->sendMessage($params);
            };
            case 'sendMessageTo': return $app->sendMessage($params);
            case 'getMessages': return $app->getMessages($params);
            ///////////
            // GAMER //
            ///////////
            case 'getCastle': return $app->getCastle($params);
            case 'upgradeCastle': return $app->upgradeCastle($params);
            case 'buyUnit': return $app->buyUnit($params);
            ////////
            //GAME//
            ////////
            case 'getMap': return $app->getMap($params);
            case 'getScene': return $app->getScene($params);
            case 'getUnitsTypes': return $app->getUnitsTypes($params);
            //case 'updateUnits': return $app->updateUnits($params);
            case 'robVillage': return $app->robVillage($params);
            case 'destroyVillage': return $app->destroyVillage($params);
            case 'destroyCastle': return $app->destroyCastle($params);
        }
    }
    return false;
}

function answer($data) {
    if ($data) {
        return array(
            'result' => 'ok',
            'data' => $data
        );
    }
    return array(
        'result' => 'error'
    );
}

echo(json_encode(answer(router($_GET))));
