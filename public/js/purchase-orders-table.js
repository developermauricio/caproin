const getRow = function (json, attribute) {
  const data = json[attribute];
  if (!data) {
    return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
  } else {
    return `<span class="label text-center font-weight-bold">${data}</span>`;
  }
}

const initTable = function (urlListPurchaseOrders, urlCreate) {
  const table = $('.datatables-all-purchase-ordes').DataTable({
    "ordering": true,
    "orderCellsTop": true,
    "fixedHeader": true,
    "initComplete": function () { },
    "processing": true,
    "lengthMenu": [7, 10, 25, 50, 75, 100],
    "responsive": true,
    "pageLength": 10,
    "serverSide": true,
    "autoWidth": false,
    "columnDefs": [{
      "defaultContent": "-",
      "targets": "_all"
    }],
    "drawCallback": function (settings) {
      feather.replace();
    },
    "ajax": {
      url: urlListPurchaseOrders,
    },
    "columns": [
      {
        "data": "id"
      },
      {
        "data": "customer_order_number",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'customer_order_number');
        },
      },
      {
        data: "customer_id",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'customer_id');
        },
      },
      {
        data: "delivery_date_required_customer",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'delivery_date_required_customer');
        },
      },
      {
        render: (data, type, JsonResultRow, meta) => {
          return 'estado';
        }
      },
      {
        data: "description",
        render: function (data, type, JsonResultRow, meta) {
          return getRow(JsonResultRow, 'description');
        },
      },
      {
        data: "total_value",
        render: function (data, type, JsonResultRow, meta) {
          const value = JsonResultRow.total_value || 0;
          return value.toLocaleString();
        }
      },
      {
        render: function (data, type, JsonResultRow, meta) {
          return `opciones`;
        },
      }
    ],
    "language": languageDataTable,
    "dom": `<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>`,
    "buttons": [{
      "extend": 'collection',
      "className": 'btn btn-outline-secondary theme-light dropdown-toggle mr-2',
      "text": feather.icons['share'].toSvg({
        class: 'font-small-4 mr-50'
      }) + 'Exportar',
      "buttons": [
        {
          "extend": 'print',
          "text": feather.icons['printer'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Imprimir',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          },
          "customize": function (win) {
            $(win.document.body)
              .css('font-size', '10pt')
            $(win.document.body).find('table')
              .addClass('compact')
              .css('font-size', 'inherit');
          }
        },
        {
          "extend": 'csv',
          "text": feather.icons['file-text'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Csv',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          "extend": 'excel',
          "text": feather.icons['file'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Excel',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        },
        {
          "extend": 'pdf',
          "text": feather.icons['clipboard'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Pdf',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          },
          "orientation": 'vertical',
        },
        {
          "extend": 'copy',
          "text": feather.icons['copy'].toSvg({
            class: 'font-small-4 mr-50'
          }) + 'Copiar',
          "className": 'dropdown-item',
          "exportOptions": {
            columns: [1, 2, 3, 4, 5, 6]
          }
        }
      ],
    },
    {
      text: feather.icons['plus'].toSvg({
        class: 'mr-50 font-small-4'
      }) + 'Nueva orden de compra',
      className: 'create-new btn btn-primary',
      attr: {
        'id': "create-new-purchase-order",
      },
      init: function (api, node, config) {
        $(node).click(() => {
          window.location = urlCreate
        });
      }
    }
    ],
  })
  let text = 'Todos los proveedores registrados'
  $('div.head-label').html(`<h6 class="mb-0">${text}</h6>`);
  table.on('order.dt search.dt', function () {
    table.column(0, {
      search: 'applied',
      order: 'applied'
    }).nodes().each(function (cell, i) {
      cell.innerHTML = i + 1;
    });
  }).draw();


  $('.datatables-all-purchase-ordes').on('click', '.btn-show-purchase-order', function (e) {
    const id = e.target.getAttribute('data-id');
    $('#traerDatosBoton').val(id).click();
  });
}
