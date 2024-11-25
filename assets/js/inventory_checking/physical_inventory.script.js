let searchKey = "";
const modalConfirm = $("#modal-confirm-physical-inventory");

$(".clearable-search-box")
    .on("input", function () {
        $(this).val() ? $(".clear-search").css("visibility", "visible") : $(".clear-search").css("visibility", "hidden");
    });

$('body').tooltip({
    selector: '[data-toggle="m-tooltip"]'
});

$(".clear-search")
    .on("click", function () {
        $(".clearable-search-box").val("");
        $(".clear-search").css("visibility", "hidden");
        searchKey = "";
        dtPI.ajax.reload();
    });

let dtPI = $("#tbl-pi-list")
    .DataTable({
        dom: "rtlp",
        serverSide: true,
        processing: true,
        ajax: {
            url: base_url + "inventory/get_physical_inventory_list",
            type: "post",
            dataType: "json",
            data: function (d) {
                d.search.value = searchKey;
                d.filter = $("#status").val();
            }
        },
        columns: [
            {data: "ref_no", width: "15%"},
            {data: "remarks"},
            {
                data: "status",
                render: function (data, meta, row) {
                    let color = null;
                    let status = null;
                    switch (parseInt(data)) {
                        case 0:
                            status = "Pending";
                            color = "m-badge--warning";
                            break;
                        case 1:
                            status = "Verified";
                            color = "m-badge--success";
                            break;
                        case 2:
                            status = "Cancelled";
                            color = "m-badge--danger";
                            break;
                        default:
                            status = "Void";
                            color = "m-badge--metal";
                            break;
                    }
                    return "<span class='m-badge m-badge--wide m--font-bolder " + color + "'>" + status + "</span>";
                },
                width: "13%"
            },
            {
                data: "created_at",
                render: function (data, type, row) {
                    return row.created_by_name + "<br/><span class='text-muted m--regular-font-size-sm1'>" +
                        moment(row.created_at).format("MMM DD, YYYY hh:mm:ss A") + "</span>";
                },
                width: "18%"
            },
            {
                data: "verified_by",
                render: function (data, type, row) {
                    return (data ? data : "---") + "<br/><span class='text-muted m--regular-font-size-sm1'>" +
                        (row.verified_date ? moment(row.verified_date).format("MMM DD, YYYY hh:mm:ss A") : "") + "</span>";
                },
                width: "18%"
            },
            {
                data: "id",
                orderable: false,
                className: "text-center",
                width: "8%",
                render: function (data, meta, row) {
                    const disabled = parseInt(row.status) !== 0 ? "disabled" : "";
                    const hover = parseInt(row.status) !== 0 ? "" : "btn-default m-btn--hover-accent";
                    const edit_mode = parseInt(row.status) === 0 ? "<i class='fa fa-pencil'></i>" : "<i class='fa fa-eye'></i>";
                    const edit_tooltip = parseInt(row.status) === 0 ? "Edit" : "View";

                    let buttons = "";

                    if (_actions.includes("btnEdit") || _actions.includes("btnHistory")) {
                        buttons += "" +
                            "<button onclick='openEditPage(" + row.id + ")'" +
                            "        data-toggle='m-tooltip' " +
                            "        data-original-title='" + edit_tooltip + "' " +
                            "        data-placement='top' " +
                            "        data-delay='{\"show\": 300}' data-skin='dark' " +
                            "        class='btn btn-sm btn-default m-btn--hover-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill'>" +
                            "   " + edit_mode +
                            "</button>";
                    }

                    if (_actions.includes("btnPrint")) {
                        buttons += " " +
                            "<button onclick='printPhysicalInventory(" + row.id + ")'" +
                            "        data-toggle='m-tooltip' " +
                            "        data-original-title='Print' " +
                            "        data-placement='top' " +
                            "        data-delay='{\"show\": 300}' data-skin='dark' " +
                            "        class='btn btn-default m-btn--hover-accent btn-sm m-btn m-btn--icon m-btn--icon-only m-btn--pill'>" +
                            "   <i class='fa fa-print'></i>" +
                            "</button>";
                    }

                    if (_actions.includes("btnApproval")) {
                        buttons += " " +
                            "<div class='m-dropdown m-dropdown--inline m-dropdown--small m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push' " +
                            "     data-dropdown-toggle='click' aria-expanded='true'>" +
                            "   <button class='m-portlet__nav-link m-dropdown__toggle btn btn-sm " +
                            "                  m-btn m-btn--icon m-btn--icon-only m-btn--pill " + hover + "'" +
                            "           data-toggle='m-tooltip' " +
                            "           data-original-title='Verify or Cancel Options' " +
                            "           data-placement='top' " +
                            "           data-delay='{\"show\": 300}' data-skin='dark' " + disabled + ">" +
                            "           <i class='la la-ellipsis-v'></i>" +
                            "   </button>" +
                            "   <div class='m-dropdown__wrapper'>" +
                            "       <span class='m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust'></span>" +
                            "       <div class='m-dropdown__inner'>" +
                            "           <div class='m-dropdown__body'>" +
                            "              <div class='m-dropdown__content'>" +
                            "                  <ul class='m-nav'>" +
                            "                      <li class='m-nav__item'>" +
                            "                          <a href='javascript:void(0);' class='m-nav__link' " +
                            "                             onclick='confirmPhysicalInventory(" + row.id + ",\"" + row.ref_no + "\", 1)'>" +
                            "                              <i class='m-nav__link-icon fa fa-check'></i>" +
                            "                              <span class='m-nav__link-text'>Verify</span>" +
                            "                          </a>" +
                            "                      </li>" +
                            "                      <li class='m-nav__item'>" +
                            "                          <a href='javascript:void(0);' class='m-nav__link' " +
                            "                             onclick='confirmPhysicalInventory(" + row.id + ",\"" + row.ref_no + "\", 2)'>" +
                            "                              <i class='m-nav__link-icon fa fa-ban'></i>" +
                            "                              <span class='m-nav__link-text'>Cancel</span>" +
                            "                          </a>" +
                            "                      </li>" +
                            "                  </ul>" +
                            "              </div>" +
                            "          </div>" +
                            "      </div>" +
                            "   </div>" +
                            "</div>";
                    }

                    return buttons;
                }
            },
        ],
        order: [[3, "desc"]]
    });

function openEditPage(id) {
    window.location.assign(base_url + "inventory/edit_physical_inventory/" + id);
}

$(".clearable-search-box")
    .donetyping(function () {
        searchKey = $(this).val();
        dtPI.ajax.reload();
    });

function confirmPhysicalInventory(id, ref_no, status) {
    const title = status === 1 ? "<span class='m--font-bolder'>Verify Physical Inventory</span>" : "<span class='m--font-bolder'>Cancel Physical Inventory</span>";
    const message = status === 1 ? "<p style='font-size: 16px;'>Are you sure to mark Physical Inventory with <br/> " +
        "<span class='m--font-bolder'>Reference No: " + ref_no + "</span> as <span class='m--font-bolder text-success'>verified</span>?</p>" :
        "<p style='font-size: 16px;'>Are you sure to <span class='m--font-bolder text-danger'>cancel</span> this Physical Inventory with " +
        "<span class='m--font-bolder'>Reference No: " + ref_no + "</span>?</p>";

    modalConfirm.find("form").attr("data-id", id);
    modalConfirm.find("form").attr("data-status", status);
    modalConfirm.find("form").attr("data-ref_no", ref_no);
    const modalTitle = modalConfirm.find(".modal-title");
    const modalMessage = modalConfirm.find(".modal-body");

    modalTitle.html(title);
    modalMessage.html(message);
    modalConfirm.modal("show");
}

$("#frm-physical-inventory-confirmation")
    .on("submit", function (e) {
        e.preventDefault();

        const pi_id = $(this).attr("data-id");
        const status = $(this).attr("data-status");
        const ref_no = $(this).attr("data-ref_no");

        $.ajax({
            url: base_url + `inventory/confirm_physical_inventory/${pi_id}/${status}`,
            type: "GET",
            dataType: "JSON",
            beforeSend: function () {
                if (parseInt(status) === 1) {
                    modalConfirm.find("button[type='submit']")
                        .html("Sending Email")
                        .addClass("m-btn--custom m-loader m-loader--light m-loader--left");
                }
            },
            success: function (response) {
                if (response.success === true) {
                    modalConfirm.removeAttr("data-id");
                    modalConfirm.removeAttr("data-status");

                    if (parseInt(status) === 1) {
                        modalConfirm.find("button[type='submit']")
                            .html("Yes")
                            .removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                    }

                    let defaultMessage = "Physical inventory with <span class='m--font-boldest'>Reference No: " + ref_no + "</span> was.";
                    const msg = parseInt(status) === 1 ? {title: "Successfully verified.", text: defaultMessage + " verified."} :
                        {title: "Successfully cancelled.", text: defaultMessage + " cancelled."};

                    if (response.mail !== undefined) {
                        if (response.mail) {
                            toastr.success("<span class='m--font-boldest'>Email was successfully sent.</span>", null,
                                {timeOut: 10000, positionClass: "toast-top-right opacity-1"});
                        }
                    }

                    toastr.success(msg.text, msg.title, {timeOut: 10000, positionClass: "toast-top-right opacity-1"});
                } else {
                    toastr.error("An error occurred while updating.", "Error", {timeOut: 10000});
                }

                modalConfirm.modal("hide");
                dtPI.ajax.reload();
            },
        });
    });

function printPhysicalInventory(id) {
    window.open(`${base_url}/inventory/printable/physical_inventory/${id}`, "_blank");
}

$("#status")
    .select2({
        width: "100%"
    })
    .on("select2:select", function () {
        dtPI.ajax.reload();
    });