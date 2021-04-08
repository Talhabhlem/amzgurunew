<?php
class a{
    function my(){
        echo __CLASS__;
    }
}
class b extends a{
    function my(){
        echo __CLASS__;
    }
}
class c extends b{
    function my(){
        echo __CLASS__;
        a::my();
    }
    function abc(){
        self::my();
    }
}
$obj=new c;
$obj->abc();
$obj->my();
?>