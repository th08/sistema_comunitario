<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">

<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Agendar Visita</h3>
          </div>
         <?php if( $msgError != '' ){ ?>
        <div class="alert alert-danger alert-dismissible" style="margin:10px">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p><?php echo htmlspecialchars( $msgError, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
        </div>
        <?php } ?>
        <form role="form" id="visitas" action="/admin/visitas/<?php echo htmlspecialchars( $morador["idmorador"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/agendar" method="post">
          <div class="box-body">
            <div class="form-group col-md-12">
              <label for="desmoradorvisita">Morador</label>
             <input type="text" class="form-control" id="desmoradorvisita" name="desmoradorvisita" value="<?php echo htmlspecialchars( $morador["desmorador"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" readonly="readonly">
            </div>
            <div class="form-row col-md-6">
              <label for="desemailvisita">Email</label>
             <input type="text" class="form-control" id="desemailvisita" name="desemailvisita" value="<?php echo htmlspecialchars( $morador["desemail"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
            <div class="form-row col-md-6">
              <label for="desnrtelvisita">Telefone</label>
             <input type="text" class="form-control" id="desnrtelvisita" name="desnrtelvisita" value="<?php echo htmlspecialchars( $morador["nrtel"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" readonly="readonly">
            </div>
             <div class="form-row col-md-4">
              <label for="desendereco">Endereço:</label>
             <input type="text" class="form-control" id="desendereco" name="desendereco" value="<?php echo htmlspecialchars( $morador["desrua"], ENT_COMPAT, 'UTF-8', FALSE ); ?>,  Nº<?php echo htmlspecialchars( $morador["desnumero"], ENT_COMPAT, 'UTF-8', FALSE ); ?>,  <?php echo htmlspecialchars( $morador["desbairro"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" readonly="readonly">
            </div>
             <div class="form-row col-md-4">
              <label for="descomplementovisita">Complemento</label>
             <input type="text" class="form-control" id="descomplementovisita" name="descomplementovisita" value="<?php echo htmlspecialchars( $morador["descomplemento"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
            </div>
             <div class="form-row col-md-4">
              <label for="descidadevisita">Cidade</label>
             <input type="text" class="form-control" id="descidadevisita" name="descidadevisita" value="<?php echo htmlspecialchars( $morador["descidade"], ENT_COMPAT, 'UTF-8', FALSE ); ?> - <?php echo htmlspecialchars( $morador["desestado"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" readonly="readonly">
            </div>
            <div class="form-row col-md-4">
              <br><br>
              <label for="desdoacao">Selecione o tipo de doação<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <select type="text" class="form-control" name="desdoacao" placeholder="Produto">
                <option style="background: #ccc;"><?php echo htmlspecialchars( $registrarValoresVisitas["desdoacao"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                 <?php $counter1=-1;  if( isset($produtos) && ( is_array($produtos) || $produtos instanceof Traversable ) && sizeof($produtos) ) foreach( $produtos as $key1 => $value1 ){ $counter1++; ?>
                <option><?php echo htmlspecialchars( $value1["desproduto"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-row col-md-4">
              <br><br>
              <label for="dtvisita">Data de Agendamento<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <input type="text" id="data" placeholder="dd/mm/AAAA" class="form-control" name="dtvisita" autocomplete="off" value="<?php echo htmlspecialchars( $registrarValoresVisitas["dtvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>">
          </div>
          <div class="form-row col-md-4">
            <br><br>
              <label for="data">Horario<span style="color:#E9967A; font-size: 12pt;">&nbsp&nbsp*</span></label>
              <select type="text" class="form-control" name="deshorario" placeholder="Hora">
                <option style="background: #ccc;"><?php echo htmlspecialchars( $registrarValoresVisitas["deshorario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                <option>08:00</option>
                <option>08:30</option>
                <option>09:00</option>
                <option>09:30</option>
                <option>10:00</option>
                <option>10:30</option>
                <option>11:00</option>
                <option>11:30</option>
                <option>12:00</option>
                <option>12:30</option>
                <option>13:00</option>
                <option>13:30</option>
                <option>14:00</option>
                <option>14:30</option>
                <option>15:00</option>
                <option>15:30</option>
                <option>16:00</option>
                <option>16:30</option>
                <option>17:00</option>
                <option>17:30</option>
                <option>18:00</option>
              </select>
          </div><br><br>
           <div class="form-inline col-md-12">  
           <br><br><br><br><br>
          <div class="box-footer">
            <a style="color: #000;" href="/admin/visitas"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a>
            <button type="submit" onclick="return confirm('Deseja realmente agendar essa visita? ')" id="enviar" class="btn btn-success" style="float: right; width: 120px; height: 50px;">Agendar</button>
          </div>
           </div>
        </form>
      </div>
  	</div>
  </div>
</div>

</section>
</div>
