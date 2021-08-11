<!--=====================================
MENU ADMIN
======================================-->
<li class=" navigation-header"><span data-i18n="Apps &amp; Pages">Navegación</span><i
        data-feather="more-horizontal"></i>
</li>
<li class="{{request()->is('customers') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.customer.customers') }}"><i data-feather="users"></i><span
            class="menu-title text-truncate" data-i18n="Email">Clientes</span></a>
</li>
<li class="{{request()->is('providers') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.provider.providers') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Proveedores</span></a>
</li>
<li class="{{request()->is('sucursales') ? 'active' : '' }} nav-item"><a class="d-flex align-items-center" href="{{ route('admin.branch_offices') }}"><i data-feather='box'></i><span
            class="menu-title text-truncate" data-i18n="Email">Sucursales</span></a>
</li>
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
