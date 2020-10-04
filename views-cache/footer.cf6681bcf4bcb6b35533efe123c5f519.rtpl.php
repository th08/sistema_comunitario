<?php if(!class_exists('Rain\Tpl')){exit;}?> <!-- rodape principal -->
  <footer class="main-footer">
    <!-- Direita -->
    <div class="pull-right hidden-xs">
      Desenvolvido por <b>Thiago Miranda</b>
    </div>
    <!-- Esquerda -->
    <strong>Sistema de Auxílio a Projetos Comunitários 2019.</strong>
  </footer>

  <!-- Aside -->
  <aside class="control-sidebar control-sidebar-dark">

  </aside>
  <!-- /.Fim do aside -->
  <div class="control-sidebar-bg"></div>
</div>

<script>
var formulario = document.querySelector('form');

formulario.onsubmit = function(){
   if(!document.querySelector("input[name='desmorador']").value){
      swal("Atenção!", "Campo nome vazio.", "warning");
      return false;
  }
   if(!document.querySelector("input[name='desidentidade']").value){
      swal("Atenção!", "Campo identidade vazio.", "warning");
      return false;
  }
  if(!document.querySelector("input[name='descpf']").value){
      swal("Atenção!", "Campo CPF vazio.", "warning");
      return false;
  }
  if(!document.querySelector("input[name='nrtel']").value){
      swal("Atenção!", "Campo telefone vazio.", "warning");
      return false;
  }
  if(!document.querySelector("input[name='descep']").value){
      swal("Atenção!", "Campo CEP vazio.", "warning");
      return false;
  }
}
</script>



<script src="/resources/templates/plugins/jQuery/jquery-2.2.3.min.js"></script>

<script src="/resources/templates/bootstrap/js/bootstrap.min.js"></script>

<script src="/resources/templates/dist/js/app.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.pt-BR.min.js"></script>

<script src="https://rawgit.com/RobinHerbots/Inputmask/3.x/dist/jquery.inputmask.bundle.js"></script>


<script>
  $("input[id*='descpf']").inputmask({
   mask: ['999.999.999-99']
  });
  $("input[id*='desidentidade']").inputmask({
      mask: ['99.999.999']
  });
   $("input[id*='nrtel']").inputmask({
      mask: ['(99) 99999-9999']
  });

  $(function () {

    $('#data').datepicker({
      autoclose: true,
      orientation: 'bottom',
      language: 'pt-BR',
      startDate: '+0'
    });
  });
</script>

<script>
var visitas = document.getElementById("visitas");
var enviar = $("#enviar");

$(visitas).submit(function(event){
  if (visitas.checkValidity()) {
    enviar.attr('disabled', 'disabled');
  }
});
</script>
</script>
</body>
</html>
