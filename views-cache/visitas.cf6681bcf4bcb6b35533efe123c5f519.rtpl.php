<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">
<section class="content-header">
 
</section>

<section class="content">

  <div class="row">
    <div class="col-md-12">
      <div class="box">
            
            <div class="box-header">
              <h3>Visitas Agendadas<small style="float: right;"><?php echo datePt(); ?></small></h3>
            </div>
            <div class="box-tools" style="margin-left: 8px;">
                <form action="/admin/visitas" method="get">
                  <div class="input-group col-md-4">
                    <input type="text" name="busca" class="form-control pull-right" placeholder="Buscar" value="<?php echo htmlspecialchars( $busca, ENT_COMPAT, 'UTF-8', FALSE ); ?>">
                    <div class="input-group-btn">
                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                    </div>
                  </div>
                </form>
              </div><br>

            <div class="box-body no-padding">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 20px &nbsp;">Cod</th>
                    <th>Nome</th>
                    <th>Tel</th>
                    <th>Data</th>
                    <th>Horário</th>
                    <th style="width: 120px">Situação</th>
                    <th style="width: 240px"></th>
                  </tr>
                </thead>
                <tbody>
                  <?php $counter1=-1;  if( isset($visitas) && ( is_array($visitas) || $visitas instanceof Traversable ) && sizeof($visitas) ) foreach( $visitas as $key1 => $value1 ){ $counter1++; ?>
                  <tr>
                    <td><?php echo htmlspecialchars( $value1["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["desmoradorvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["desnrtelvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo formatDtVisita($value1["dtvisita"]); ?></td/>
                    <td><?php echo htmlspecialchars( $value1["deshorario"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["desstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td>

                      <div style="float: right;">
                      <a href="/admin/visitas/<?php echo htmlspecialchars( $value1["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/detalhes" class="btn btn-default btn-xs"><i class="fa fa-search"></i> Detalhes</a>
                      <a href="/admin/visitas/<?php echo htmlspecialchars( $value1["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/status" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                      <a href="/admin/visitas/<?php echo htmlspecialchars( $value1["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete" onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Excluir</a>
                    </td>
                  </div>
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
