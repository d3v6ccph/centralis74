var selectedLogDate = "";

const dtTrail = $("#tbl-audit-trail")
    .DataTable({
        dom: "<'row'<'col-12'f>>" +
            "<'row'<'col-12'tr>>" +
            // "<'row mt-3'<'col-12'l>>" +
            "<'row'<'col-12'p>>",
        serverSide: false,
        destroy: true,
        ajax: {
            url: `${base_url}reports/audit_trail/get_trail_dates`,
            type: "GET",
            dataType: "JSON"
        },
        columns: [
            {
                data: "date",
                render: function (data, type, row) {
                    const template = `<span class="span-link" style="color: #000;"
                                            onclick="getLogs('${data}')">
                                        ${moment(data).format("dddd, MMMM DD YYYY")}
                                      </span>`;
                    return template;
                }
            },
            {
                data: "date",
                visible: false
            }
        ],
        initComplete: function () {
            $("#tbl-audit-trail_filter input[type='search']")
                .removeClass("form-control-sm");
        },
        order: [[1, "DESC"]]
    });

function getLogs(date) {
    $("#log-details-title").html(`LOG DETAILS FOR <span class="m--font-boldest2">${moment(date).format("MMMM DD, YYYY")}</span>`);
    selectedLogDate = moment(date).format("YYYY-MM-DD");
    setTimeout(() => {
        dtTrailDetails.ajax.reload();
    }, 100);
}

const dtTrailDetails = $("#tbl-audit-trail-details")
    .DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: function () {
                    return "LOG DETAILS FOR " + moment(selectedLogDate).format("MMM DD, YYYY");
                },
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: function () {
                    return "LOG DETAILS FOR " + moment(selectedLogDate).format("MMM DD, YYYY");
                },
                customize: function (doc) {
                    doc.content[1].table.widths = ["10%", "20%", "70%"];
                },
            }
        ],
        serverSide: false,
        processing: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: `${base_url}reports/audit_trail/get_trail_date_logs`,
            type: "POST",
            dataType: "json",
            data: function (d) {
                d.date = selectedLogDate;
            }
        }, columns: [
            {data: "time", width: "10%"},
            {data: "user", width: "15%"},
            {data: "action"},
        ],
        initComplete: function () {
            $("#tbl-audit-trail-details_filter input[type='search']")
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

            $(dropdown).appendTo("#tbl-audit-trail-details_wrapper .exportDropdown");
        }
    });

function exportAs(type, dt) {
    const dropdown = $(".m-dropdown__toggle.export-as");
    dropdown.addClass("m-btn--custom m-loader m-loader--light m-loader--left");

    setTimeout(() => {
        switch (type) {
            case "excel":
                dtTrailDetails.button(".buttons-excel").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
            case "pdf":
                dtTrailDetails.button(".buttons-pdf").trigger();
                dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                break;
        }
    }, 150);
}