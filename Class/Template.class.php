<?php

class Template {
    private string $App = "app.html";
    private string $Menu = "menu.html";
    private string $components = "/resources/views/components/";
    private string $dir = __DIR__;
    
    public static function App()
    {
        $instanse = new self();
        return $instanse->makeApp();
    }

    public function makeApp()
    {
        $app = file_get_contents($this->root().$this->components.$this->App);
        $menu = file_get_contents($this->root().$this->components.$this->Menu);

        $app = str_replace(':menu', $menu, $app);

        return $app;
    }

    public function root()
    {
        return dirname($this->dir);
    }
}