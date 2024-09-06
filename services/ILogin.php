<?php
interface ILogin {
    public static function login(); 
}

interface IRegister {
    public static function register();
}

abstract class Login {
    abstract function login();
}