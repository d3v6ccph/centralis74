<?php
    $sortFields = array(
        array("id" => "items.name", "text" => "Item Name"),
        array("id" => "priority.name", "text" => "Priority"),
        array("id" => "items.critical_level_percentage", "text" => "Critical Level %"),
    ); ?>

<div class="modal fade" tabindex="-1" role="dialog"
     data-backdrop="static" data-keyboard="false"
     id="inventory-filter-modal">
    <form action="" id="frm-inventory-filter">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Inventory Advance Filter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Select Filters</p>
                    <div class="form-group">
                        <label for="">Priority</label>
                        <select name="priority" id="" class="form-control">
                            <option></option>
                            <?php foreach ($priority as $item) { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group pt-4">
                        <label for="">Category</label>
                        <select name="category" id="" class="form-control">
                            <option></option>
                            <?php foreach ($category as $item) { ?>
                                <option value="<?= $item->id ?>"><?= $item->name ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div id="sort-container" class="mt-5">
                        <div class="row m-row--no-padding main-sort-field">
                            <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 pr-2">
                                <div class="form-group">
                                    <label for="">Sort By</label>
                                    <select name="field[]" class="form-control sort-field">
                                    </select>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 px-2">
                                <div class="form-group">
                                    <label for="" class="invisible">Order</label>
                                    <select name="sort_order[]" id="" class="form-control sort-order">
                                        <option value="ASC">ASC</option>
                                        <option value="DESC">DESC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-2 mb-4"/>
                    <div class="row">
                        <div class="col-12">
                            <button class="btn btn-default btn-sm m-btn--custom m-btn btnAdvance_filter"
                                    id="btnAddSortField"
                                    onclick="addSortField()"
                                    type="button"
                                    style="text-transform: none;">
                                <span class="text-success m--font-bolder">Add another sort column</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btnNew">Filter!</button>
                    <button type="button" class="btn btn-accent btnNew" onclick="clearFilter()">Clear Filter</button>
                    <button type="button" class="btn btn-danger btnNew" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const form = $("#frm-inventory-filter");
    const graph = form.attr("data-graph");
    const sortFields = <?php echo json_encode($sortFields)?>;
    let sortFieldCtr = 0;

    $("select[name='priority']")
        .select2({
            placeholder: "Select a Priority",
            width: "100%",
            dropdownParent: $(".modal"),
            allowClear: true,
        });

    $("select[name='category']")
        .select2({
            placeholder: "Select a Category",
            width: "100%",
            dropdownParent: $(".modal"),
            allowClear: true,
        });

    initSortFieldSelect2();

    function initSortFieldSelect2() {
        $(".sort-field")
            .select2({
                data: sortFields,
                placeholder: "Select a Field",
                width: "100%",
                dropdownParent: $("#inventory-filter-modal")
            });
    }

    initSortOrderSelect2();

    function initSortOrderSelect2() {
        $(".sort-order")
            .select2({
                placeholder: "Sort Order",
                width: "100%",
                dropdownParent: $("#inventory-filter-modal")
            });
    }

    function clearFilter() {
        $("select[name='priority']").val('').trigger('change');
        $("select[name='category']").val('').trigger('change');
        $("select[name='name']").val('items.name').trigger('change');
        $("select[name='sort_order']").val('ASC').trigger('change');
        $("#sort-container > .row:not('.main-sort-field')").remove();
        sortFieldCtr = 0;

        const graph = form.attr("data-graph");
        if (graph === 'stock storage life') {
            temp_filter_inv_stock_storage_life = filter_inv_stock_storage_life; // store old filter
            filter_inv_stock_storage_life = {priority: "", category: ""};

            temp_sort_inv_stock_storage_life = sort_inv_stock_storage_life;
            sort_inv_stock_storage_life = [{field: "priority.name", order: "ASC"}];
        } else if (graph === 'nearing min qty') {
            temp_filter_inv_nearing_min_qty = filter_inv_nearing_min_qty;
            filter_inv_nearing_min_qty = {priority: "", category: ""};

            temp_sort_inv_nearing_min_qty = sort_inv_nearing_min_qty;
            sort_inv_nearing_min_qty = [{field: "priority.name", order: "ASC"}];
        }

        $("#inventory-filter-modal").modal("hide");
    }

    function addSortField(populateSelected = false, field = null, order = null) {
        sortFieldCtr++;

        $("#sort-container")
            .append('' +
                '<div class="row m-row--no-padding" id="row-' + sortFieldCtr + '">' +
                '   <div class="col-xl-7 col-lg-7 col-md-7 col-sm-12 pr-2">' +
                '       <div class="form-group">' +
                '           <label for="">Then by</label>' +
                '           <select name="field[]" class="form-control sort-field">' +
                '           </select>' +
                '       </div>' +
                '   </div>' +
                '   <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 px-2">' +
                '       <div class="form-group">' +
                '           <label for="" class="invisible">Order</label>' +
                '           <select name="sort_order[]" id="" class="form-control sort-order">' +
                '               <option value="ASC">ASC</option>' +
                '               <option value="DESC">DESC</option>' +
                '           </select>' +
                '       </div>' +
                '   </div>' +
                '   <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 d-flex flex-column justify-content-center align-items-end">' +
                '       <button type="button" class="btn btn-default m-btn m-btn--icon m-btn--icon-only m-btn--pill mt-3"' +
                '           onclick="removeSortField(' + sortFieldCtr + ')">' +
                '           <i class="fa fa-trash-o"></i>' +
                '       </button>' +
                '   </div>' +
                '</div>');

        initSortFieldSelect2();
        initSortOrderSelect2();

        const previousSortField = $(".sort-field")[sortFieldCtr - 1];
        const previousValue = $(previousSortField).val();
        const previousValueIndexFromList = sortFields.findIndex((item) => item.id === previousValue);

        const currentSortEl = $(".sort-field")[sortFieldCtr];
        const currentOrderEl = $(".sort-order")[sortFieldCtr];

        if (!populateSelected) {
            if (sortFieldCtr >= sortFields.length) {
                $(currentSortEl).prop('selectedIndex', 0).change();
            } else {
                $(currentSortEl).prop('selectedIndex', (previousValueIndexFromList + 1)).change();
            }
        } else {
            $(currentSortEl).val(field).trigger('change');
            $(currentOrderEl).val(order).trigger('change');
        }
    }

    function removeSortField(index) {
        sortFieldCtr--;
        $("#row-" + index).remove();
    }
</script>