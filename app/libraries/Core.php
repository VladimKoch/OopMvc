<?php
/**
 * App core Class
 * Create URL & load core controller
 * URL format -/ contorller/method/params
 */



 class Core {
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];



    public function __construct()
    {
      // print_r($this->getUrl()); 
      $url = $this -> getUrl();

      //Look in controllers for first value
      if(file_exists('../app/controllers/' . ucwords($url[0]).'.php')){
         //if exists, set as controller
         $this->currentController = ucwords($url[0]);
         // Unset 0 Index
         unset($url[0]);
      }

      //Require the Controller
      require_once '../app/controllers/' . $this->currentController . '.php';

      //Instantiate controller Class
      $this->currentController = new $this->currentController;

      //check for second part of Url

      if(isset($url[1])){
         // check to see if method exists in controller
         if(method_exists($this->currentController, $url[1])){
            $this-> currentMethod = $url[1];
            //unset url 1 url
            unset($url[1]);
         }
      }
      // Get params
      $this->params = $url ? array_values($url) : [];

      //Call a callback with array of params
      call_user_func_array([$this->currentController, $this->currentMethod],$this->params);
    }

    public function getURL(){
       if(isset($_GET['url'])){
         $url = rtrim($_GET['url'],'/');
         $url = filter_var($url, FILTER_SANITIZE_URL);
         $url = explode('/',$url);
         return $url;
       }
    }
 }