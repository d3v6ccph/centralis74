const newModal = $("#new-extension-request-modal");
const editModal = $("#edit-extension-request-modal");
const confirmModal = $("#confirm-modal");

$("body")
    .tooltip({
        selector: "[data-toggle='m-tooltip']"
    });

const dtExtensionRequests = $("#tbl-extension-requests")
    .DataTable({
        dom: "rtlp",
        serverSide: true,
        processing: true,
        autoWidth: false,
        ajax: {
            url: `${base_url}inventory/get_max_qty_requests`,
            type: "POST",
            dataType: "JSON",
            data: function (data) {
                data.search.value = $("#search-extension-requests").val();
                data.filter = $("#status").val();
            }
        },
        columns: [
            {
                data: "sku",
                width: "8%"
            },
            {
                data: "name"
            },
            {
                data: "curr_max_qty",
                width: "10%",
                className: "text-center",
                render: function (data) {
                    return `<span class="m--font-boldest">${parseFloat(data).toLocaleString(undefined, {minimumFractionDigits: 0})}</span>`;
                }
            },
            {
                data: "new_max_qty",
                width: "10%",
                className: "text-center",
                render: function (data) {
                    return `<span class="m--font-boldest">${parseFloat(data).toLocaleString(undefined, {minimumFractionDigits: 0})}</span>`;
                }
            },
            {
                data: "remarks",
                width: "15%"
            },
            {
                data: "status",
                width: "15%",
                render: function (data, type, row) {
                    let status = '';
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
                        case 3:
                            status = `<div class="m-badge m-badge--wide m-badge--metal m--font-boldest">CANCELLED</div>`;
                            break;
                    }

                    if (parseInt(data) !== 0) {
                        status += `<div class="m--regular-font-size-sm2 m--font-bolder mt-1">
                                        <span style="text-transform: none;">by:</span>
                                        <span>${row._confirmed_by}</span>
                                   </div>`;
                        status += `<div class="m--regular-font-size-sm2 m--font-bolder">
                                        <span>${moment(row.confirmed_at).format("MMM DD,YYYY hh:mm A")}</span>
                                   </div>`;

                        if (row.confirmed_remarks) {
                            status += `<div class="m--regular-font-size-sm2 mt-2 m--font-boldest">
                                        <span style="font-weight: normal;">REMARKS: </span>
                                        <span>${row.confirmed_remarks}</span>
                                   </div>`;
                        }
                    }

                    return status;
                }
            },
            {
                data: "created_at",
                width: "13%",
                render: function (data, type, row) {
                    return `<div>${row._created_by}</div>
                            <div class="m--regular-font-size-sm1">${moment(row.created_at).format("MMM DD,YYYY hh:mm:ss A")}</div>`;
                }
            },
            {
                data: null,
                width: "8%",
                orderable: false,
                className: "text-center",
                defaultContent: "---",
                render: function (data, type, row) {
                    let buttons = "";
                    if (_actions.includes("btnEdit") && (parseInt(row.status) === 0)) {
                        buttons += `<button class="btn btn-sm btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-accent"
                                            onclick='openEditExtensionRequestModal(${row.id})'
                                            data-toggle="m-tooltip" data-original-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </button>`;
                    }

                    if (_actions.includes("btnApproval") && (parseInt(row.status) === 0)) {
                        buttons += ` <div class="m-dropdown m-dropdown--inline m-dropdown--small 
                                                m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push" 
                                         data-dropdown-toggle="click" aria-expanded="true">
                                        <button class="m-portlet__nav-link m-dropdown__toggle btn btn-sm 
                                                       m-btn m-btn--icon m-btn--icon-only m-btn--pill btn-default m-btn--hover-accent" 
                                                data-toggle="m-tooltip" data-original-title="Approval Options" 
                                                data-placement="top" data-delay='{"show": 300}' data-skin="dark">
                                                <i class="la la-ellipsis-v"></i>
                                        </button>   
                                       <div class="m-dropdown__wrapper">
                                          <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" style="left: auto; right: 13px;"></span>       
                                          <div class="m-dropdown__inner">
                                             <div class="m-dropdown__body">
                                                <div class="m-dropdown__content">
                                                   <ul class="m-nav">
                                                      <li class="m-nav__item">
                                                        <a href="javascript:void(0);" class="m-nav__link" onclick='confirmExtensionRequest(1, ${row.id})'>
                                                            <i class="m-nav__link-icon fa fa-thumbs-o-up"></i>
                                                            <span class="m-nav__link-text">Approve</span>                          
                                                        </a>                      
                                                      </li>
                                                      <li class="m-nav__item">
                                                        <a href="javascript:void(0);" class="m-nav__link" onclick='confirmExtensionRequest(2, ${row.id})'>
                                                            <i class="m-nav__link-icon fa fa-thumbs-o-down"></i>
                                                            <span class="m-nav__link-text">Decline</span>                          
                                                        </a>                      
                                                      </li>
                                                      <li class="m-nav__item">
                                                        <a href="javascript:void(0);" class="m-nav__link" onclick='confirmExtensionRequest(3, ${row.id})'>
                                                            <i class="m-nav__link-icon fa fa-ban"></i>
                                                            <span class="m-nav__link-text">Cancel</span>                          
                                                        </a>                      
                                                      </li>
                                                   </ul>
                                                </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>`;
                    }

                    return buttons ? buttons : "---";
                }
            }
        ],
        order: [[6, "DESC"]],
        columnDefs: [{
            targets: [0, 1],
            render: function (data) {
                return `<span class="m--font-bolder">${data}</span>`;
            }
        }]
    });

$("#status")
    .select2()
    .on("select2:select", function () {
        dtExtensionRequests.ajax.reload();
    });

$("#item", newModal)
    .select2({
        placeholder: "Please select an item.",
        width: "100%",
        dropdownParent: newModal,
        ajax: {
            url: `${base_url}inventory/get_item_list_for_select_on_max_extension_request`,
            dataType: "JSON",
            delay: 500,
            type: "GET"
        },
        escapeMarkup: function (markup) {
            return markup;
        }
    })
    .on("select2:select", function (e) {
        const data = e.params.data;
        $("#curr_max_qty", newModal).val(data.max_qty);
    });

$.validate({
    form: '#frm-new-extension-request',
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (form) {
        const data = $(form).serializeArray();
        const btn = $(form).find("button[type='submit']");
        data.push({name: "curr_max_qty", value: $("#curr_max_qty").val()});

        $.ajax({
            url: `${base_url}inventory/save_max_qty_extension_request`,
            type: "POST",
            dataType: "JSON",
            data,
            beforeSend: function () {
                $(btn).addClass("m-btn--custom m-loader m-loader--light m-loader--left");
                $(btn).attr("disabled", true);
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                if (response.success) {
                    $(btn).removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    $(btn).attr("disabled", false);
                }

                toastr[toast](response.message, response.title, {timeOut: 10000});
                dtExtensionRequests.ajax.reload();
                $(form).resetForm();
                $("#item", this).val(null).trigger('change');
                newModal.modal("hide");
            }
        });

        return false;
    }
});

$("#search-extension-requests")
    .donetyping(function () {
        dtExtensionRequests.ajax.reload();
    });

function openEditExtensionRequestModal(id) {
    $.ajax({
        url: `${base_url}inventory/get_max_qty_request/${id}`,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $("#id", editModal).val(data.id);
            $("#sku", editModal).val(data.sku);
            $("#name", editModal).val(data.name.toString());
            $("#curr_max_qty", editModal).val(data.curr_max_qty);
            $("#extend_qty", editModal).val(data.qty_increase);
            $("#remarks", editModal).val(data.remarks);
            editModal.modal("show");
        }
    });
}

$.validate({
    form: $("#frm-edit-extension-request"),
    lang: "en",
    scrollToTopOnError: false,
    onSuccess: function (form) {
        const data = $(form).serializeArray();
        const btn = $(form).find("button[type='submit']");
        data.push({name: "curr_max_qty", value: $("#curr_max_qty", form).val()});

        $.ajax({
            url: `${base_url}inventory/edit_max_qty_extension_request`,
            type: "POST",
            dataType: "JSON",
            data,
            beforeSend: function () {
                $(btn).addClass("m-btn--custom m-loader m-loader--light m-loader--left");
                $(btn).attr("disabled", true);
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                if (response.success) {
                    $(btn).removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    $(btn).attr("disabled", false);
                }

                toastr[toast](response.message, response.title, {timeOut: 10000});
                dtExtensionRequests.ajax.reload();
                editModal.modal("hide");
            }
        });
        return false;
    }
})

function confirmExtensionRequest(status, id) {
    $.ajax({
        url: `${base_url}inventory/get_max_qty_request/${id}`,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            let title = "";
            let message = `Are you sure you want to `;
            switch (parseInt(status)) {
                case 1:
                    title = `<span class="m--font-success m--font-bolder">Approve</span> Confirmation`;
                    message += `approve request for <span class="m--font-bolder">${data.name}?</span>`;
                    break;
                case 2:
                    title = `<span class="m--font-danger m--font-bolder">Decline</span> Confirmation`;
                    message += `decline request for <span class="m--font-bolder">${data.name}?</span>`;
                    break;
                case 3:
                    title = `<span class="m--font-bolder">Cancel</span> Confirmation`;
                    message += `cancel request for <span class="m--font-bolder">${data.name}?</span>`;
                    break;
            }
            confirmModal.find(".modal-title").html(title);
            confirmModal.find(".modal-body p").html(message);
            confirmModal.find("form").attr("action", `${base_url}inventory/confirm_extension_request/${data.id}/${status}`);
            confirmModal.find("#item_name").val(data.name);
            confirmModal.modal("show");
        }
    });
}

$.validate({
    form: $("#frm-confirm-modal"),
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (form) {
        const url = $(form).attr("action");
        const data = $(form).serializeArray();
        const btn = $(form).find("button[type='submit']");

        $.ajax({
            url,
            type: "POST",
            dataType: "JSON",
            data,
            beforeSend: function () {
                $(btn).addClass("m-btn--custom m-loader m-loader--light m-loader--left");
                $(btn).attr("disabled", true);
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                if (response.success) {
                    $(btn).removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    $(btn).attr("disabled", false);
                }

                toastr[toast](response.message, response.title, {timeOut: 10000});
                dtExtensionRequests.ajax.reload();
                confirmModal.modal("hide");
            }
        });
        return false;
    }
})