const mxQtyRptTable = "mx-qty-rpt-table";
const exportTitle = "ITEM MAX. QTY REPORT AS OF " + moment().format("MMM DD, YYYY");
const mx_qty_ext_history_modal = $("#max-qty-extension-history-modal");

$("body")
    .tooltip({
        selector: '[data-toggle="m-tooltip"]'
    });

const dtMxQtyRptTable = $("#" + mxQtyRptTable).DataTable({
    dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
        "<'row'<'col-12'tr>>" +
        "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>",
    buttons: [
        {
            extend: 'excel',
            text: 'EXCEL',
            title: exportTitle,
            customize: function (excel) {
                const sheet = excel.xl.worksheets['sheet1.xml'];
            },
            exportOptions: {
                columns: 'th:not(:last-child)'
            }
        },
        {
            extend: 'pdf',
            text: 'PDF',
            title: exportTitle,
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
                columns: 'th:not(:last-child)'
            }
        }
    ],
    serverSide: false,
    processing: true,
    destroy: true,
    autoWidth: false,
    order: [[5, "DESC"]],
    ajax: {
        url: `${base_url}reports/max_qty/get_max_qty_report_list`,
        type: "post",
        dataType: "json"
    }, columns: [
        {data: 'sku', width: "10%"},
        {data: 'name', width: "30%"},
        {
            data: 'initial_max_qty',
            width: "10%",
            className: 'text-center',
        },
        {
            data: 'max_qty',
            width: "10%",
            className: 'text-center',
        },
        {
            data: 'variance',
            width: "10%",
            className: 'text-center',
        },
        {
            data: 'times_extended',
            width: "10%",
            className: 'text-center',
        },
        {
            data: null,
            orderable: false,
            className: 'text-center',
            width: "5%",
            render: function (data, type, row) {
                const hoverStyle = parseFloat(row.times_extended) > 0 ? "m-btn--hover-accent" : "";

                if (_actions.includes("btnHistory")) {
                    return `<button class="btn btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-sm ${hoverStyle}"
                                ${(hoverStyle ? "" : " disabled")} data-toggle="m-tooltip"
                                data-original-title="Show Extension History" onclick="openHistoryModal(${row.itemId})">
                            <i class="la la-list"></i>
                        </button>`;
                }

                return "---";
            }
        }
    ],
    initComplete: function () {
        $("#" + mxQtyRptTable + "_filter input[type='search']")
            .removeClass("form-control-sm");

        const dropdown = '' +
            '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
            '             data-dropdown-toggle="hover" aria-expanded="true">' +
            '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
            '                EXPORT AS' +
            '            </button>' +
            '            <div class="m-dropdown__wrapper">' +
            '                <div class="m-dropdown__inner">' +
            '                    <div class="m-dropdown__body">' +
            '                        <div class="m-dropdown__content">' +
            '                            <ul class="m-nav">' +
            '                                <li class="m-nav__item">' +
            '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
            '                                       onclick="exportAs(\'excel\', \'min\'); return false;" class="m-nav__link">' +
            '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
            '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
            '                                           Excel File' +
            '                                        </span>' +
            '                                    </a>' +
            '                                </li>' +
            '                                <li class="m-nav__item">' +
            '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
            '                                       onclick="exportAs(\'pdf\', \'min\'); return false;" class="m-nav__link">' +
            '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
            '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
            '                                          PDF File' +
            '                                        </span>' +
            '                                    </a>' +
            '                                </li>' +
            '                            </ul>' +
            '                        </div>' +
            '                    </div>' +
            '                </div>' +
            '            </div>' +
            '        </div>';

        $(dropdown).appendTo("#" + mxQtyRptTable + "_wrapper .exportDropdown");
    }
});

function exportAs(type, dt) {
    const dropdown = $(".m-dropdown__toggle.export-as");
    dropdown.addClass("m-btn--custom m-loader m-loader--light m-loader--left");

    setTimeout(() => {
        switch (type) {
            case "excel":
                dtMxQtyRptTable.button(".buttons-excel").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
            case "pdf":
                dtMxQtyRptTable.button(".buttons-pdf").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
        }
    }, 150);
}

function openHistoryModal(itemId) {
    $.ajax({
        url: `${base_url}reports/max_qty/get_item_max_qty_extension_request_history/${itemId}`,
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            const item = response.item;
            const history = response.history;

            $("#sku", mx_qty_ext_history_modal).val(item.sku);
            $("#name", mx_qty_ext_history_modal).val(item.name);
            $("#initial-max-qty", mx_qty_ext_history_modal).val(item.initial_max_qty);
            $("#current-max-qty", mx_qty_ext_history_modal).val(item.max_qty);

            $("#tbl-max-qty-history")
                .DataTable({
                    dom: "rtlp",
                    serverSide: false,
                    processing: true,
                    destroy: true,
                    data: history,
                    autoWidth: false,
                    columns: [
                        {
                            data: "curr_max_qty",
                            width: "10%"
                        },
                        {
                            data: "qty_increase",
                            width: "10%"
                        },
                        {
                            data: "remarks",
                        },
                        {
                            data: "status",
                            width: "25%",
                            render: function (data, type, row) {
                                let status = null;
                                switch (parseInt(data)) {
                                    case 0:
                                        status = `<div class="m-badge m-badge--wide m-badge--warning m--font-boldest">PENDING</div>`;
                                        break;
                                    case 1:
                                        status = `<div class="m-badge m-badge--wide m-badge--success m--font-boldest">APPROVED</div>`;
                                        break;
                                    case 2:
                                        status = `<div class="m-badge m-badge--wide m-badge--danger m--font-boldest">DECLINED</div>`;
                                        break;
                                    default:
                                        status = `<div class="m-badge m-badge--wide m-badge--metal m--font-boldest">CANCELLED</div>`;
                                        break;
                                }

                                if (parseInt(data) !== 0) {
                                    status += `<div class="m--regular-font-size-sm1 mt-1 text-muted">
                                                    <span style="text-transform: none;">by:</span>
                                                    <span class="m--font-bolder">${row._confirmed_by}</span>
                                               </div>
                                               <div class="m--regular-font-size-sm1 text-muted">
                                                    <span class="m--font-bolder">${moment(row.confirmed_at).format("MMM DD,YYYY hh:mm:ss A")}</span>
                                               </div>`;

                                    if (row.remarks) {
                                        status += `<div class="m--regular-font-size-sm1 mt-2 text-muted">
                                                    <span>REMARKS:</span>
                                                    <span class="m--font-bolder">${row.remarks}</span>
                                               </div>`;
                                    }
                                }

                                return status;
                            }
                        },
                        {
                            data: "created_at",
                            width: "25%",
                            render: function (data, type, row) {
                                return `<div>${row._created_by}</div>
                                        <div class="m--regular-font-size-sm1">${moment(row.created_at).format("MMM DD,YYYY hh:mm:ss A")}</div>`;
                            }
                        },
                        {
                            data: "confirmed_at",
                            visible: false
                        }
                    ],
                    order: [[5, "DESC"]]
                });
        }
    });

    mx_qty_ext_history_modal.attr("data-id", itemId);
    mx_qty_ext_history_modal.modal("show");
}

function printHistory() {
    const itemId = mx_qty_ext_history_modal.attr("data-id");
    window.open(`${base_url}reports/max_qty/print_history/${itemId}`, "_blank");
}