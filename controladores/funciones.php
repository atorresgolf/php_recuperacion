<?php
//Este archivo representa el conjunto de funciones que controlaran toda la lógica de nuestra propuesta, y de esa forma no la disponemos en nuestras páginas donde tenemos las vistas al usuario.

//Aquí de entrada activo la session, de no hacerlo no se almacenan las variables de sessión del usuario que se loguea
session_start();


//Aquí comienzo a programar las funciones generales de mi sistema
function validar($datos,$imagen){
    //Este representa mi array donde voy a ir almacenando los errores, que luego muestro en la vista al usuario.|
    $errores = [];
    $userName = trim($datos['userName']);
    if(empty($userName )){
        $errores['userName']="El campo nombre no lo puede dejar en blanco..";
    }
    $email = trim($datos['email']);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errores['email']="Email inválido...";
    }
    $password = trim($datos['password']);
    if(empty($password)){
        $errores['password']="El password no puede ser blanco...";
    }elseif (!is_numeric($password)) {
        $errores['password']="El password debe ser numérico...";
    }elseif (strlen($password)<6) {
        $errores['password']="El password como mínimo debe tener 6 caracteres...";
    }
    $passwordRepeat = trim($datos['passwordRepeat']);
    if($password != $passwordRepeat){
        $errores['passwordRepeat']="Las contraseñas deben ser iguales";
    }
    if(isset($_FILES)){
        $nombre = $imagen['avatar']['name'];
        $ext = pathinfo($nombre,PATHINFO_EXTENSION);
        if($imagen['avatar']['error']!=0){
            $errores['avatar']="Debes subir tu foto...";
    
        }elseif ($ext != "jpg" && $ext != "png") {
            $errores['avatar']="Formato inválido";
        }        
    }
    return $errores;   
}

//Esta función se encarga de validad los datos queel usuario coloca en el formulario de Login
function validarLogin($datos){
    $errores=[];
    $email = trim($datos['email']);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errores['email']="Email inválido...";
    }
    $password = trim($datos['password']);
    if(empty($password)){
        $errores['password']="El password no puede ser blanco...";
    }elseif (!is_numeric($password)) {
        $errores['password']="El password debe ser numérico...";
    }elseif (strlen($password)<6) {
        $errores['password']="El password como mínimo debe tener 6 digitos...";
    }
    return $errores;
}

function validarOlvidePassword($datos){
    //Este representa mi array donde voy a ir almacenando los errores, que luego muestro en la vista al usuario.|
    $errores = [];
    $email = trim($datos['email']);
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errores['email']="Email inválido...";
    }
    $password = trim($datos['password']);
    if(empty($password)){
        $errores['password']="El password no puede ser blanco...";
    }elseif (!is_numeric($password)) {
        $errores['password']="El password debe ser numérico...";
    }elseif (strlen($password)<6) {
        $errores['password']="El password como mínimo debe tener 6 caracteres...";
    }
    $passwordRepeat = trim($datos['passwordRepeat']);
    if($password != $passwordRepeat){
        $errores['passwordRepeat']="Las contraseñas deben ser iguales";
    }
    return $errores;   
}

//Esta función nos ayuda a preparar el array asociativo de mi registro
function armarRegistro($datos, $avatar){

$password = password_hash($datos['password'], PASSWORD_DEFAULT);
	 	 
    
$registro = [
    'userName' => $datos['userName'],
    'email' => $datos['email'],
    'password' => $password,
    'avatar' => $avatar

];
    return $registro;
var_dump($registro);
}

//Función que nos permite guardar los datos en nuestro archivo json y de esa forma persistir los datos dispuestos por el usuario en el formulario

function guardarRegistro($registro){
//abrirBD manualmente sin usar fcion abrirBaseDato() xq m tira error
//dd($registro);
    if(file_exists('usuarios.json'))
    {
        //abro bd
         $arrayUsuarios = abrirBase();
        // $archivo = "usuarios.json";
         //$archivoJson = file_get_contents($archivo);
        //dd($archivoJson);
         //una vez obtenido el archivo, convertirlo en array php.
        
    // $arrayUsuarios = json_decode($archivoJson, true);
        //fin abrir bd
          /*    */
          //dd($arrayUsuarios);
    }else
    {
        $arrayUsuarios=[];
    }
    
        $arrayUsuarios[]= [ 
            'userName' => $registro['userName'],
            'email' => $registro['email'],
            'password' => $registro['password'],
            'avatar'=> $registro['avatar']
        ];
  
      
    //guardar
    //dd($arrayUsuarios);
    //codifico de nuevo a jason
    $json = json_encode($arrayUsuarios);
//dd($json);
   //sobreescribir el archivo con los datos nuevos
    file_put_contents("usuarios.json", $json);
    
    header('location: index.php');
	 exit;
}
  


//Esta función nos permite armar el registro cuando el usuario selecciona el avatar
function armarAvatar($imagen){
    $nombre = $imagen['avatar']['name'];
    $ext = pathinfo($nombre,PATHINFO_EXTENSION);
    $archivoOrigen = $imagen['avatar']['tmp_name'];
    $archivoDestino = dirname(__DIR__);
    $archivoDestino = $archivoDestino."/imagenes/";
    $avatar = uniqid();
    $archivoDestino = $archivoDestino.$avatar.".".$ext;
    //Aquí estoy copiando al servidor nuestro archivo destino creado
    move_uploaded_file($archivoOrigen,$archivoDestino);
    //Aquí estoy retornando al usuario sólo la imagen, la cual será guardada en el archivo json
    $avatar = $avatar.".".$ext;
    return $avatar;
}



//Función que nos permite buscar por email, a ver si el usuario existe o no en nuestra base de datos, que en este momento es un archivo json.
function buscarPorEmail($email){
  //abro bd
  
           // $archivoJson = file_get_contents("usuarios.json");
            // $usuarios = json_decode($archivoJson, true);

  //llamo a mi funcion
  $usuarios = abrirBase();


    //tengo el array de usuarios
    //var_dump($usuarios);
    if($usuarios !== null )
    {
        foreach ($usuarios as $usuario)
        {
             //por cada ususario pregunatr si el email
             if ($usuario["email"] == $email) 
             {
                $retorno = $usuario;
                  break;
             }
            /*//ya viene hecho
            else{$errores['email'] = "El usuario no exite";}*/
         }   
    }  
    else
        {
            $retorno = null;
         }
    return $retorno;
    	
}


//reemplazo funcion abrirBaseDatos() porque m tira error

function abrirBase()
{
    if(file_exists('usuarios.json')){
        $archivoJson = file_get_contents('usuarios.json');
         $usuarios = json_decode($archivoJson, true);

        
        return $usuarios;
    }else{
        return null;
    }  
    
    
     
}


//Esta función abre nuestro archivo json y lo prepara para eliminar el último registro en blanco y además genero el array asociativo del mismo. Convierto de json a array asociativo para mas adelante con la funcion "bucarEmail" poder recorrerlo y verificar si el usuario existe o no en mi base de datos, dicha verificación la hago por el email del usuario, ya que es el dato único que tengo del usuario
function abrirBaseDatos(){
    if(file_exists('usuarios.json')){
        $archivoJson = file_get_contents('usuarios.json');
        //Aquí lo que hago es generar cada array con un salto de linea, para poder verlo ejecuta aca un dd($archivoJson)
        $archivoJson = explode(PHP_EOL,$archivoJson);
        //Aquí saco el ultimo registro, el cual está en blanco
        array_pop($archivoJson);
        //Aca recorro el array y creo mi array con todos los usuarios
        foreach ($archivoJson as  $usuarios) {
            $arrayUsuarios[]= json_decode($usuarios,true);
        }
        //Aca devuelvo el array de usuarios con todos sus datos
        return $arrayUsuarios;
    }else{
        return null;
    }
}
//Aqui creo los las variables de session y de cookie de mi usuario que se está loguendo
function seteoUsuario($usuario,$dato){
    $_SESSION['nombre']=$usuario['userName'];
    $_SESSION['email']=$usuario['email'];
    $_SESSION['avatar']=$usuario['avatar'];
    $_SESSION['role']=$usuario['role'];
    if(isset($dato['recordarme'])){
        setcookie('email',$usuario['email'],time()+3600);
        setcookie('password',$dato['password'],time()+3600);
    }
}
//Con esta función controlo si el usuario se logueo o ya tenemos las cookie en la máquina
function validarUsuario(){
    if(isset($_SESSION['email'])){
        return true;
    }elseif(isset($_COOKIE['email'])){
        $_SESSION['email']=$_COOKIE['email'];
        return true;
    }else{
        return false;
    }
}


