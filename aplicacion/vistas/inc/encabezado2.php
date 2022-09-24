<!DOCTYPE html>
<html lang="en">

    <head>
        <base href="<?php echo STASIS; ?>/">
        <meta charset="utf-8" />
        <title>Grupo Valcas</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
        <link href="assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/plugins/custom/prismjs/prismjs.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/base/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/header/menu/light.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/brand/dark.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/themes/layout/aside/dark.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/pages/wizard/wizard-1.css" rel="stylesheet" type="text/css" />
        <link href="css/global.css?<?php echo filemtime(ROOT_DIR . 'static/css/global.css'); ?>" rel="stylesheet" type="text/css" />
        <link href="css/app.css?<?php echo filemtime(ROOT_DIR . 'static/css/app.css'); ?>" rel="stylesheet" type="text/css" />
        <link rel="apple-touch-icon" sizes="180x180" href="img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="img/favicon-16x16.png">
        <link rel="manifest" href="site.webmanifest">
    </head>

    <body id="kt_body" class="header-fixed header-mobile-fixed aside-enabled aside-fixed page-loading">
        <div id="kt_header_mobile" class="header-mobile align-items-center header-mobile-fixed">
            <a href="./">
                <img alt="Logo" src="img/logo.png" height="18" />
            </a>
            <div class="d-flex align-items-center">
                <button class="btn p-0 burger-icon burger-icon-left" id="kt_aside_mobile_toggle">
                    <span></span>
                </button>
                <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
                    <span class="svg-icon svg-icon-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <polygon points="0 0 24 0 24 24 0 24" />
                                <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                            </g>
                        </svg>
                    </span>
                </button>
            </div>
        </div>

        <div class="d-flex flex-column flex-root">
            <div class="d-flex flex-row flex-column-fluid page">
                
                <div class="aside aside-left aside-fixed d-flex flex-column flex-row-auto" id="kt_aside">
                    <div class="text-center mt-2" id="kt_brand">
                        <a href="./" class="brand-logo">
                            <img alt="Logo" src="img/logo.png" height="54" />
                        </a>
                    </div>
                    <div class="aside-menu-wrapper flex-column-fluid" id="kt_aside_menu_wrapper">

                        <div id="kt_aside_menu" class="aside-menu" data-menu-vertical="1" data-menu-scroll="1" data-menu-dropdown-timeout="500">
                            <ul class="menu-nav">

                                <?php
                                // Administracion global, catalogos, usuarios y configuracion
                                if ($_SESSION['login_id'] == 1 || $_SESSION['login_id'] == 1225 || $_SESSION['login_id'] == 1227 || $_SESSION['login_id'] == 1301) {
                                ?>
                                    <li class="menu-section">
                                        <h4 class="menu-text">Administración</h4>
                                        <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                                    </li>
                                    <!-- Catalogos -->
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'catalogos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-folder"></i>
                                            <span class="menu-text">Catálogos</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'administrativo') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Administrativo</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'centros_trabajo') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/centros_trabajo" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Centros de Trabajo</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'departamentos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/departamentos" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Departamentos</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'puestos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/puestos" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Puestos</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'telemarketing') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Telemarketing</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">

                                                            <li class="menu-item <?php if ($menu3 == 'campanas') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/campanas" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Campañas</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'tipificacion') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/tipificacion" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Tipificación</span>
                                                                </a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'postventa') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Postventa</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            
                                                            <li class="menu-item <?php if ($menu3 == 'masterplan') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/masterplan" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Master Plan</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'inventario') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/inventario" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Inventario</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'contratos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/contratos" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Contratos</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item menu-item-submenu <?php if ($menu3 == 'propietarios') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                                    <i class="menu-bullet menu-bullet-line">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Propietarios</span>
                                                                    <i class="menu-arrow"></i>
                                                                </a>
                                                                <div class="menu-submenu">
                                                                    <i class="menu-arrow"></i>
                                                                    <ul class="menu-subnav">
                                                                        <li class="menu-item <?php if ($menu4 == 'irt') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                            <a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="menu-link">
                                                                                <i class="menu-bullet menu-bullet-dot">
                                                                                    <span></span>
                                                                                </i>
                                                                                <span class="menu-text">IRT</span>
                                                                                <span class="menu-label">
                                                                                    <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['irt']; ?></span>
                                                                                </span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="menu-item <?php if ($menu4 == 'rgr') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                            <a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="menu-link">
                                                                                <i class="menu-bullet menu-bullet-dot">
                                                                                    <span></span>
                                                                                </i>
                                                                                <span class="menu-text">RGR</span>
                                                                                <span class="menu-label">
                                                                                    <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['rgr']; ?></span>
                                                                                </span>
                                                                            </a>
                                                                        </li>
                                                                        <li class="menu-item <?php if ($menu4 == 'serena') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                            <a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="menu-link">
                                                                                <i class="menu-bullet menu-bullet-dot">
                                                                                    <span></span>
                                                                                </i>
                                                                                <span class="menu-text">La Serena</span>
                                                                                <span class="menu-label">
                                                                                    <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['serena']; ?></span>
                                                                                </span>
                                                                            </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </li>












                                                            <!-- <li class="menu-item <?php if ($menu3 == 'arrendatarios') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/arrendatarios" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Propietarios</span>
                                                                </a>
                                                            </li> -->
                                                            
                                                            <li class="menu-item <?php if ($menu3 == 'servicios') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/servicios" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Servicios</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'conceptos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/conceptos" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Conceptos</span>
                                                                </a>
                                                            </li>
                                                            
                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'compras') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Compras</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">

                                                            <li class="menu-item <?php if ($menu3 == 'tipos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/tipos" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Tipos de Gastos</span>
                                                                </a>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <!-- Usuarios -->
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'empleados') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-users"></i>
                                            <span class="menu-text">Usuarios</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">
                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'evaluadores') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Administradores</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'compras' && $menu2 == 'evaluadores') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/empleados/evaluadores" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Compras</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'solicitudes' && $menu2 == 'evaluadores') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/empleados/adsolicitudes" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Solicitudes</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'jefes') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Jefes Directos</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'compras' && $menu2 == 'jefes') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/empleados/jefes" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Compras</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'solicitudes' && $menu2 == 'jefes') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/empleados/jdsolicitudes" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Solicitudes</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                                <li class="menu-item <?php if ($menu2 == 'colaboradores') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                    <a href="<?php echo STASIS; ?>/empleados/colaboradores" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Colaboradores</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item <?php if ($menu2 == 'vendedores') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                    <a href="<?php echo STASIS; ?>/empleados/vendedores" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Vendedores</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item <?php if ($menu2 == 'ejecutivos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                    <a href="<?php echo STASIS; ?>/empleados/ejecutivos" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Ejecutivos</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </li>
                                    <li class="menu-item <?php if ($menu1 == 'configuracion') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="<?php echo STASIS; ?>/configuracion" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-cog"></i>
                                            <span class="menu-text">Configuración</span>
                                        </a>
                                    </li>
                                <?php
                                }
                                ?>

                                <li class="menu-section">
                                    <h4 class="menu-text">Departamentos</h4>
                                    <i class="menu-icon ki ki-bold-more-hor icon-md"></i>
                                </li>

                                <?php
                                if ($_SESSION['login_tipo'] == 4 || $_SESSION['login_tipo'] == 5 || $_SESSION['login_id'] == 1 || $_SESSION['login_id'] == 1225 || $_SESSION['login_id'] == 1227 || $_SESSION['login_id'] == 1301) {
                                ?>

                                    <?php
                                    // Administradores
                                    if ($_SESSION['login_id'] == 1 || $_SESSION['login_id'] == 1225 || $_SESSION['login_id'] == 1227 || $_SESSION['login_id'] == 1301) {
                                    ?>

                                        <!-- Mercadotecnia -->
                                        <li class="menu-item menu-item-submenu <?php if ($menu1 == 'mercadotecnia') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                            <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                <i class="menu-icon fa fa-bullhorn"></i>
                                                <span class="menu-text">Mercadotecnia</span>
                                                <i class="menu-arrow"></i>
                                            </a>
                                            <div class="menu-submenu">
                                                <i class="menu-arrow"></i>
                                                <ul class="menu-subnav">
                                                    <li class="menu-item menu-item-submenu <?php if ($menu2 == 'dashboard') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Dashboard</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu2 == 'prospectos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                            <i class="menu-bullet menu-bullet-line">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Prospectos</span>
                                                            <i class="menu-arrow"></i>
                                                        </a>
                                                        <div class="menu-submenu">
                                                            <i class="menu-arrow"></i>
                                                            <ul class="menu-subnav">
                                                                <li class="menu-item menu-item-submenu <?php if ($menu3 == 'nuevos') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="<?php echo STASIS; ?>/mercadotecnia/prospectos/nuevos" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Prospectos Nuevos</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item menu-item-submenu <?php if ($menu3 == 'historialPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="#" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Prospectos 2021</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item menu-item-submenu <?php if ($menu3 == 'historialPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="#" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Prospectos 2020</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item menu-item-submenu <?php if ($menu3 == 'historialPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="#" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Prospectos 2019</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item menu-item-submenu <?php if ($menu3 == 'historialPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="#" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Prospectos Archivados</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    
                                                </ul>
                                            </div>
                                        </li>

                                        <!-- Telemarketing -->
                                        <li class="menu-item menu-item-submenu <?php if ($menu1 == 'empleados') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                            <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                <i class="menu-icon fa fa-headphones"></i>
                                                <span class="menu-text">Telemarketing</span>
                                                <i class="menu-arrow"></i>
                                            </a>
                                            <div class="menu-submenu">
                                                <i class="menu-arrow"></i>
                                                <ul class="menu-subnav">
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Dashboard</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Gestión de Prospectos</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Pre-Manifiestos</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>

                                        <!-- Ventas -->
                                        <li class="menu-item menu-item-submenu <?php if ($menu1 == 'empleados') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                            <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                <i class="menu-icon fa fa-certificate"></i>
                                                <span class="menu-text">Ventas</span>
                                                <i class="menu-arrow"></i>
                                            </a>
                                            <div class="menu-submenu">
                                                <i class="menu-arrow"></i>
                                                <ul class="menu-subnav">
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Hoja de Registro</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Tablas de Amortización</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Ofertas de Compra</span>
                                                        </a>
                                                    </li>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="#" class="menu-link menu-toggle">
                                                            <i class="menu-bullet menu-bullet-dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Contratos</span>
                                                        </a>
                                                    </li>
                                                    <!-- Promesas de Venta -->
                                                    <?php
                                                    if ($_SESSION['login_tipo'] == 6 || $_SESSION['login_id'] == 1) {
                                                    ?>
                                                    <li class="menu-item menu-item-submenu <?php if ($menu2 == 'propietarios') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                            <i class="menu-bullet menu-bullet-line">
                                                                <span></span>
                                                            </i>
                                                            <span class="menu-text">Beneficios de Compra</span>
                                                            <i class="menu-arrow"></i>
                                                        </a>
                                                        <div class="menu-submenu">
                                                            <i class="menu-arrow"></i>
                                                            <ul class="menu-subnav">
                                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'nuevaPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="<?php echo STASIS; ?>/movimientos/ventas" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Generar</span>
                                                                    </a>
                                                                </li>
                                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'historialPromesa') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                                    <a href="<?php echo STASIS; ?>/movimientos/ventas/historial" class="menu-link menu-toggle">
                                                                        <i class="menu-bullet menu-bullet-dot">
                                                                            <span></span>
                                                                        </i>
                                                                        <span class="menu-text">Reporte</span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                    <?php
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </li>

                                    <?php
                                    }
                                    ?>

                                    <!-- Postventa -->
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'postventa') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-hands-helping"></i>
                                            <span class="menu-text">Postventa</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <?php
                                                // Si no son jefes directos
                                                if ($_SESSION['login_tipo'] != 5 && $_SESSION['login_id'] != 1) {
                                                ?>

                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'catalogos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Propietarios</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'irt') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">IRT</span>
                                                                    <span class="menu-label">
                                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['irt']; ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'rgr') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">RGR</span>
                                                                    <span class="menu-label">
                                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['rgr']; ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <?php
                                                }
                                                ?>

                                                <li class="menu-item <?php if ($menu3 == 'cobroplan') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                    <a href="<?php echo STASIS; ?>/catalogos/cobroplan" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Cobroplan</span>
                                                    </a>
                                                </li>

                                                <?php
                                                if ($_SESSION['login_id'] == 1 || $_SESSION['login_id'] == 1373 || $_SESSION['login_id'] == 1265 || $_SESSION['login_id'] == 1257 || $_SESSION['login_id'] == 1174 || $_SESSION['login_id'] == 1174 || $_SESSION['login_id'] == 1268 || $_SESSION['login_id'] == 1275 || $_SESSION['login_id'] == 1207) {
                                                ?>
                                                <li class="menu-item <?php if ($menu3 == 'reporteMovimientos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                    <a href="<?php echo STASIS; ?>/catalogos/cobroplan/reporte" class="menu-link">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Reporte de Movimientos</span>
                                                    </a>
                                                </li>
                                                <?php
                                                }
                                                ?>

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Solicitudes</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'cotizaciones') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Cotizaciones</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'generarCotizacion') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/generar" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Generar</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'reporteCotizaciones') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/reporte" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Reporte</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'qys') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/qys/reporte" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Quejas y Comentarios</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'evaluaciones') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/evaluaciones/reporte" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Evaluaciones</span>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>
                                    </li>

                                <?php
                                }
                                ?>

                                <!-- Postventa -->
                                <?php
                                if ($_SESSION['login_tipo'] == 2 && $_SESSION['login_id_departamento'] == 4 && $_SESSION['login_centro_trabajo'] == 'PROYECTOS Y OBRA') {
                                ?>
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'empleados') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-hands-helping"></i>
                                            <span class="menu-text">Postventa</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'solicitudes') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/solicitudes/reporte" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Solicitudes</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'cotizaciones') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Cotizaciones</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'generarCotizacion') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/generar" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Generar</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'reporteCotizaciones') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/cotizaciones/reporte" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Reporte</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </li>
                                <?php
                                }
                                ?>

                                <!-- Compras -->
                                <?php
                                if ($_SESSION['login_tipo'] != 6 && $_SESSION['login_tipo'] != 5 && $_SESSION['login_tipo'] != 4) {
                                ?>
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'empleados') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-store"></i>
                                            <span class="menu-text">Compras</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'nueva') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/compras/generar" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Generar Requisición</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'historial') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/movimientos/compras/historial" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Requisiciones</span>
                                                    </a>
                                                </li>
                                                
                                                <?php
                                                // Administrador
                                                if ($_SESSION['login_tipo'] == 3 || $_SESSION['login_id'] == 1) {
                                                ?>
                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'proveedores') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/catalogos/proveedores" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Proveedores</span>
                                                    </a>
                                                </li>
                                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'facturas') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="<?php echo STASIS; ?>/catalogos/facturas" class="menu-link menu-toggle">
                                                        <i class="menu-bullet menu-bullet-dot">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Facturas</span>
                                                    </a>
                                                </li>
                                                <?php
                                                }
                                                ?>

                                            </ul>
                                        </div>
                                    </li>
                                <?php
                                }
                                ?>

                                <!-- Procesos -->
                                <li class="menu-item menu-item-submenu <?php if ($menu1 == 'procesos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                        <i class="menu-icon fa fa-project-diagram"></i>
                                        <span class="menu-text">Procesos</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <i class="menu-arrow"></i>
                                        <ul class="menu-subnav">

                                            <?php
                                            if ($_SESSION['login_id'] == 1) {
                                            ?>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'dashboard') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/dashboard" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Dashboard</span>
                                                </a>
                                            </li>

                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'reporte') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/reporte" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Reporte Global</span>
                                                </a>
                                            </li>

                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'indicadores') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/indicadores" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Indicadores</span>
                                                </a>
                                            </li>
                                            <?php
                                            }
                                            ?>

                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'generar') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/generar" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Generar Interacción</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'asignadas') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/asignadas" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Asignadas</span>
                                                    <span class="menu-label">
                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['interacciones_asignadas']; ?></span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'generadas') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/movimientos/procesos/generadas" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Generadas</span>
                                                    <span class="menu-label">
                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['interacciones_generadas']; ?></span>
                                                    </span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li>

                                <?php
                                if ($_SESSION['login_id'] == 1 || $_SESSION['login_id'] == 1225 || $_SESSION['login_id'] == 1227 || $_SESSION['login_id'] == 1301 || $_SESSION['login_id'] == 1382 || $_SESSION['login_id'] == 1291 || $_SESSION['login_id'] == 1207 || $_SESSION['login_id'] == 1275 || $_SESSION['login_id'] == 1267 || $_SESSION['login_id'] == 1238) {
                                ?>
                                    <!-- Eventos y Amenidades -->
                                    <li class="menu-item menu-item-submenu <?php if ($menu1 == 'eventos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-calendar"></i>
                                            <span class="menu-text">Eventos y Amenidades</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'eventos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Eventos</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'agregarEvento') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/eventos/agregar" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Agregar</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'listadoEventos') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/eventos/listado" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Listado</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'amenidades') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Amenidades</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu3 == 'agregarAmenidad') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/amenidades/agregar" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Agregar</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu3 == 'listadoAmenidades') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/movimientos/amenidades/listado" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">Listado</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </li>
                                <?php } ?>






























                                <!-- <li class="menu-item menu-item-submenu <?php if ($menu1 == 'catalogos') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                        <a href="javascript:;" class="menu-link menu-toggle" style="">
                                            <i class="menu-icon fa fa-dollar-sign"></i>
                                            <span class="menu-text">Cobranza</span>
                                            <i class="menu-arrow"></i>
                                        </a>
                                        <div class="menu-submenu">
                                            <i class="menu-arrow"></i>
                                            <ul class="menu-subnav">

                                                <li class="menu-item menu-item-submenu <?php if ($menu2 == 'postventa') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                                        <i class="menu-bullet menu-bullet-line">
                                                            <span></span>
                                                        </i>
                                                        <span class="menu-text">Contratos</span>
                                                        <i class="menu-arrow"></i>
                                                    </a>
                                                    <div class="menu-submenu">
                                                        <i class="menu-arrow"></i>
                                                        <ul class="menu-subnav">
                                                            <li class="menu-item <?php if ($menu4 == 'irt') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosirt" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">IRT</span>
                                                                    <span class="menu-label">
                                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['irt']; ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu4 == 'rgr') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosrgr" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">RGR</span>
                                                                    <span class="menu-label">
                                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['rgr']; ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu4 == 'serena') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">La Serena</span>
                                                                    <span class="menu-label">
                                                                        <span class="label label-info label-rounded" style="width: 30px;"><?php echo $_SESSION['serena']; ?></span>
                                                                    </span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu4 == 'serena') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">AU</span>
                                                                </a>
                                                            </li>
                                                            <li class="menu-item <?php if ($menu4 == 'serena') echo 'menu-item-active'; ?>" aria-haspopup="true">
                                                                <a href="<?php echo STASIS; ?>/catalogos/propietariosserena" class="menu-link">
                                                                    <i class="menu-bullet menu-bullet-dot">
                                                                        <span></span>
                                                                    </i>
                                                                    <span class="menu-text">UMA</span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>

                                            </ul>
                                        </div>
                                    </li> -->























                                <!-- Constructora -->
                                <!-- <li class="menu-item menu-item-submenu <?php if ($menu1 == 'constructora') echo 'menu-item-active menu-item-open'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                    <a href="javascript:;" class="menu-link menu-toggle" style="">
                                        <i class="menu-icon fa fa-tools"></i>
                                        <span class="menu-text">Constructora</span>
                                        <i class="menu-arrow"></i>
                                    </a>
                                    <div class="menu-submenu">
                                        <i class="menu-arrow"></i>
                                        <ul class="menu-subnav">

                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'constructoraContratos') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/constructora/contratos" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Contratos</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'constructoraPresupuestos') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="<?php echo STASIS; ?>/constructora/presupuestos" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Presupuestos de Obras</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'programaObra') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="#" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Programas de Obras</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'programaObra') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="#" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Reportes de Obras</span>
                                                </a>
                                            </li>
                                            <li class="menu-item menu-item-submenu <?php if ($menu2 == 'programaObra') echo 'menu-item-active'; ?>" aria-haspopup="true" data-menu-toggle="hover">
                                                <a href="#" class="menu-link menu-toggle">
                                                    <i class="menu-bullet menu-bullet-dot">
                                                        <span></span>
                                                    </i>
                                                    <span class="menu-text">Compras</span>
                                                </a>
                                            </li>

                                        </ul>
                                    </div>
                                </li> -->
                            </ul>
                        </div>

                    </div>
                </div>

                <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                    <div id="kt_header" class="header header-fixed">
                        <div class="container-fluid d-flex align-items-stretch justify-content-between">
                            <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper" style="font-weight: bold; font-size: 20px; margin-top: 17px;">
                                <?php echo $titulo; ?>
                            </div>
                            <div class="topbar">
                                <div class="topbar-item">
                                    <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                                        <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">Hola,</span>
                                        <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?php echo $_SESSION['login_nombre']; ?></span>
                                        <span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
                                            <span class="symbol-label font-size-h5 font-weight-bold"><?php echo $_SESSION['login_nombre'][0]; ?></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
                        <div class="d-flex flex-column-fluid">
                            <div class="container-fluid">