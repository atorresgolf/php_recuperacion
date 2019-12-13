<?php

//Esta función simula a la función (dd), de Laravel la cual simplemente es un dump and die (var_dump)

function dd($dato){
    echo "<pre>";
        var_dump($dato);
        exit;
    echo "</pre>";
}