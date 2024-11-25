let searchValue = null;
const dropdown = $(".m-dropdown__toggle.export-as");

const dtPIitemized = $("#tbl-physical-inventory-itemized").DataTable({
    dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'><'col-xl-3 col-lg-3 col-md-3 col-sm-12 " +
        "         offset-xl-3 offset-lg-3 offset-m-3 offset-sm-0'>>" +
        "<'row'<'col-12'tr>>" +
        "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>",
    buttons: [
        {
            extend: 'excel',
            text: 'EXCEL',
            title: function () {
                const _status = parseInt($("#status").val());
                let status = "";
                switch (_status) {
                    case 0:
                        status = "- Pending";
                        break;
                    case 1:
                        status = "- Verified";
                        break;
                    case 2:
                        status = "- Cancelled";
                        break;
                    case 3:
                        status = "- Void";
                        break;
                }
                return "Physical Inventory by SKU " + status;
            },
            customize: function (excel) {
                // const sheet = excel.xl.worksheets['sheet1.xml'];
            },
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6]
            },
            action: function (e, dt, node, config) {
                const self = this;
                const data = dtPIitemized.ajax.params();
                $.ajax({
                    url: `${base_url}inventory/get_physical_inventory_list_by_sku/1`,
                    type: "POST",
                    dataType: "JSON",
                    data,
                    success: function (response) {
                        dt.rows().remove();
                        dt.rows.add(response.data).draw();
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, node, config);
                        dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    }
                });
            }
        },
        {
            extend: 'pdf',
            text: 'PDF',
            title: function () {
                const _status = parseInt($("#status").val());
                let status = "";
                switch (_status) {
                    case 0:
                        status = "- Pending";
                        break;
                    case 1:
                        status = "- Verified";
                        break;
                    case 2:
                        status = "- Cancelled";
                        break;
                    case 3:
                        status = "- Void";
                        break;
                }

                return "Physical Inventory by SKU " + status;
            },
            customize: function (doc) {
                const rowCount = doc.content[1].table.body.length;
                for (let i = 1; i < rowCount; i++) {
                    doc.content[1].table.body[i][2].alignment = 'center';
                    doc.content[1].table.body[i][3].alignment = 'center';
                    doc.content[1].table.body[i][4].alignment = 'center';
                    doc.content[1].table.body[i][5].alignment = 'center';
                }
            },
            exportOptions: {
                columns: [1, 2, 3, 4, 5, 6]
            },
            action: function (e, dt, node, config) {
                const self = this;
                const data = dtPIitemized.ajax.params();

                $.ajax({
                    url: `${base_url}inventory/get_physical_inventory_list_by_sku/1`,
                    type: "POST",
                    dataType: "JSON",
                    data,
                    success: function (response) {
                        dt.rows().remove();
                        dt.rows.add(response.data).draw();
                        $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, node, config);
                        dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    }
                });
            }
        }
    ],
    serverSide: true,
    processing: true,
    destroy: true,
    autoWidth: false,
    order: [[0, "DESC"]],
    ajax: {
        url: `${base_url}inventory/get_physical_inventory_list_by_sku`,
        type: "post",
        dataType: "json",
        data: function (d) {
            d.search.value = searchValue;
            d.filter = $("#status").val();
        }
    },
    columns: [
        {
            data: 'created_at', visible: false
        },
        {
            data: "sku",
            width: "10%",
            render: function (data, type, row) {
                if (row.status === "invalid") {
                    if (parseInt($("#status").val()) === 3) {
                        return `<span class="text-muted">${data}</span>`;
                    } else {
                        return `<span class="text-muted" style="text-decoration: line-through;">${data}</span>`;
                    }
                }

                return `<span class="m--font-bolder">${data}</span>`;
            }
        },
        {
            data: "name",
            render: function (data, type, row) {
                if (row.status === "invalid") {
                    if (parseInt($("#status").val()) === 3) {
                        return `<span class="text-muted">${data}</span>`;
                    } else {
                        return `<span class="text-muted" style="text-decoration: line-through;">${data}</span>`;
                    }
                }

                return `<span class="m--font-bolder">${data}</span>`;
            }
        },
        {
            data: "ref_no",
            width: "9%"
        },
        {
            data: "qty",
            width: "10%",
            className: "text-center",
        },
        {
            data: "count",
            width: "10%",
            className: "text-center",
        },
        {
            data: "variance",
            width: "10%",
            className: "text-center",
            render: function (data, type, row) {
                const badgeColor = parseFloat(data) < 0 ? "m-badge--danger" : "m-badge--success";
                return `<span class="m-badge m-badge--wide m--font-boldest2 ${badgeColor}">${data}</span>`;
            }
        },
        {
            data: "moving_variance",
            width: "12%",
            className: "text-center",
            render: function (data) {
                return `<span class="m--font-boldest2">${data}</span>`;
            }
        },
    ],
    initComplete: function () {
    },
    pageLength: 15,
    lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "All"]],
    drawCallback: function (settings) {
        var api = this.api();
        var rows = api.rows({page: 'current'}).nodes();
        var last = null;

        api.column(0, {page: 'current'}).data().each(function (group, i) {
            const _group = moment(group).format("YYYY-MM-DD");
            if (last !== _group) {
                $(rows).eq(i).before(
                    '<tr class="group"><td colspan="7">' + moment(_group).format('ll') + '</td></tr>'
                );

                last = _group;
            }
        });
    }
});

function exportAs(type) {
    dropdown.addClass("m-btn--custom m-loader m-loader--light m-loader--left");

    setTimeout(() => {
        switch (type) {
            case "excel":
                dtPIitemized.button(".buttons-excel").trigger();
                break;
            case "pdf":
                dtPIitemized.button(".buttons-pdf").trigger();
                break;
        }
    }, 150);
}

$(".clearable-search-box")
    .donetyping(function () {
        searchValue = $(this).val();
        dtPIitemized.ajax.reload();
    });

$("#status")
    .select2()
    .on("select2:select", function (e) {
        dtPIitemized.ajax.reload();
    });