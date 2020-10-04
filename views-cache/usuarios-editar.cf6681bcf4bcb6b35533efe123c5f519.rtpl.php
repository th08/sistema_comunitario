<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">
<section class="content-header">
  
<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Editar Perfil</h3>
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
        <form role="form" action="/admin/editar" method="post">
          <div class="box-body">
            <div class="form-row col-md-6">
              <label for="despessoa">Nome<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <input type="text" class="form-control" id="despessoa" name="despessoa" placeholder="Digite o nome" value="<?php echo htmlspecialchars( $usuario["despessoa"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" minlength="8" maxlength="16">
            </div>
            <div class="form-row col-md-6">
              <label for="deslogin">Login<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <input type="text" class="form-control" id="deslogin" name="deslogin" placeholder="Digite o login"  value="<?php echo htmlspecialchars( $usuario["deslogin"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" minlength="2" maxlength="15">
            </div>
            <div class="form-row col-md-12">
              <label for="nrtel">Telefone</label>
              <input type="tel" class="form-control" id="nrtel" name="nrtel" placeholder="Digite o telefone"  value="<?php echo htmlspecialchars( $usuario["nrtel"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" maxlength="15">
            </div>
            <div class="form-row col-md-12">
              <label for="desemail">E-mail<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <input type="email" class="form-control" id="desemail" name="desemail" placeholder="Digite o e-mail" value="<?php echo htmlspecialchars( $usuario["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" maxlength="64">
            </div>

          <div class="form-inline col-md-12">    
          <div class="box-footer">
            <a style="color: #000;" href="/admin/moradores"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a>
            <button type="submit" onclick="return confirm('Deseja realmente editar seus dados?')" class="btn btn-success" style="float: right; width: 120px; height: 50px;">Editar</button>
          </div>
           </div>
        </form>
      </div>
    </div>
  </div>

</section>
</div>
