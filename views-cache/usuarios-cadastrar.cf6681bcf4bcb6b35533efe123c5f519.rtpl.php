<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper">

<section class="content" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">

<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Novo Usuário</h3>
        </div>

        <?php if( $msgError != '' ){ ?>
        <div class="alert alert-danger alert-dismissible" style="margin:10px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><?php echo htmlspecialchars( $msgError, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
        </div>
        <?php } ?>
        <form role="form" action="/admin/usuarios/cadastrar" method="post">
          <div class="box-body">
            <div class="form-group">
              <label for="despessoa">Nome<span style="color:#E9967A; font-size: 15pt;">&nbsp&nbsp*</span></label>
              <input type="text" class="form-control" id="despessoa" name="despessoa" placeholder="Digite o nome" value="<?php echo htmlspecialchars( $registrarValoresUsuario["despessoa"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" minlength="8" maxlength="16">
            </div>
            <div class="form-group">
              <label for="deslogin">Login<span style="color:#E9967A; font-size: 15pt;">&nbsp&nbsp*</span></label>
              <input type="text" class="form-control" id="deslogin" name="deslogin" placeholder="Digite o login" value="<?php echo htmlspecialchars( $registrarValoresUsuario["deslogin"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" minlength="2" maxlength="15">
            </div>
            <div class="form-group">
              <label for="nrtel">Telefone</label>
              <input type="tel" class="form-control" id="nrtel" name="nrtel" placeholder="Digite o telefone" value="<?php echo htmlspecialchars( $registrarValoresUsuario["nrtel"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" maxlength="15">
            </div>
            <div class="form-group">
              <label for="desemail">E-mail<span style="color:#E9967A; font-size: 15pt;">&nbsp&nbsp*</span></label>
              <input type="email" class="form-control" id="desemail" name="desemail" placeholder="Digite o e-mail" value="<?php echo htmlspecialchars( $registrarValoresUsuario["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" maxlength="64">
            </div>
            <div class="form-group">
              <label for="dessenha">Senha<span style="color:#E9967A; font-size: 15pt;">&nbsp&nbsp*</span></label>
              <input type="password" class="form-control" id="dessenha" name="dessenha" placeholder="Digite a senha" value="<?php echo htmlspecialchars( $registrarValoresUsuario["dessenha"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" autocomplete="off" minlength="8">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="inadmin" value="1"> Acesso de Administrador
              </label>
            </div>
          </div>

          <div class="box-footer">
             <a style="color: #000;" href="/admin/usuarios"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a>
            <button type="submit" onclick="return confirm('Deseja realmente cadastrar esse usuário? ')" class="btn btn-success" style="float: right; width: 120px; height: 50px;">Cadastrar</button>
          </div>
        </form>
      </div>
  	</div>
  </div>
  </section>
</section>
</div>
