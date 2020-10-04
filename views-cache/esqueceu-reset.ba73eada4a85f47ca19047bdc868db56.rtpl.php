<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> SAPC | Recuperação de Senha</title>

  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

  <link rel="stylesheet" href="/resources/templates/bootstrap/css/bootstrap.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="/resources/templates/dist/css/formatacao.css">

  <link rel="icon
  " href="/resources/templates/img/sapc-logo.jpg"/>  

</head>
<body class="hold-transition lockscreen"  style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">

<div class="lockscreen-wrapper">
   <div class="login-box-body">
  <div class="lockscreen-logo">
    <b>SAPC</b>
  </div>
  
   <div class="help-block text-center"  style="color: #000;  font-size: 13pt;">
     Olá <b><?php echo htmlspecialchars( $name, ENT_COMPAT, 'UTF-8', FALSE ); ?></b>, digite uma nova senha:
    </div>


  <div class="lockscreen-item">


    <form  action="/esqueceu/reset" method="post">
      <input type="hidden" minlength="8" id="code" name="code" value="<?php echo htmlspecialchars( $code, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
      <div class="input-group">
        <input type="password" class="form-control" placeholder="Digite a nova senha" id="dessenha" name="dessenha">
        <div class="input-group-btn">
          <button type="submit" class="btn"><i class="fa fa-arrow-right text-muted"></i></button>
        </div>
      </div>
    </form>
  </div>

  
  <div class="lockscreen-footer text-center">
    SAPC - 2019<br><br> <u><b><a style="font-size: 14pt;" href="/login" class="text-black">Voltar</a></b></u>
  </div>
</div>
</div>

<script>
var formulario = document.querySelector('form');

formulario.onsubmit = function(){
  if(!document.querySelector("input[name='dessenha']").value){
      swal("Atenção!", "Digite a sua nova senha.", "info");
      return false;
  }
}
</script>

<script src="/resources/admin/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="/resources/admin/bootstrap/js/bootstrap.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</body>
</html>
