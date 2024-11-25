const piHistoryModal = $("#pi-history-modal");
const transactionDetailsForPiModal = $("#transaction-details-for-pi-modal");
const transactionListingForPiModal = $("#transaction-listing-for-pi-modal");
let dtPiHistory;

function openPiHistory(item_id) {
    piHistoryModal.attr('data-item-id', item_id);
    $.ajax({
        url: `${base_url}/inventory/get_item/${item_id}`,
        type: "POST",
        dataType: "JSON",
        success: function (response) {
            $("#sku", piHistoryModal).val(response.sku);
            $("#description", piHistoryModal).val(response.name.trim());
            piHistoryModal.modal("show");
        }
    });
}

piHistoryModal
    .on("shown.bs.modal", function () {
        const item_id = piHistoryModal.attr('data-item-id');

        dtPiHistory = $("#tbl-pi-history")
            .dataTable({
                dom: "frtlp",
                ajax: {
                    url: `${base_url}/inventory/get_physical_inventory_history/${item_id}`,
                    type: "POST",
                    dataType: "JSON"
                },
                serverSide: true,
                processing: true,
                destroy: true,
                columns: [
                    {
                        data: "ref_no",
                        width: "12%",
                        render: function (data) {
                            return `<span class="m--font-bolder">${data}</span>`;
                        }
                    },
                    {
                        data: "variance",
                        width: "10%",
                        className: "text-center",
                        render: function (data) {
                            const badgeColor = parseFloat(data) > 0 ? "m-badge--success" : "m-badge--danger";
                            return `<span class="m-badge m-badge--wide m--font-boldest ${badgeColor}">${data}</span>`;
                        }
                    },
                    {data: "remarks"},
                    {
                        data: "created_at",
                        width: "17%",
                        render: function (data, type, row) {
                            return `<div class="">${row.created_by}</div>
                                    <div class="text-muted m--regular-font-size-sm1">${moment(data).format("MMM. DD,YYYY hh:mm A")}</div>`;
                        }
                    },
                    {
                        data: "date_confirmed",
                        width: "17%",
                        render: function (data, type, row) {
                            return `<div class="">${row.confirmed_by}</div>
                                    <div class="text-muted m--regular-font-size-sm1">${moment(data).format("MMM. DD,YYYY hh:mm A")}</div>`;
                        }
                    },
                    {
                        data: null,
                        width: "20%",
                        orderable: false,
                        render: function (data, type, row) {
                            const trans = row.trans ? row.trans.split("||") : null;
                            if (trans) {
                                let transTemplate = "";
                                trans.forEach((_trans) => {
                                    transTemplate += `
                                                <div class="mr-3 mb-1 d-inline-block">
                                                    <span class="m--font-bolder span-link" 
                                                        onclick="openTransactionDetails('${_trans}', ${item_id}); return false;">
                                                        ${_trans}
                                                    </span>
                                                </div>`;
                                });

                                if (trans && trans.length > 1) {
                                    transTemplate += `<div class="mr-3 mb-1 d-inline-block">
                                                        <span class="m--font-bolder span-link m--regular-font-size-sm2"
                                                              onclick="openTransactionTabularListing(${row.id}, ${item_id}); return false;">
                                                              SHOW AS LIST
                                                        </span>
                                                  </div>`;
                                }

                                return transTemplate;
                            }

                            return "---";
                        }
                    },
                ],
                initComplete: function () {
                    let api = dtPiHistory.api();
                    let timer = 0;

                    $('#tbl-pi-history_filter input')
                        .unbind('.DT')
                        .bind('keyup.DT', function (e) {
                            var value = this.value;

                            clearTimeout(timer);

                            timer = setTimeout(function () {
                                api.search(value).draw();
                            }, 500);
                        });
                },
                order: [[3, "desc"]]
            });
    });

function openTransactionDetails(ref_no, item_id) {
    $.ajax({
        url: `${base_url}/inventory/get_transaction_details_for_pi_history/${ref_no}/${item_id}`,
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            const data = response.data;
            const type = response.type;
            let modalTitle = null;
            let bodyTemplate = null;

            if (type === "RR") {
                modalTitle = "Receiving Details";
                bodyTemplate = `<div class="row">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="qty">QTY</label>
                                        <input type="text" id="qty" class="form-control" value="${data.qty}" disabled>
                                    </div>
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="unit_price">UNIT PRICE</label>
                                        <input type="text" id="unit_price" class="form-control" value="${data.unit_price}" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<hr/>
                                <div class="row">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="ref_no">Reference No.</label>
                                        <input type="text" id="ref_no" value="${data.reference_no}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="invoice_no">ISSUANCE DR NO.</label>
                                        <input type="text" id="invoice_no" value="${data.invoice_dr_no}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="po_no">P.O NO.</label>
                                        <input type="text" id="po_no" value="${data.po_no}" class="form-control" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<div class="row">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="created_by">Received By</label>
                                        <input type="text" id="created_by" value="${data.created_by}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="received_date">Date & Time Received</label>
                                        <input type="text" id="received_date" value="${moment(data.received_date).format('MMM. DD,YYYY hh:mm A')}" 
                                               class="form-control" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<div class="row">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="supplier">Supplier</label>
                                        <input type="text" id="supplier" value="${data.supplier}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="project_name">Project Name</label>
                                        <input type="text" id="project_name" value="${data.project_name}" class="form-control" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<div class="row">
                                    <div class="form-group col-12">
                                        <label for="remarks">Remarks</label>
                                        <textarea id="remarks" class="form-control" disabled>${data.remarks}</textarea>
                                    </div>
                                </div>`;
            } else {
                modalTitle = "Issuance Details";
                bodyTemplate = `<div class="row">
                                    <div class="form-group col-xl-3 col-lg-3 col-md-3 col-sm-12">
                                        <label for="qty">QTY</label>
                                        <input type="text" id="qty" class="form-control" value="${data.qty}" disabled>
                                    </div>
                                    <div class="form-group col-xl-9 col-lg-9 col-md-9 col-sm-12">
                                        <label for="qty">Purpose</label>
                                        <input type="text" id="qty" class="form-control" value="${data.purpose}" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<hr/>
                                <div class="row">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="ref_no">Reference No.</label>
                                        <input type="text" id="ref_no" value="${data.reference_no}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="ws_no">Withdrawal  Slip No.</label>
                                        <input type="text" id="ws_no" value="${data.ws_no}" class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="wo_no">Work Order No.</label>
                                        <input type="text" id="wo_no" value="${data.work_number}" class="form-control" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<div class="row">
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="issued_to">ISSUED TO</label>
                                        <input type="text" id="issued_to" class="form-control" value="${data.issued_to}" disabled>
                                    </div>
                                    <div class="form-group col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <label for="issued_to">Received By</label>
                                        <input type="text" id="issued_to" class="form-control" value="${data.received_by}" disabled>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-12">
                                        <label for="issued_to">Charged To</label>
                                        <input type="text" id="issued_to" class="form-control" value="${data.charge_to}" disabled>
                                    </div>
                                </div>`;

                bodyTemplate += `<div class="row">
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="ref_no">Issued Date</label>
                                        <input type="text" id="ref_no" 
                                               value="${moment(data.issued_date).format('MMM. DD,YYYY hh:mm A')}" 
                                               class="form-control" disabled>
                                    </div>
                                    <div class="form-group col-xl-4 col-lg-4 col-md-4 col-sm-12">
                                        <label for="ws_no">Approver</label>
                                        <input type="text" id="ws_no" value="${data.approved_by}" class="form-control" disabled>
                                    </div>
                                </div>`;
            }

            transactionDetailsForPiModal.find(".modal-title").empty().html(modalTitle);
            transactionDetailsForPiModal.find(".modal-body").empty().html(bodyTemplate);
            transactionDetailsForPiModal.modal("show");
        }
    });
}

function openTransactionTabularListing(pi_contents_id, item_id) {
    transactionListingForPiModal.attr("data-pi-contents-id", pi_contents_id);
    transactionListingForPiModal.attr("data-item-id", item_id);
    transactionListingForPiModal.modal("show");
}

transactionListingForPiModal
    .on("show.bs.modal", function () {
        const pi_contents_id = $(this).attr("data-pi-contents-id");
        const item_id = $(this).attr("data-item-id");

        const dt = $("#tbl-transaction-listing", this)
            .dataTable({
                dom: "frtlp",
                ajax: {
                    url: `${base_url}/inventory/get_transaction_tabular_listing/${pi_contents_id}/${item_id}`,
                    type: "POST",
                    dataType: "JSON"
                },
                serverSide: true,
                processing: true,
                destroy: true,
                autoWidth: false,
                columns: [
                    {
                        data: "type",
                        width: "12%",
                        render: function (data, type, row) {
                            return data === 'RR' ? 'Receiving' : 'Issuance';
                        }
                    },
                    {
                        data: "reference_no",
                        width: "18%",
                        render: function (data, type, row) {
                            return `<span class="m--font-bolder span-link"
                                          onclick="openTransactionDetails('${data}', ${row.item}); return false;">${data}
                                    </span>`;
                        }
                    },
                    {
                        data: "qty",
                        width: "8%",
                    },
                    {
                        data: "price",
                        width: "18%",
                        defaultContent: "---"
                    },
                    {
                        data: "purpose",
                        defaultContent: "---"
                    },
                    {
                        data: "trans_date",
                        visible: false
                    },
                ],
                order: [[5, "desc"]],
                initComplete: function () {
                    let api = dt.api();
                    let timer = 0;

                    $('#tbl-transaction-listing_filter input')
                        .unbind('.DT')
                        .bind('keyup.DT', function (e) {
                            var value = this.value;

                            clearTimeout(timer);

                            timer = setTimeout(function () {
                                api.search(value).draw();
                            }, 500);
                        });
                },
            });
    });