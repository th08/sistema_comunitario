<?php if(!class_exists('Rain\Tpl')){exit;}?><!DOCTYPE html>

<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SAPC | Admin</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="/resources/templates/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="/resources/templates/dist/css/formatacao.css">
  <link rel="stylesheet" href="/resources/templates/dist/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"> 
  <link rel="icon
  " href="/resources/templates/img/sapc-logo.jpg"/> 
</head>


<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- cabeçalho principal -->
  <header class="main-header">

    <!-- logo -->
    <a href="/admin" class="logo">
      <span class="logo-mini">SAPC</span>
      <span class="logo-lg"><b>SAPC / Admin</b></span>
    </a>

    <!-- navegacao cabecalho -->
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- botao ocultar esquerdo-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="tittleMenu">
          <span class="logo-lg"><b>Sistema de Auxílio a Projetos Comunitários</b></span>
      </div>
    
      <!-- lado direito menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- usuario conta -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- icone do usuario-->
              <i class="glyphicon glyphicon-user"></i>
              <!-- esconder depois em dispositivos pequenos o nome do usuario -->
              <span class="hidden-xs"><?php echo getUserName(); ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- icone de usuario no menu -->
              <li class="user-header">
                <img src="/resources/templates/dist/img/user.png" class="img-circle" alt="User Image">
               <p><?php echo getUserName(); ?></p>
               <?php if( checkLogin(false) ){ ?>
               <small>Administrador</small>
               <?php }else{ ?>
               <small>Membro</small>
               <?php } ?>
              </li>
              <!-- footer do menu -->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/admin/editar" class="fas fa-user-edit btn btn-info btn-flat">Perfil</a>
                </div>
                <div class="pull-right">
                  <a href="/logout" class="fas fa-window-close btn btn-danger btn-flat">Sair</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- lado esquerdo, contem o menu lateral -->
  <aside class="main-sidebar">

    <section class="sidebar">

      <div class="user-panel">
        <div class="pull-left info">
          <!--Nome do usuario-->
          <!--Colocar um text-lenght no input de login-->
          <center><p style="font-size: 18pt;"><?php echo getUserName(); ?></p></center>
        </div>
      </div><br/><br/><br/><br/>

      <!-- menu lateral esquerdo -->
      <ul class="sidebar-menu">
        <li class="header"><b>MENU</b></li>
        <!--menus -->
        <li class="treeview"><a href="/admin"><i class="fa fa-home"></i> <span>Home</span></a></li>
         
         <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Moradores</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/moradores/cadastrar"><i class="fa fa-user-plus"></i><span>Cadastrar</span></a></li>
            <li><a href="/admin/moradores"><i class="fa fa-search"></i><span>Consultar</span></a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href=""><i class="fa fa-envelope"></i> <span>Visitas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/visitas/agendar"><i class="fas fa-calendar-plus"></i></i><span>Nova visita</span></a></li>
            <li><a href="/admin/visitas"><i class="fa fa-search"></i><span>Consultar</span></a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fa fa-user"></i> <span>Usuários</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/usuarios/cadastrar"><i class="fas fa-user-plus"></i></i><span>Novo</span></a></li>
            <li><a href="/admin/usuarios"><i class="fa fa-search"></i><span>Consultar</span></a></li>
          </ul>
        </li>

        <li class="treeview">
          <a href="#"><i class="fas fa-luggage-cart"></i> <span>Estoque</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/produtos/cadastrar"><i class="fas fa-cart-plus"></i><span>Novo Produto</span></a></li>
            <li><a href="/admin/produtos"><i class="fas fa-search"></i><span>Consultar</span></a></li>
            <li><a href="/admin/categorias"><i class="fa fa-list"></i><span>Categorias</span></a></li>
          </ul>
        </li>

         <li class="treeview">
          <a href="#"><i class="fa fa-cog"></i> <span>Configurações</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/editar"><i class="fas fa-user-edit"></i><span>Editar Perfil</span></a></li>
            <li><a href="/admin/editar/senha"><i class="fas fa-lock"></i><span>Alterar Senha</span>
            <li><a href="/logout"><i class="fas fa-window-close"></i><span>Sair</a></li>
          </ul>
        </li>

      </ul>
      <!-- /barra lateral esquerda -->
    </section>
    <!-- /lado esquerdo -->
  </aside>