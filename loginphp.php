<?php
  require_once('controladores/funcionesphp.php');
  require_once('helpers.php');
  if($_POST){
    $errores = validarLogin($_POST);
    if(count($errores)==0){
      $usuario = buscarPorEmail($_POST['email']);
      if($usuario==null){
        $errores['email']="Usuario no encontrado...";
      }else{
        //Desde aquí incio mi revisión a ver que ocurre con los contenidos de las variables y tratar de ver que ocurre
        //Aquí ví que el dato viene bien, es decir el password hasheado
        //dd($usuario['password']);
        //Ahora veo que trae esta variable  y noto que trae el dato correctamente
        //dd($_POST['password']);
        //Aquí estaba el error a la función password_verify, se le debe psar primero el dato no hasheado y luego el hasheado, ese fue mi error, lo habia pasado al contrario 
        if(password_verify($_POST['password'],$usuario['password'])===false){
          $errores['password']="Datos inválidos...";
        }else{
          seteoUsuario($usuario,$_POST);
          if(validarUsuario()){
            header('location:perfil.php');
            exit;
          }else{
            header('location:loginphp.php');
            exit;
          }
        }
      }
    }
  }

?>

<html>
  <head>
    <title>Formulario de Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
    <link rel="stylesheet" href="css/master.css">
    <link rel="stylesheet" href="css/index.css">
    
    <meta charset="utf-8">
  </head>
  <body>
    <div class="container-fluid containerLogin">
      <section class="formularioLogin">
        <div id="formContainer" class="row align-items-center">
          <div class="col-8 offset-2  ">
            <h1>Iniciar Sesión</h1>
            <?php if(isset($errores)):?>
              <ul class="alert alert-danger">
                <?php foreach ($errores as $value) :?>
                    <li><?=$value;?></li>
                <?php endforeach;?>
              </ul>
            <?php endif;?> 
            <form id="formulario"  class="form" name="formLogin"     novalidate action="loginphp.php"  method="POST" enctype="multipart/form-data" >
                      
              <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input required name="email" type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Ingrese su correo" value= "<?=isset($errores['email'])? $usuario['email'] : ""  ;?>"
              </div>
          
              <div class="form-group">
                <label for="password">Contraseña</label>
                <input required name="password" type="password" value= "" class="form-control" id="password" placeholder="Contraseña">
              
                <small class="form-text text-muted">Al menos 6 caracteres, debe contenter sólo números</small>
              </div>
            
              <div class="form-group">
                <input  class="text-left" name="recordarme" type="checkbox" value= "recordarme" class="form-control " id="recordarme" >
                <label for="recordarme">Recordarme</label>
              </div>          
           
              <button type="submit" class="btn btn-primary">Ingresar</button>
              <a href="register.php" class="btn btn-link">Aun no poseo una cuenta</a>
              <a href="olvidePassword.php" class="btn btn-link">¿Olvidaste tu contraseña?</a>
            </form>
        </div> 
      </section>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
  </body>
</html>
