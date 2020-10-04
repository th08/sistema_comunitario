<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">
<section class="content-header">

<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Editar Senha</h3>
        </div>

        <?php if( $msgError != '' ){ ?>
        <div class="alert alert-danger alert-dismissible" style="margin:10px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><?php echo htmlspecialchars( $msgError, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
        </div>
        <?php } ?>
        <?php if( $msgSuccess != '' ){ ?>
        <div class="alert alert-success alert-dismissible" style="margin:10px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><?php echo htmlspecialchars( $msgSuccess, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
        </div>
        <?php } ?>

        <form role="form" action="/admin/editar/senha" method="post">
          <div class="box-body">
            <div class="form-row col-md-6">
              <label for="dessenha">Nova Senha</label>
              <input type="password" class="form-control" id="dessenha" name="dessenha" placeholder="Nova senha" minlength="8">
            </div>
            <div class="form-row col-md-6">
              <label for="dessenha-confirmar">Confirme a senha</label>
              <input type="password" class="form-control" id="dessenha-confirmar" name="dessenha-confirmar" placeholder="Confirme a nova senha" minlength="8">
            </div>
          </div>

          <div class="box-footer">
             <a style="color: #000;" href="/admin"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a>
            <button type="submit" onclick="return confirm('Deseja realmente atualizar a sua senha? ')" class="btn btn-success" style="float: right; width: 120px; height: 50px;">Atualizar</button>
          </div>
        </form>
      </div>
  	</div>
  </div>

</section>

</div>