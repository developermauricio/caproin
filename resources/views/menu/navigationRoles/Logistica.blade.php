<li class="{{request()->is('ordenes-compra') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.purchase_order.purchase_orders') }}">
        <i data-feather='shopping-cart'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Ordenes de Pedido</span>
    </a>
</li>
