<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">

<section class="content-header">
</section>


<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box">
            
            <div class="box-header">
              <h3>Nova Visita<small style="float: right;"><?php echo datePt(); ?></small></h3>
            </div>
            <center><div class="box-tools" style="margin-left: 8px;">
                <form action="/admin/visitas/agendar" method="get">
                  <div class="input-group col-md-6">
                    <input type="text" name="busca" class="form-control pull-right" placeholder="Digite o nome ou cpf" autocomplete="off" value="<?php echo htmlspecialchars( $busca, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div></center><br>
            <div class="box-body no-padding">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Telefone</th>
                    <th style="width: 280px">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $counter1=-1;  if( isset($moradores) && ( is_array($moradores) || $moradores instanceof Traversable ) && sizeof($moradores) ) foreach( $moradores as $key1 => $value1 ){ $counter1++; ?>
                  <tr>
                    <td><?php echo htmlspecialchars( $value1["desmorador"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["descpf"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["nrtel"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td/>
                    <td>
                      <div style="float: right;">
                      <a href="/admin/visitas/<?php echo htmlspecialchars( $value1["idmorador"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/agendar" class="btn btn-success btn-xs"><i class="fas fa-plus"></i> Nova Visita</a>
                    </div>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
              <div class="box-footer clearfix"  style="background: #72afd2;">
              <ul class="pagination pagination-sm no-margin pull-right">
                <?php $counter1=-1;  if( isset($paginas) && ( is_array($paginas) || $paginas instanceof Traversable ) && sizeof($paginas) ) foreach( $paginas as $key1 => $value1 ){ $counter1++; ?>
                <li><a href="<?php echo htmlspecialchars( $value1["href"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["texto"], ENT_COMPAT, 'UTF-8', FALSE ); ?></a></li>
                <?php } ?>
              </ul>
            </div>
            </div>
          </div>
  	</div>
  </div>

</section>

</div>