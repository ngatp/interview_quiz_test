<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('Security', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class ApiController extends Controller {

    public function getInfoWeather(){
        $this->autoRender = false;
        $content =  file_get_contents("http://api.openweathermap.org/data/2.5/group?id=1580578,1581129,1581297,1581188,1587923&units=metric&appid=91b7466cc755db1a94caf6d86a9c788a");
        $weatherInfo = json_decode($content,true)["list"];

        $data = [];
        foreach($weatherInfo as $item){
            $temp = [];
            $cityId = isset($item["id"]) ? $item["id"] : "";
            $cityName = isset($item["name"]) ? $item["name"] : "";
            $weatherMain = isset($item["weather"][0]["main"]) ? $item["weather"][0]["main"] : "";
            $weatherDescription = isset($item["weather"][0]["description"]) ? $item["weather"][0]["description"] : "";
            $icon = isset($item["weather"][0]["icon"]) ? $item["weather"][0]["icon"] : "";
            $weatherIcon = "";
            if($icon){
                $weatherIcon = "http://openweathermap.org/img/wn/".$icon."@2x.png";
            }
            $mainTemp = isset($item["main"]["temp"]) ? $item["main"]["temp"] : "";
            $mainHumidity = isset($item["main"]["humidity"]) ? $item["main"]["humidity"] : "";
            $temp = [
                "cityId" => $cityId,
                "cityName" => $cityName,
                "weatherMain" => $weatherMain,
                "weatherDescription" => $weatherDescription,
                "weatherIcon" => $weatherIcon,
                "mainTemp" => "$mainTemp",
                "mainHumidity" => "$mainHumidity",
            ];
            $data[] = $temp;
        }
        $msg = 'Current weather information of cities';
        $statusCode = 200;
        return $this->response->body(json_encode(array("data" => $data, "message" => $msg, 'statusCode' => $statusCode)));
    }

    /*public function login() {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            } else {

                $this->Session->setFlash( __('Username or password incorrect'));
            }
        }
    }*/


}
