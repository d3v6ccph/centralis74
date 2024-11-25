const editEmailSettingModal = $("#edit-email-setting-modal");
const addEmailSettingModal = $("#add-email-setting-modal");
const archiveConfirmationModal = $("#modal-archive-confirmation");
const archivedListModal = $("#archived-list-modal");

let dtArchived;

$('body').tooltip({
    selector: '[data-toggle="m-tooltip"]'
});

$(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $('.modal:visible').length);
    $(this).css('z-index', zIndex);
    setTimeout(function () {
        $('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

$(document).on('hidden.bs.modal', '.modal', function () {
    $('.modal:visible').length && $(document.body).addClass('modal-open');
});

let dtEmailSettings = $("#tbl-email-settings")
    .DataTable({
        dom: "rtlp",
        autoWidth: false,
        serverSide: true,
        processing: true,
        ajax: {
            url: `${base_url}configuration/email_settings/get_email_settings`,
            type: "POST",
            dataType: "JSON",
            data: function (d) {
                d.search.value = $("#search-email-setting").val();
            }
        },
        columns: [
            {data: "label", width: "20%"},
            {data: "to", width: "24%",},
            {data: "cc", width: "24%"},
            {data: "bcc", width: "24%"},
            {
                data: null,
                width: "8%",
                orderable: false,
                defaultContent: "---",
                className: "text-center",
                render: function (data, type, row) {
                    let buttons = "";
                    if (_actions.includes("btnEdit")) {
                        buttons += `<button data-toggle="m-tooltip" data-original-title="Edit" data-skin="dark"
                                            class="btn btn-sm btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-accent"
                                            onclick='openEditEmailSettingModal(${JSON.stringify(row)})'>
                                        <i class="fa fa-edit"></i>
                                    </button> `;
                    }

                    if (_actions.includes("btnArchive")) {
                        buttons += `<button data-toggle="m-tooltip" data-original-title="Archive" data-skin="dark"
                                            class="btn btn-sm btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-warning"
                                            onclick="openArchiveConfirmation(${row.id}, '${row.label}')">
                                        <i class="la la-archive"></i>
                                    </button>`;
                    }

                    return buttons;
                }
            },
        ],
        columnDefs: [
            {
                targets: [1, 2, 3], render: function (data) {
                    let template = '';
                    if (!data) {
                        return "---";
                    }

                    const emails = data.split(",");
                    emails.forEach((email) => {
                        template += `<span class="m-badge m-badge--wide m--font-boldest mb-2 mr-1" style="text-transform: none;">${email}</span>`;
                    });

                    return template;
                }
            }
        ],
    });

function openArchiveConfirmation(id, str, status = 1) {
    archiveConfirmationModal.find(".modal-title").html((status === 1 ? "Archive Confirmation" : "Restore Confirmation"));
    archiveConfirmationModal.find(".modal-body p").html(`Are you sure to ${(status === 1 ? "archive" : "restore")} configuration for <span class="m--font-boldest">${str}</span>?`);
    archiveConfirmationModal.find("form").attr("action", `${base_url}configuration/email_settings/archive_email_setting/${id}/${status}`);
    archiveConfirmationModal.modal("show");
}

function openEditEmailSettingModal(setting) {
    $("#label", editEmailSettingModal).val(setting.label);
    $("#to", editEmailSettingModal).val(setting.to);
    $("#cc", editEmailSettingModal).val(setting.cc);
    $("#bcc", editEmailSettingModal).val(setting.bcc);
    $("#id", editEmailSettingModal).val(setting.id);
    editEmailSettingModal.modal("show");
}

$.validate({
    form: '#frm-edit-email-setting-modal',
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (_form) {
        const form = $(_form);
        const data = form.serializeArray();
        const _btnSubmit = form.find("button[type='submit']");

        $.ajax({
            url: `${base_url}configuration/email_settings/update_email_setting`,
            type: "POST",
            dataType: "json",
            data,
            beforeSend: function () {
                _btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                toastr[toast](response.message, response.title, {timeOut: 10000});

                dtEmailSettings.ajax.reload();
                editEmailSettingModal.modal("hide");
                _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
            }
        });

        return false;
    }
});

$.validate({
    form: '#frm-add-email-setting-modal',
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (_form) {
        const form = $(_form);
        const data = form.serializeArray();
        const _btnSubmit = form.find("button[type='submit']");

        $.ajax({
            url: `${base_url}configuration/email_settings/create_email_setting`,
            type: "POST",
            dataType: "json",
            data,
            beforeSend: function () {
                _btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                toastr[toast](response.message, response.title, {timeOut: 10000});

                dtEmailSettings.ajax.reload();
                addEmailSettingModal.modal("hide");
                _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
            }
        });

        return false;
    }
});

$.validate({
    form: '#frm-archive-confirmation',
    lang: 'en',
    scrollToTopOnError: false,
    onSuccess: function (_form) {
        const form = $(_form);
        const url = form.attr("action");
        const _btnSubmit = form.find("button[type='submit']");
        const status = url.split("/").pop();

        $.ajax({
            url,
            type: "GET",
            dataType: "json",
            beforeSend: function () {
                _btnSubmit.addClass("m-btn--custom m-loader m-loader--light m-loader--right");
            },
            success: function (response) {
                const toast = response.success ? "success" : "error";
                toastr[toast](response.message, response.title, {timeOut: 10000});

                if (parseInt(status) === 0) {
                    dtArchived.ajax.reload();
                    dtEmailSettings.ajax.reload();
                } else {
                    dtEmailSettings.ajax.reload();
                }
                archiveConfirmationModal.modal("hide");
                _btnSubmit.removeClass("m-btn--custom m-loader m-loader--light m-loader--right");
            }
        });

        return false;
    }
});

$("#search-email-setting")
    .donetyping(function () {
        dtEmailSettings.ajax.reload();
    });

archivedListModal
    .on("show.bs.modal", function () {
        dtArchived = $("#tbl-archived-email-settings").DataTable({
            dom: "frtlp",
            ajax: {
                url: `${base_url}configuration/email_settings/get_archived_email_settings`,
                type: "POST",
                dataType: "JSON"
            },
            serverSide: true,
            processing: true,
            autoWidth: false,
            destroy: true,
            columns: [
                {data: "label"},
                {data: "to"},
                {data: "cc"},
                {data: "bcc"},
                {
                    data: null,
                    width: "8%",
                    orderable: false,
                    defaultContent: "---",
                    className: "text-center",
                    render: function (data, type, row) {
                        let buttons = "";

                        if (_actions.includes("btnArchive")) {
                            buttons += `<button data-toggle="m-tooltip" data-original-title="Revert"
                                            class="btn btn-sm btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill m-btn--hover-warning"
                                            onclick="openArchiveConfirmation(${row.id}, '${row.label}', 0)">
                                        <i class="la la-undo"></i>
                                    </button>`;
                        }

                        return buttons;
                    }
                },
            ],
            columnDefs: [
                {
                    targets: [1, 2, 3], render: function (data) {
                        let template = '';
                        if (!data) {
                            return "---";
                        }

                        const emails = data.split(",");
                        emails.forEach((email) => {
                            template += `<span class="m-badge m-badge--wide m--font-boldest mb-2 mr-1" style="text-transform: none;">${email}</span>`;
                        });

                        return template;
                    }
                }
            ],
            initComplete: function () {
                let timer = 0;
                $('#tbl-archived-email-settings_filter input')
                    .unbind('.DT')
                    .bind('keyup.DT', function (e) {
                        const value = this.value;
                        clearTimeout(timer);
                        timer = setTimeout(function () {
                            dtArchived.search(value).draw();
                        }, 400);
                    });
            }
        });
    });