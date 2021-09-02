

<li class="{{request()->is('purchase_orders') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.purchase_order.purchase_orders') }}">
        <i data-feather='shopping-cart'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Ordenes de Pedido</span>
    </a>
</li>

<li class="{{request()->is('facturas') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.invoice') }}"><i data-feather='clipboard'></i><span
            class="menu-title text-truncate" data-i18n="Email">Facturas</span></a>
</li>

<li class="{{request()->is('acuerdos-comerciales') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.trade.agreement') }}"><i data-feather='thumbs-up'></i><span
            class="menu-title text-truncate" data-i18n="Email">Acuerdos Comerciales</span></a>
</li>

<li class="{{request()->is('productos-servicios') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.products,services') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Productos y Servicios</span></a>
</li>

