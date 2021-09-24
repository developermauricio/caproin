<!-- BEGIN: Vendor JS-->
<script src="/app-assets/vendors/js/vendors.min.js"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<!-- END: Page Vendor JS-->

<!-- BEGIN: Theme JS-->
<script src="/app-assets/vendors/js/extensions/sweetalert2.all.min.js"></script>
<script src="/app-assets/js/core/app-menu.js"></script>
<script src="/app-assets/js/core/app.js"></script>
{{--<script src="/app-assets/js/scripts/extensions/ext-component-sweet-alerts.js"></script>--}}
<script src="/app-assets/js/scripts/components/components-tooltips.js"></script>
<script src="/js/moment.js"></script>
<script src="/js/moment-locale.js"></script>
@if (!isset($customScript))
<script src="{{ asset('js/app.js') }}"></script>
@endif
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<!-- END: Page JS-->
