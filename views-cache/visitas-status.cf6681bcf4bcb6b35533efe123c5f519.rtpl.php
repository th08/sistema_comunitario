<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">

    <section class="content-header">

    </section>

    <section class="content">
        
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Visita N°<?php echo htmlspecialchars( $visita["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h3>
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

            <form role="form" action="/admin/visitas/<?php echo htmlspecialchars( $visita["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/status" method="post">
                <div class="box-body">
                    <div class="form-group">
                        <label for="idstatus">Status da Visita</label>
                        <select class="form-control" name="idstatus">
                            <?php $counter1=-1;  if( isset($statusvisita) && ( is_array($statusvisita) || $statusvisita instanceof Traversable ) && sizeof($statusvisita) ) foreach( $statusvisita as $key1 => $value1 ){ $counter1++; ?>
                            <option <?php if( $value1["idstatus"] === $visita["idstatus"] ){ ?>selected="selected"<?php } ?> value="<?php echo htmlspecialchars( $value1["idstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?>"><?php echo htmlspecialchars( $value1["desstatus"], ENT_COMPAT, 'UTF-8', FALSE ); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>

                <div class="box-footer">
                	 <a style="color: #000;" href="/admin/visitas"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a>
                     <button type="submit" class="btn btn-success" onclick="return confirm('Deseja realmente mudar o status da visita? ')" style="float: right; width: 120px; height: 50px;">Atualizar</button>
                </div>
            </form>
            </div>
            </div>
        </div>
    
    </section>


    <div class="clearfix"></div>

</div>
