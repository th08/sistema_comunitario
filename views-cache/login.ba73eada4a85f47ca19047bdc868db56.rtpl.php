<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SAPC | Login</title>
  
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  
  <link rel="stylesheet" href="/resources/templates/bootstrap/css/bootstrap.min.css">
  
  <link rel="icon
  " href="/resources/templates/img/sapc-logo.jpg"/>  
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

  <link rel="stylesheet" href="/resources/templates/dist/css/formatacao.css">
  <!--Google reCaptcha-->
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>
<body class="hold-transition login-page" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">
<div class="login-box">
  <div class="login-logo">
    <b><span style="color: #fff;">SAPC</span></b>
  </div>
 
  <?php if( $error != '' ){ ?>
    <div class="alert alert-danger">
        <center><?php echo htmlspecialchars( $error, ENT_COMPAT, 'UTF-8', FALSE ); ?></center>
    </div>
  <?php } ?>
  <div class="login-box-body">
    <b><p class="login-box-msg">Entre para iniciar sua sessão</p></b>

    <form action="../login" method="post" name="form">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Login" name="login" autocomplete="off" value="<?php echo htmlspecialchars( $registrarValoresLogin["login"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div><br/>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Senha" name="password" value="<?php echo htmlspecialchars( $registrarValoresLogin["password"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <center><span><h3>Verificação</h3></span>
            <div class="g-recaptcha" name="reCaptcha" data-sitekey="6LcLYb0UAAAAAOjmfh4w6qtIzqJG1tMsWau_Stle"></div>
      </center><br/> 
      <div class="row">
        <div class="col-xs-4" style="float: right;">
          <button type="submit" id="btn" class="btn btn-primary btn-block btn-flat">Entrar</button>
        </div>
        
      </div>
    </form>

    <a href="/esqueceu"><u>Esqueci minha senha</u></a><br>

  </div>
</div>

<!-- jQuery 2.2.3 -->
<script src="/resources/templates/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/resources/templates/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>


