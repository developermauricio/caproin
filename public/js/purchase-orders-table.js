const initTable = function (urlListPurchaseOrders) {
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
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.code === null) {
              return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
            } else {
              return `<span class="label text-center font-weight-bold">${JsonResultRow.code}</span>`;
            }
          },
        },
        {
          data: "type_providers.name",
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.type_providers.name === null) {
              return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
            } else {
              return `<span class="label text-center font-weight-bold">${JsonResultRow.type_providers.name}</span>`;
            }
          },
        },
        {
          data: "users.identification_type.name",
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.users.identification_type === null) {
              return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
            } else {
              return `<span class="label text-center font-weight-bold">${JsonResultRow.users.identification_type.name}</span>`;
            }
          },
        },
        {
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.users.identification === null) {
              return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
            } else {
              return `<span class="label text-center font-weight-bold">${JsonResultRow.users.identification}</span>`;
            }
          },
        },
        {
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.business_name === null) {
              return '<span class="label label-danger text-center" style="color:#0082FB !important">Ningún valor por defecto</span>'
            } else {
              return `<span class="label text-center font-weight-bold">${JsonResultRow.business_name}</span>`;
            }
          },
        },
        {
          data: "state",
          render: function (data, type, JsonResultRow, meta) {
            if (JsonResultRow.state === "1") {
              return '<div class="badge badge-pill badge-light-success mr-1">Activo</div>'
            } else {
              return `<div class="badge badge-pill badge-light-danger mr-1">Inactivo</div>`;
            }
          },
        },
        {
          render: function (data, type, JsonResultRow, meta) {
            return `
            <div
              data-id="${JsonResultRow.id}"
              class="demo-inline-spacing text-center">
                <button data-id="${JsonResultRow.id}" data-target="#modal-show-purchase-order" data-toggle="modal" data-toggle="tooltip" data-placement="top" title="Más Información" type="button" class="btn btn-show-purchase-order btn-icon btn-primary">
                <i data-id="${JsonResultRow.id}" data-feather="edit-2"></i>
                </button>
            </div>`

          },
        },
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
          'data-target': '#modal-new-purchase-order',
          'data-toggle': 'modal',
        },
        init: function (api, node, config) {
          $(node).removeClass('btn-secondary');
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

