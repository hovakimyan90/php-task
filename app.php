<?php

require_once 'db.php';

class App
{
    private $config;
    private $userData;
    private $image;
    private $db;

    public function __construct()
    {
        $this->config = require 'config.php';
        $this->db = new Db($this->config['db']);

        $this->init();
    }

    private function init()
    {
        $this->selectRandomImageSource();
        $this->collectUserData();
    }

    private function selectRandomImageSource()
    {
        $files = glob($this->config['img_directory'] . '/*.*');
        $file = array_rand($files);

        $this->image = $files[$file];
    }

    private function collectUserData()
    {
        $this->userData = [
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'view_date' => date('Y-m-d H:i:s'),
            'page_url' => $_SERVER['HTTP_REFERER'],
            'views_count' => 1
        ];
    }

    private function showImage()
    {
        $image = imagecreatefromjpeg($this->image);
        header('Content-Type: image/jpeg');
        imagejpeg($image);
        imagedestroy($image);
    }

    private function saveData()
    {
        $this->db->save($this->userData);
    }

    public function start()
    {
        $this->saveData();
        $this->showImage();
    }
}
