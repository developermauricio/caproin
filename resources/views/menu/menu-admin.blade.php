<!--=====================================
MENU ADMIN
======================================-->
@php
    $role1 = array("Vendedor", "Administrador");
    $role2 = array("Administrador");
@endphp

<li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Navegación</span><i
        data-feather="more-horizontal"></i>
</li>

@role($role2)
<li class="{{request()->is('customers') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.customer.customers') }}"><i data-feather="users"></i><span
            class="menu-title text-truncate" data-i18n="Email">Clientes</span></a>
</li>

<li class="{{request()->is('purchase_orders') ? 'active' : '' }} nav-item">
    <a class="d-flex align-items-center" href="{{ route('admin.purchase_order.purchase_orders') }}">
        <i data-feather='box'></i>
        <span class="menu-title text-truncate" data-i18n="Email">Ordenes de compra</span>
    </a>
@endrole
@role($role2)
<li class="{{request()->is('providers') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.provider.providers') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Proveedores</span></a>
</li>
@endrole
@role($role2)
<li class="{{request()->is('sucursales') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.branch_offices') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Sucursales</span></a>
</li>
@endrole
@role($role2)
<li class="{{request()->is('usuarios') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.user.users') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Usuarios</span></a>
</li>
@endrole
@role($role2)
<li class="{{request()->is('zonas') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.zones') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Zonas</span></a>
</li>
@endrole
@role($role1)
<li class="{{request()->is('facturas') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.invoice') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Facturas</span></a>
</li>
@endrole
@role($role1)
<li class="{{request()->is('productos-servicios') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.products,services') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Productos y Servicios</span></a>
</li>
@endrole
{{--<li class=" nav-item"><a class="d-flex align-items-center" href="app-chat.html"><i--}}
{{--            data-feather="message-square"></i><span class="menu-title text-truncate" data-i18n="Chat">Chat</span></a>--}}
{{--</li>--}}
{{--<li class=" nav-item"><a class="d-flex align-items-center" href="app-todo.html"><i data-feather="check-square"></i><span--}}
{{--            class="menu-title text-truncate" data-i18n="Todo">Todo</span></a>--}}
{{--</li>--}}
{{--<li class=" nav-item"><a class="d-flex align-items-center" href="app-calendar.html"><i data-feather="calendar"></i><span--}}
{{--            class="menu-title text-truncate" data-i18n="Calendar">Calendar</span></a>--}}
{{--</li>--}}
{{--<li class=" nav-item"><a class="d-flex align-items-center" href="app-kanban.html"><i data-feather="grid"></i><span--}}
{{--            class="menu-title text-truncate" data-i18n="Kanban">Kanban</span></a>--}}
{{--</li>--}}
{{--<li class=" nav-item"><a class="d-flex align-items-center" href="#"><i data-feather="file-text"></i><span--}}
{{--            class="menu-title text-truncate" data-i18n="Invoice">Invoice</span></a>--}}
{{--    <ul class="menu-content">--}}
{{--        <li><a class="d-flex align-items-center" href="app-invoice-list.html"><i data-feather="circle"></i><span--}}
{{--                    class="menu-item text-truncate" data-i18n="List">List</span></a>--}}
{{--        </li>--}}
{{--        <li><a class="d-flex align-items-center" href="app-invoice-preview.html"><i data-feather="circle"></i><span--}}
{{--                    class="menu-item text-truncate" data-i18n="Preview">Preview</span></a>--}}
{{--        </li>--}}
{{--        <li><a class="d-flex align-items-center" href="app-invoice-edit.html"><i data-feather="circle"></i><span--}}
{{--                    class="menu-item text-truncate" data-i18n="Edit">Edit</span></a>--}}
{{--        </li>--}}
{{--        <li><a class="d-flex align-items-center" href="app-invoice-add.html"><i data-feather="circle"></i><span--}}
{{--                    class="menu-item text-truncate" data-i18n="Add">Add</span></a>--}}
{{--        </li>--}}
{{--    </ul>--}}
</li>
<!--=====================================
PARA UNA NUEVA SECCIÓN EN EL MENU
======================================-->
{{--<li class="navigation-header"><span data-i18n="">Nueva Sección</span><i data-feather="more-horizontal"></i>--}}
{{--</li>--}}

