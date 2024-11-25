let tempSelectedItems = [];
let constSelectedItems = [];
let itemPageOffset = 0;
let itemPageSize = 20;
let dtList;
let firstLoad = false;
let tempDataList = [];
let removeList = [];
const modalConfirmPermanentRemoveItem = $("#modal-confirm-permanent-remove-item");

/*let dtSelected = $("#tbl-selected-items")
    .DataTable({
        dom: "rtlip",
        columns: [
            {data: "sku"},
            {data: "name"},
            {data: "qty"},
            {
                data: "count", render: function () {
                    return "--";
                }
            },
            {
                data: "variance", render: function () {
                    return "--";
                }
            },
        ],
        pageLength: 15
    });*/

$("#frm-scan-item")
    .on("submit", function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        const self = $(this);
        $.ajax({
            url: `${base_url}/inventory/get_inventory_item_scanned`,
            type: "POST",
            dataType: "JSON",
            data: formData,
            success: function (item) {
                if (item) {
                    const $tbody = $("#tbl-selected-items").find("tbody");
                    const idx = selectedItems.findIndex((_item) => _item.id === item.id);
                    if (idx <= -1) {
                        $tbody.append("" +
                            "<tr id='row-selected-" + item.id + "'>" +
                            "   <td>" +
                            "       " + item.sku +
                            "       <input type='hidden' value='' name='content_id[]'>" +
                            "       <input type='hidden' value='" + item.id + "' name='id[]'>" +
                            "       <input type='hidden' value='" + item.qty + "' name='qty[]'>" +
                            "   </td>" +
                            "   <td>" + item.name + "</td>" +
                            "   <td>" + item.qty + "</td>" +
                            "   <td>" +
                            "       <input type='text' class='form-control' name='count[]' " +
                            "              oninput='determineVariance(" + item.qty + "," + item.id + ", this)'" +
                            "              data-validation='required'>" +
                            "   </td>" +
                            "   <td>" +
                            "       <div id='row-selected-variance-" + item.id + "'>" +
                            "           <span class='m-badge m-badge--wide'>&#8230;</span>" +
                            "       </div>" +
                            "       <input type='hidden' class='form-control' name='variance[]'>" +
                            "   </td>" +
                            "   <td class='text-center'>" +
                            "       <button class='btn btn-sm btn-danger m-btn m-btn--icon m-btn--icon-only' " +
                            "               onclick='removeFromSelected(" + item.id + ")' type='button'>" +
                            "           <i class='fa fa-trash-o'></i>" +
                            "       </button>" +
                            "   </td>" +
                            "</tr>");

                        selectedItems.push(item);
                    }

                    setTimeout(function () {
                        self.resetForm();
                    }, 500);
                }
            }
        });
    });

$("#frm-add-item")
    .on("submit", function (e) {
        e.preventDefault();
        if (tempSelectedItems.length > 0) {
            tempSelectedItems.map((item) => {
                const $tbody = $("#tbl-selected-items").find("tbody");
                $tbody.append("" +
                    "<tr id='row-selected-" + item.id + "'>" +
                    "   <td>" +
                    "       " + item.sku +
                    "       <input type='hidden' value='' name='content_id[]'>" +
                    "       <input type='hidden' value='" + item.id + "' name='id[]'>" +
                    "       <input type='hidden' value='" + item.qty + "' name='qty[]'>" +
                    "   </td>" +
                    "   <td>" + item.name + "</td>" +
                    "   <td>" + item.qty + "</td>" +
                    "   <td>" +
                    "       <input type='text' class='form-control' name='count[]' " +
                    "              oninput='determineVariance(" + item.qty + "," + item.id + ", this)'" +
                    "              data-validation='required'>" +
                    "   </td>" +
                    "   <td>" +
                    "       <div id='row-selected-variance-" + item.id + "'>" +
                    "           <span class='m-badge m-badge--wide'>&#8230;</span>" +
                    "       </div>" +
                    "       <input type='hidden' class='form-control' name='variance[]'>" +
                    "   </td>" +
                    "   <td class='text-center'>" +
                    "       <button class='btn btn-sm btn-danger m-btn m-btn--icon m-btn--icon-only' " +
                    "               onclick='removeFromSelected(" + item.id + ")' type='button'>" +
                    "           <i class='fa fa-trash-o'></i>" +
                    "       </button>" +
                    "   </td>" +
                    "</tr>");

                /*dtSelected.row.add({
                    sku: item.sku,
                    name: item.name,
                    qty: item.qty,
                    count: 0,
                    variance: 0,
                }).draw();*/

                $tbody.find('tr:last').hide();
                $tbody.find('tr:last').fadeIn(100);

                const idx = selectedItems.findIndex((_item) => _item.id === item.id);
                if (idx <= -1) {
                    selectedItems.push(item);
                }

                $("#select-all-shown").prop("checked", false);
            });
        }
        tempSelectedItems = [];

        removeList.forEach((rm) => {
            selectedItems.splice(selectedItems.findIndex((item) => rm.id === item.id), 1);
            $("tr#row-selected-" + rm.id).remove();
        });

        removeList = [];

        $("#select2-item-list").val("").trigger("change");
        $("#add-item-modal").modal("hide");
    });

function determineVariance(qty, id, el) {
    const count = $(el).val() || 0;
    const _qty = parseFloat(qty);
    let diff = 0;

    if (_qty < 0) {
        diff = Math.abs(_qty) + parseFloat(count);
    } else {
        diff = parseFloat(count) - _qty;
    }

    if ((diff - Math.floor(diff)) !== 0) {
        diff = diff.toFixed(2);
    }
    const clsColor = diff > 0 ? "m-badge--success" : "m-badge--danger";
    const str = diff > 0 ? "Positive" : "Negative";
    $("div#row-selected-variance-" + id).html("<span class='m--font-boldest2 m-badge m-badge--wide " + clsColor + "'>" + diff + "</span>");
    $("tr#row-selected-" + id).find("input[name='variance[]']").val(diff);
}

function removeFromSelected(id) {
    const idx = selectedItems.findIndex((item) => item.id === id.toString());
    selectedItems.splice(idx, 1);

    const cb = $("tr#row-" + id).find("input[type='checkbox']");
    $(cb).prop("checked", false);
    $("tr#row-selected-" + id).remove();
}

$("#add-item-modal")
    .on("shown.bs.modal", function () {
        !firstLoad && loadItems(itemPageOffset, itemPageSize);
    })
    .on("show.bs.modal", function () {
        selectedItems.forEach((item) => {
            const el = $("tr#row-" + item.id).find("input[type='checkbox']");
            $(el).prop("checked", true);
        });
    });

function addSelectionToTemp(id, el) {
    const idxInSelection = selectedItems.findIndex((item) => item.id === id);

    $.ajax({
        url: base_url + "inventory/get_item/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            if (el.checked) {
                if (idxInSelection <= -1) {
                    tempSelectedItems.push(data);
                }
                const idxRm = removeList.findIndex((item) => item.id === id);
                if (idxInSelection >= 0) {
                    removeList.splice(idxRm, 1);
                }
            } else {
                const idx = tempSelectedItems.findIndex((item) => item.id === id);
                tempSelectedItems.splice(idx, 1);

                if (idxInSelection >= 0) {
                    removeList.push(data);
                }
            }
        }
    });
}

function loadItems(offset, size) {
    firstLoad = true;
    const selectAll = $("#select-all-shown").is(":checked");
    $.ajax({
        url: base_url + "inventory/get_item_list_infinite_scroll/" + offset + "/" + size,
        type: "POST",
        dataType: "JSON",
        data: {
            search: $("#search-item").val(),
            exclude: [] //selectedItems.length ? selectedItems.reduce((acc, item) => acc.concat(item.id), []) : null
        },
        success: function (response) {
            const $tbody = $("#tbl-item-list tbody");
            let i = 0;
            let interval = setInterval(function () {
                if (i < response.length) {
                    const inSelected = selectedItems.find((item) => item.id === response[i].id);
                    $tbody.append("" +
                        "<tr id='row-" + response[i].id + "'>" +
                        "   <td>" +
                        "       <label class='m-checkbox item-list-cb'>" +
                        "           <input data-id='" + response[i].id + "' type='checkbox' class='form-control' " + ((inSelected !== undefined) ? "checked" : "") +
                        "                  onchange='addSelectionToTemp(" + response[i].id + ", this)'>" +
                        "           <span></span>" +
                        "       </label>" +
                        "   </td>" +
                        "   <td>" +
                        "       " + response[i].sku +
                        "   </td>" +
                        "   <td>" +
                        "       " + response[i].name +
                        "   </td>" +
                        "   <td>" +
                        "       " + response[i].qty +
                        "   </td>" +
                        "</tr>");
                    $tbody.find('tr:last').hide();
                    $tbody.find('tr:last').fadeIn(100);

                    const idx = tempDataList.findIndex((item) => item.id === response[i].id);
                    if (idx <= -1) {
                        tempDataList.push(response[i]);
                    }
                    i++;
                } else {
                    clearInterval(interval);
                }
            }, 0);
        }
    });
}

function reloadAlreadyLoaded() {
    const $tbody = $("#tbl-item-list tbody");
    let i = 0;
    let interval = setInterval(function () {
        if (i < tempDataList.length) {
            const idx = tempSelectedItems.findIndex((item) => item.id === tempDataList[i].id);
            const idxSelected = selectedItems.findIndex((item) => item.id === tempDataList[i].id);
            let checked = "checked";

            if (idx <= -1 && idxSelected <= -1) {
                checked = "";
            }

            $tbody.append("" +
                "<tr>" +
                "   <td>" +
                "       <label class='m-checkbox item-list-cb'>" +
                "           <input data-id='" + tempDataList[i].id + "' type='checkbox' class='form-control' " + checked +
                "                  onchange='addSelectionToTemp(" + JSON.stringify(tempDataList[i]) + ", this)'>" +
                "           <span></span>" +
                "       </label>" +
                "   </td>" +
                "   <td>" +
                "       " + tempDataList[i].sku +
                "   </td>" +
                "   <td>" +
                "       " + tempDataList[i].name +
                "   </td>" +
                "   <td>" +
                "       " + tempDataList[i].qty +
                "   </td>" +
                "</tr>");
            $tbody.find('tr:last').hide();
            $tbody.find('tr:last').fadeIn(100);
            i++;
        } else {
            clearInterval(interval);
        }
    }, 0);
}

$("#search-item")
    .donetyping(function () {
        $("#tbl-item-list tbody").empty();
        $("#select-all-shown").prop("checked", false);

        if ($(this).val()) {
            itemPageOffset = 0;
            loadItems(itemPageOffset, itemPageSize);
        } else {
            reloadAlreadyLoaded();
        }
    });

$("#table-container")
    .on("scroll", function () {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            itemPageOffset += 20;
            setTimeout(() => {
                loadItems(itemPageOffset, itemPageSize);
            }, 200);
        }
    });


$(".clearable-search-box")
    .on("input", function () {
        $(this).val() ? $(".clear-search").css("visibility", "visible") : $(".clear-search").css("visibility", "hidden");
    });


$(".clear-search")
    .on("click", function () {
        $(".clearable-search-box").val("");
        $(".clear-search").css("visibility", "hidden");
        itemPageOffset = 0;
        $("#tbl-item-list tbody").empty();

        if ($("#search-item").val()) {
            itemPageOffset = 0;
            loadItems(itemPageOffset, itemPageSize);
        } else {
            reloadAlreadyLoaded();
        }
    });

$("#select-all-shown")
    .on("change", function (e) {
        const checked = e.target.checked;
        const c_boxes = $("#tbl-item-list tbody").find("input[type='checkbox']");

        if (checked) {
            const cb = $("#tbl-item-list tbody").find("input[type='checkbox']:not(':checked')");
            const tr = $("#tbl-item-list tbody")
                .find("input[type='checkbox']:not(':checked')")
                .closest("tr");
            $.each(tr, function (index, row) {
                const id = $(row).find("td:eq(0) input").attr("data-id");
                const sku = $(row).find("td:eq(1)").text();
                const name = $(row).find("td:eq(2)").text();
                const qty = $(row).find("td:eq(3)").text();

                const idxSelected = selectedItems.findIndex((item) => item.id === id);
                if (idxSelected <= -1) {
                    tempSelectedItems.push({id, sku: sku.trim(), name: name.trim(), qty: qty.trim()});
                }
            });

            cb.prop('checked', checked);
        } else {
            const tr = $("#tbl-item-list tbody").find("input[type='checkbox']:checked").closest("tr");
            $.each(tr, function (index, row) {
                const id = $(row).find("td:eq(0) input").attr("data-id");
                const cb = $(row).find("td:eq(0) input[type='checkbox']");
                const idx = tempSelectedItems.findIndex((item) => item.id === id);
                tempSelectedItems.splice(idx, 1);

                const idxSelected = selectedItems.findIndex((item) => item.id === id);
                if (idxSelected <= -1) {
                    cb.prop('checked', false);
                }
            });
        }
    });

$("#tbl-item-list tbody")
    .on("click", "input[type='checkbox']", function () {
        const tbody = $("#tbl-item-list tbody");
        const c_boxesNotChecked = tbody.find("input[type='checkbox']").not(':checked');
        $("#select-all-shown").prop('checked', (c_boxesNotChecked.length <= 0));
    });


$.validate({
    form: '#frm-edit-physical-inventory',
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (form) {
        const data = $(form).serialize();
        $.ajax({
            url: base_url + "inventory/update_physical_inventory",
            dataType: "JSON",
            type: "POST",
            data,
            beforeSend: function () {
                const btn = $(form).find("button[type='submit']");
                $(btn).addClass("m-btn--custom m-loader m-loader--light m-loader--left");
                $(btn).attr("disabled", true);
            },
            success: function (response) {
                const btn = $(form).find("button[type='submit']");
                $(btn).removeClass("m-btn--custom m-loader m-loader--light m-loader--left");

                if (response === true) {
                    toastr.success("Physical inventory successfully updated.", "Successfully updated.", {timeOut: 10000});
                } else {
                    toastr.error("An error occurred while saving.", "Save Error.", {timeOut: 10000});
                }

                setTimeout(() => {
                    window.location.assign(base_url + 'inventory/physical_inventory');
                }, 1300);
            }
        });
        return false;
    }
});

function deleteContentPermanently(content_id, item_name, item_id) {
    const modalBody = modalConfirmPermanentRemoveItem.find(".modal-body");
    $("#frm-confirm-permanent-remove-item").attr("data-id", content_id);
    $("#frm-confirm-permanent-remove-item").attr("data-item-id", item_id);
    modalBody.empty()
        .append("" +
            "<p class='m--regular-font-size-lg1'>Are you sure to remove <span class='m--font-bolder'>" + item_name + "</span>?</p>" +
            "<br/>" +
            "<p>Note: Item will be removed also from database.</p>");
    modalConfirmPermanentRemoveItem.modal("show");
}

$("#frm-confirm-permanent-remove-item")
    .on("submit", function (e) {
        e.preventDefault();

        const content_id = $(this).attr("data-id");
        const id = $(this).attr("data-item-id");

        $.ajax({
            url: base_url + "inventory/delete_physical_inventory_content/" + content_id,
            type: "GET",
            dataType: "JSON",
            success: function (response) {
                if (response) {
                    const idx = selectedItems.findIndex((item) => parseInt(item.content_id) === parseInt(content_id));
                    if (idx >= 0) {
                        selectedItems.splice(idx, 1);
                    }

                    $("#row-selected-" + id).remove();
                    modalConfirmPermanentRemoveItem.modal("hide");
                    toastr.success("Item successfully removed from list.", "Removed Successfully", {timeOut: 10000});
                } else {
                    toastr.error("An error occurred while removing.", "Error", {timeOut: 10000});
                }
            }
        });
    });

$('#scan-item-modal')
    .on('shown.bs.modal', function () {
        $('input[type="text"]', this).focus();
    });