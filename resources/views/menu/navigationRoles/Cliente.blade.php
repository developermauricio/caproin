<li class="{{request()->is('servicios') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('cliente.services') }}">
        <i data-feather='box'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Servicios</span>
    </a>
</li>
<li class="{{request()->is('facturas') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center"
      href="{{ route('admin.invoice') }}">
        <i data-feather='clipboard'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Facturas</span>
    </a>
</li>
<li class="{{request()->is('ordenes-compra') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.purchase_order.purchase_orders') }}">
        <i data-feather='shopping-cart'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Ordenes de Compra</span>
    </a>
</li>
<li class="{{request()->is('acuerdos-comerciales') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center"
      href="{{ route('admin.trade.agreement') }}">
        <i data-feather='thumbs-up'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Acuerdos Comerciales</span></a>
</li>
