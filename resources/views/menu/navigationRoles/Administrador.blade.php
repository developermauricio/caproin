<li class="{{request()->is('customers') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.customer.customers') }}">
        <i data-feather="users"></i>
        <span class="menu-title text-truncate" data-i18n="Email">Clientes</span>
    </a>
</li>

<li class="{{request()->is('ordenes-compra') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.purchase_order.purchase_orders') }}">
        <i data-feather='shopping-cart'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Ordenes de Pedido</span>
    </a>
</li>
<li class="{{request()->is('providers') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.provider.providers') }}"><i data-feather='thumbs-up'></i><span
          class="menu-title text-truncate" data-i18n="Email">Proveedores</span></a>
</li>
<li class="{{request()->is('conveyors') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
    href="{{ route('admin.conveyor.conveyors') }}"><i data-feather='truck'></i><span
        class="menu-title text-truncate" data-i18n="Conveyors">Transportadores</span></a>
</li>

<li class="{{request()->is('sucursales') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.branch_offices') }}"><i data-feather='map'></i><span
          class="menu-title text-truncate" data-i18n="Email">Sucursales</span></a>
</li>
<li class="{{request()->is('zonas') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
                                                                    href="{{ route('admin.zones') }}"><i data-feather='map-pin'></i><span
            class="menu-title text-truncate" data-i18n="Email">Zonas</span></a>
</li>
<li class="{{request()->is('usuarios') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.user.users') }}"><i data-feather='users'></i><span
          class="menu-title text-truncate" data-i18n="Email">Usuarios</span></a>
</li>
<li class="{{request()->is('facturas') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.invoice') }}"><i data-feather='clipboard'></i><span
          class="menu-title text-truncate" data-i18n="Email">Facturas</span></a>
</li>

<li class="{{request()->is('acuerdos-comerciales') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.trade.agreement') }}"><i data-feather='thumbs-up'></i><span
          class="menu-title text-truncate" data-i18n="Email">Acuerdos Comerciales</span></a>
</li>

<li class="{{request()->is('productos-servicios') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.products,services') }}"><i data-feather='box'></i><span
          class="menu-title text-truncate" data-i18n="Email">Productos y Servicios</span></a>
</li>
<li class="{{request()->is('reportes-cartera') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.report.cartera') }}"><i data-feather='activity'></i><span
          class="menu-title text-truncate">Reportes cartera</span></a>
</li>
<li class="{{request()->is('reportes-logistica') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center"
      href="{{ route('admin.report.logistic') }}"><i data-feather='truck'></i><span
          class="menu-title text-truncate">Reportes logistica</span></a>
</li>
