let dtUpdateList = $("#tbl-updates-list")
    .DataTable({
        dom: "rtlip",
        ajax: {
            url: `${base_url}/inventory/transaction_updates_list`,
            type: "POST",
            dataType: "JSON",
            data: function (d) {
                d.search.value = $("#search-transaction-updates").val();
                d.trans_type = $("#trans_type").val();
            }
        },
        serverSide: true,
        processing: true,
        destroy: true,
        columns: [
            {
                data: "trans_type",
                width: "8%",
                render: function (data) {
                    const style = data === "issuance" ? "m-badge--primary" : "m-badge--accent";
                    return "<span class='m--font-boldest m-badge m-badge--wide " + style + "'>" + data + "</span>";
                }
            },
            {
                data: "reference_no",
                width: "8%",
                render: function (data) {
                    return "<span class='m--font-boldest'>" + data + "</span>";
                }
            },
            {
                data: "name",
                render: function (data, type, row) {
                    return "<span class='mb-1 m--font-boldest'>" + row.sku + "</span>" + "<br/>" + data;
                }
            },
            {
                data: "qty_current",
                width: "7%",
                render: function (data, type, row) {
                    if (data) {
                        return "" +
                            "<span class='mr-2 font-weight-bold'>" + data + "</span>" +
                            "   <i class='fa fa-arrow-right text-muted'></i>" +
                            "<span class='ml-2 font-weight-bold'>" + row.qty_change + "</span>";
                    }

                    return "---";
                }
            },
            {
                data: "price_current",
                width: "7%",
                render: function (data, type, row) {
                    if (data) {
                        return "" +
                            "<span class='mr-2 font-weight-bold'>" + data + "</span>" +
                            "   <i class='fa fa-arrow-right text-muted'></i>" +
                            "<span class='ml-2 font-weight-bold'>" + row.price_change + "</span>";
                    }

                    return "---";
                }
            },
            {data: "remarks", width: "20%"},
            {
                data: "status",
                width: "12%",
                render: function (data, type, row) {
                    let status = null;
                    switch (parseInt(data)) {
                        case 0:
                            status = "<span class='m--font-boldest m-badge m-badge--wide m-badge--warning'>Pending</span>";
                            break;
                        case 1:
                            status = "<span class='m--font-boldest m-badge m-badge--wide m-badge--success'>Approved</span>";
                            break;
                        case 2:
                            status = "<span class='m--font-boldest m-badge m-badge--wide m-badge--danger'>Declined</span>";
                            break;
                        default:
                            status = "<span class='m--font-boldest m-badge m-badge--wide m-badge--metal'>Cancelled</span>";
                            break;
                    }

                    if (row._confirmed_by) {
                        status += "<br/><div class='m--regular-font-size-sm2 mt-2'>By " + row._confirmed_by + "</div>";
                        status += "<div class='m--regular-font-size-sm2'>" + moment(row.date_confirmed).format("MMM DD,YYYY hh:mm A") + "</div>";
                    }

                    return status;
                }
            },
            {
                data: "created_at",
                width: "12%",
                render: function (data, type, row) {
                    return "<div class='mb-1'>" + row.employee + "</div>" + "<div class='m--regular-font-size-sm1'>" + moment(data).format("MMM DD,YYYY hh:mm A") + "</div>";
                }
            },
            {
                data: null,
                width: "8%",
                render: function (data, type, row) {
                    let buttons = "";
                    if (_actions.includes("btnApproval")) {
                        buttons += "" +
                            "<button class='btn btn-default btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill " + (parseInt(row.status) === 0 ? " m-btn--hover-primary" : "") + "' " + (parseInt(row.status) !== 0 ? "disabled " : " ") +
                            "        data-toggle='m-tooltip'" +
                            "        data-original-title='Approve'" +
                            "        onclick='statusConfirmation(" + JSON.stringify(row) + ", 1)'>" +
                            "   <i class='la la-thumbs-o-up'></i>" +
                            "</button> ";
                        buttons += "" +
                            "<button class='btn btn-default btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill " + (parseInt(row.status) === 0 ? " m-btn--hover-warning" : "") + "'" + (parseInt(row.status) !== 0 ? "disabled " : " ") +
                            "        data-toggle='m-tooltip'" +
                            "        data-original-title='Decline'" +
                            "        onclick='statusConfirmation(" + JSON.stringify(row) + ", 2)'>" +
                            "   <i class='la la-thumbs-o-down'></i>" +
                            "</button> ";
                        buttons += "" +
                            "<button class='btn btn-default btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill " + (parseInt(row.status) === 0 ? " m-btn--hover-danger" : "") + "'" + (parseInt(row.status) !== 0 ? "disabled " : " ") +
                            "        data-toggle='m-tooltip'" +
                            "        data-original-title='Cancel'" +
                            "        onclick='statusConfirmation(" + JSON.stringify(row) + ", 3)'>" +
                            "   <i class='la la-ban'></i>" +
                            "</button> ";
                    }

                    return buttons;
                },
                className: "text-center",
                orderable: false
            },
        ],
        order: [[7, "desc"]],
    });

$("#trans_type")
    .select2({
        width: "100%"
    })
    .on("select2:select", function (e) {
        dtUpdateList.ajax.reload();
    });

function statusConfirmation(data, status) {
    const _modal = $("#status-update-confirmation");
    const title = _modal.find(".modal-title");
    const body = _modal.find(".modal-body #msg-container");
    const form = $("#frm-status-confirmation");
    form.find("input[name='trans_type']").val(data.trans_type);
    form.find("input[name='id']").val(data.id);
    form.find("input[name='status']").val(status);
    form.find("input[name='pi_contents_id']").val(data.pi_contents_id);
    form.find("input[name='item']").val(data.item);

    switch (parseInt(status)) {
        case 1:
            title.html("APPROVAL CONFIRMATION");
            body.empty().append("<p style='font-size: 16px;'>Are you sure to <span class='m--font-success m--font-bolder'>approve</span> this update request?</p>");
            break;
        case 2:
            title.html("DECLINE CONFIRMATION");
            body.empty().append("<p style='font-size: 16px;'>Are you sure to <span class='m--font-warning m--font-bolder'>decline</span> this update request?</p>");
            break;
        default:
            title.html("CANCEL CONFIRMATION");
            body.empty().append("<p style='font-size: 16px;'>Are you sure to <span class='m--font-danger m--font-bolder'>cancel</span> this update request?</p>");
            break;
    }

    _modal.modal("show");
}

$("#frm-status-confirmation")
    .on("submit", function (e) {
        e.preventDefault();
        const _modal = $("#status-update-confirmation");
        const data = $(this).serialize();
        const status = parseInt($(this).find("input[name='status']").val());
        let msg = null;
        switch (status) {
            case 1:
                msg = ["Transaction update was successfully <b>Approved</b>.", "Approved", 10000];
                break;
            case 2:
                msg = ["Transaction update <b>Declined</b>.", "Declined", 10000];
                break;
            default:
                msg = ["Transaction update <b>Cancelled</b>.", "Cancelled", 10000];
                break;
        }

        $.ajax({
            url: `${base_url}/inventory/confirm_trans_update_request`,
            data,
            type: "POST",
            dataType: "JSON",
            success: function (response) {
                if (response) {
                    _modal.modal("hide");
                    dtUpdateList.ajax.reload();
                    toastr.success(msg[0], msg[1], msg[2]);
                }
            }
        });
    });


$("#search-transaction-updates")
    .donetyping(function () {
        dtUpdateList.ajax.reload();
    });

$(document).tooltip({
    selector: "[data-toggle='m-tooltip']"
});