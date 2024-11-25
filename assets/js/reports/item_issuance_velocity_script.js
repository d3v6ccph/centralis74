const tblItemIssuanceVelocity = $("#tbl-item-issuance-velocity");
const dtTblItemIssuanceVelocity = tblItemIssuanceVelocity
    .DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: "INVENTORY ITEMS-ISSUANCE VELOCITY",
                className: "btn m-btn m-btn--square btn-warning text-white mb-2 btnNew",
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: "INVENTORY ITEMS-ISSUANCE VELOCITY",
                customize: function (doc) {
                    const rowCount = doc.content[1].table.body.length;
                    for (let i = 1; i < rowCount; i++) {
                        doc.content[1].table.body[i][6].alignment = 'center';
                    }
                },
                className: "btn m-btn m-btn--square btn-warning text-white mb-2 btnNew",
            }
        ],
        ajax: {
            url: base_url + "reports/get_items_for_issuance_velocity",
            dataType: "JSON",
            type: "POST"
        },
        processing: true,
        serverSide: false,
        columns: [
            {
                data: "sku",
                width: "10%",
            },
            {
                data: "name",
            },
            {
                data: "total_issued_qty",
                width: "10%",
                render: function (data) {
                    return parseFloat(data).toLocaleString(undefined, {maximumFractionDigits: 2});
                },
            },
            {
                data: "uom_code",
                width: "10%",
            },
            {
                data: "first_issuance",
                width: "10%",
            },
            {
                data: "last_issuance",
                width: "10%",
            },
            {
                data: "average_per_day",
                width: "10%",
            },
        ],
        pageLength: 50,
        order: [[6, "desc"]],
        autoWidth: false,
        initComplete: function () {
            $("#tbl-item-issuance-velocity_filter input[type='search']")
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

            $(dropdown).appendTo("#tbl-item-issuance-velocity_wrapper .exportDropdown");
        }
    });

function exportAs(type, dt) {
    const dropdown = $(".m-dropdown__toggle.export-as");
    dropdown.addClass("m-btn--custom m-loader m-loader--light m-loader--left");

    setTimeout(() => {
        switch (type) {
            case "excel":
                dtTblItemIssuanceVelocity.button(".buttons-excel").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
            case "pdf":
                dtTblItemIssuanceVelocity.button(".buttons-pdf").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
        }
    }, 150);
}