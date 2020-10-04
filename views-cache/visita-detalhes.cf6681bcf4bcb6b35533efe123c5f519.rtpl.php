<?php if(!class_exists('Rain\Tpl')){exit;}?>
<div class="content-wrapper" style="background-image: url('/resources/templates/dist/img/comunidade.jpg');">
    <section class="content-header">
    </section>

    <section class="invoice">
        <div class="row">
            <div class="col-xs-12">
            <h2 class="page-header">
                <a style="color: #000;" href="/admin/visitas"><button type="button" class="btn btn-secondary" style="float: left; width: 120px; height: 50px;">Voltar</button></a><br>
                <h1>Visita N°<?php echo htmlspecialchars( $visita["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></h1>
                <center><h1>Comprovante de Doação</h1>
                <img src="/resources/templates/img/sapc-logo.jpg" alt="Logo">
                <small class="pull-right">Data: <?php echo date('d/m/Y'); ?></small> 
            </h2>
            </div>
        </div>
        <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
            De
            <address>
                <strong>SAPC</strong><br>
                Sistema de Auxílio a Projetos Comunitários<br>
                Juiz de Fora - MG<br><br>
                <small>Versão 0.1</small>
            </address>
            </div>
            <div class="col-sm-4 invoice-col">
            Para
            <address>
                <strong><?php echo htmlspecialchars( $visita["desmoradorvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></strong><br>
                <?php echo htmlspecialchars( $visita["desendereco"], ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $visita["descomplementovisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>, <?php echo htmlspecialchars( $visita["descidadevisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?><br>
                <br>
                <?php if( $visita["desnrtelvisita"] && $visita["desnrtelvisita"]!='0' ){ ?>Telefone: <?php echo htmlspecialchars( $visita["desnrtelvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?><br><?php } ?>
                <?php if( $visita["desemailvisita"] && $visita["desemailvisita"]!='0' ){ ?>
                E-mail: <?php echo htmlspecialchars( $visita["desemailvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?><?php } ?>
            </address>
            </div>
            <div class="col-sm-4 invoice-col">
            <b>Visita #<?php echo htmlspecialchars( $visita["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></b><br>
            <br>
            <b>Emitido em:</b> <?php echo formatDate($visita["dtregistro"]); ?><br>
            </div>
        </div><br><br>

        <center><div class="row">
            <p>O <b>SAPC</b> declara que foi realizada a visita a(o) Srº(ª) <b><?php echo htmlspecialchars( $visita["desmoradorvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?></b> em <b> <?php echo formatDtVisita($visita["dtvisita"]); ?></b> na  <b><?php echo htmlspecialchars( $visita["desendereco"], ENT_COMPAT, 'UTF-8', FALSE ); ?></b> onde foi entregue <b><?php echo htmlspecialchars( $visita["desdoacao"], ENT_COMPAT, 'UTF-8', FALSE ); ?></b>.</p><br>
                <b>____________________________________________________</b><br/>
                <small>Assinatura do Morador</small>
            </b><br/>
       </div></center>
        <div class="row no-print">
            <div class="col-xs-12">
                <button type="button" onclick="window.location.href = '/admin/visitas/<?php echo htmlspecialchars( $visita["idvisita"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/status'" class="btn btn-default pull-left" style="margin-left: 5px;">
                    <i class="fa fa-pencil"></i> Editar Status
                </button>
                
                <button type="button" onclick="window.print()" class="btn btn-primary pull-right" style="margin-right: 5px;">
                    <i class="fa fa-print"></i> Imprimir
                </button>
            </div>
        </div>
    </section>

    <div class="clearfix"></div>

</div>
