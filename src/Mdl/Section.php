<?php

namespace Boke0\Mechanism\Mdl;

class Section extends Node{
    public function __construct($path,$struct,$converter,$env){
        parent::__construct($path,$converter,$env);
        $this->struct=$struct;
    }
    public function __get($k){
        switch($k){
            case "parent":
                return new Section(dirname($this->path),$this->struct,$this->converter,$this->env);
            case "children":
                $dir=scandir($this->path);
                $children=array();
                foreach($dir as $d){
                    if(substr($d,0,1)!="."&&is_dir("$this->path/$d")){
                        array_push($children,new Section("{$this->path}/$d",$this->struct,$this->converter,$this->env));
                    }
                }
                return $children;
            case "pages":
                $dir=scandir($this->path);
                $children=array();
                foreach($dir as $f){
                    if(substr($f,0,1)!="."&&is_file("$this->path/$f")){
                        array_push($children,new Page("{$this->path}/$f",$this->struct,$this->converter,$this->env));
                    }
                }
                return $children;
            case "permalink":
                return $this->struct->link($this->path);
            case "menu":
                return new Menu($this->path,$this->struct,$this->converter,$this->env);
            default:
                return $this->$k;
        }
    }
}
