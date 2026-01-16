<?php $actions = $this->core_layout->getCurrentActions(); ?>
<style>
    #filter, #sku_data {
        padding: 30px;
        background: #fff;
        border-radius: 10px;
        border: 1px solid #d1d1d1;
    }

    .table-header {
        background: #f4f5f8;
        padding: 20px 0;
        border-radius: 10px;
        margin: 0 0 10px;
    }

    .col.qty {
        max-width: 11%;
    }

    #sku_data * {
        text-transform: uppercase;
        font-size: 10px;
        letter-spacing: 0.5px;
    }

    /* Accordion Item */
    #sku_data .accord_item:not(:last-child) {
        margin: 0 0 10px 0;
    }

    /* Accordion Head */
    #skusAccordion .accord_item-head {
        background: #4895ef;
        position: relative;
        transition: .3s ease-in-out;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        min-height: 52px;
        display: flex;
        align-items: center;
    }

    #skusAccordion .accord_item-head .accord_item-icon i { /** Open accord */
        rotate: 0deg;
    }

    #skusAccordion .accord_item-head.collapsed .accord_item-icon i { /** Close accord */
        rotate: -90deg;
    }

    #skusAccordion .accord_item-head span i {
        color: #fff;
        transition: .3s ease-in-out;
    }

    #skusAccordion .accord_item-head.collapsed {
        background: #f4f5f8;
        border-radius: 5px;
        position: relative;
    }

    #skusAccordion .accord_item-icon {
        position: absolute;
        margin: auto;
        top: 0;
        bottom: 0;
        max-height: max-content;
    }

    #skusAccordion .accord_item-head.collapsed div, #skusAccordion .accord_item-head.collapsed span i {
        color: #737373;
    }

    #skusAccordion .accord_item-head div {
        color: #fff;
        font-weight: 500;
    }

    .accord_item-title {
        flex-grow: 1;
        max-width: 100%;
    }

    /* Accordion Body */
    .accord_item-body {
        background: #fff;
        border: 1px solid #4895ef;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        overflow: hidden;
    }
    .accord_item-content .warehouse-data:nth-child(odd) {
        background: #f4f5f8;
    }

    .warehouse-data div.col {
        color: #737373;
        font-weight: 400;
    }

    #warehouse-tbl-header {
        padding: 15px;
        background: #f4f5f8;
    }

    #sku_data .table-body {
        max-height: 600px;
        overflow-y: auto;
    }
</style>

<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile mb-4">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Masterfile - Items
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <div class="row align-items-center justify-content-end mx-0">
                            <div class="text-danger mr-3 m--font-boldest">
                                Last Sync Date: <span id="last_sync_date"><span>
                            </div>    
                        
                            <button class="btn btn-success" id="btnSync">
                                <span>
                                    <i class="la la-download"></i>
                                    <span>Sync Item</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-3">
                    <div id="filter">
                        <div class="form-group mb-3">
                            <label class="mb-0"><strong>FILTER BY:</strong></label>
                        </div>

                        <div class="form-group mb-3">
                            <select id="select-warehouse" class="form-control" multiple></select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <select id="select-items" class="form-control" multiple></select>
                        </div>

                        <button class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="searchItem()">
                            <span>
                                <i class="la la-search"></i>
                                <span>Search</span>
                            </span>
                        </button>

                        <button class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill text-white" onclick="reset()">
                            <span>
                                <i class="flaticon-refresh"></i>
                                <span>Clear</span>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="col-9">
                    <div id="sku_data">
                        <div class="row justify-content-between align-items-center mb-3 mx-0">
                            <button class="btn btn-success ml-auto d-block" @click="exportExcel">Export to Excel</button>
                        </div>

                        <div class="table-header">
                            <div class="row align-items-center justify-content-between mx-0">
                                <div class="col">
                                    <h6 class="text-center mb-0">Stock Code</h6>
                                </div>

                                <div class="col">
                                    <h6 class="text-center mb-0">Inventory Code</h6>
                                </div>

                                <div class="col">
                                    <h6 class="text-center mb-0">Item Description</h6>
                                </div>

                                <div class="col">
                                    <h6 class="text-center mb-0">Category</h6>
                                </div>

                                <div class="col qty">
                                    <h6 class="text-center mb-0">Total In</h6>
                                </div>

                                <div class="col qty">
                                    <h6 class="text-center mb-0">Total Out</h6>
                                </div>

                                <div class="col qty">
                                    <h6 class="text-center mb-0">Total Balance</h6>
                                </div>
                            </div>
                        </div>

                        <div class="table-body">
                            <div v-if="skus_data.length" id="skusAccordion" class="m-accordion m-accordion--default m-accordion--solid m-accordion--section m-accordion--toggle-arrow" role="tablist">
                                <div v-for="(item, index) in skus_data" :key="`acc-${index}`" class="accord_item">
                                    <div :id="`ledger_head_${index}`" :href="`#ledger_body_${index}`" class="accord_item-head py-2 collapsed" role="tab" data-toggle="collapse" aria-expanded="false">
                                        <span class="accord_item-icon ml-4"><i class="la la-angle-down"></i></span>
                                        <span class="accord_item-title">
                                            <div class="row align-items-center w-100 mx-0">
                                                <div class="col text-center">{{ item.sku }}</div>
                                                <div class="col text-center">{{ item.inventory_sku }}</div>
                                                <div class="col text-center">{{ item.name }}</div>
                                                <div class="col text-center">{{ item.category_name }}</div>
                                                <div class="col qty text-right">{{ item.total_qty_in }}</div>
                                                <div class="col qty text-right">{{ item.total_qty_out }}</div>
                                                <div class="col qty text-right">{{ item.total_balance }}</div>
                                            </div>
                                        </span>
                                    </div>

                                    <div :id="`ledger_body_${index}`" class="accord_item-body collapse" role="tabpanel" data-parent="#skusAccordion">
                                        <div class="accord_item-content">
                                            <div id="warehouse-tbl-header" class="row align-items-center justify-content-between mx-0">
                                                <div class="col">
                                                    <h6 class="text-center mb-0">Warehouse Name</h6>
                                                </div>

                                                <div class="col">
                                                    <h6 class="text-center mb-0">In</h6>
                                                </div>

                                                <div class="col">
                                                    <h6 class="text-center mb-0">Out</h6>
                                                </div>

                                                <div class="col">
                                                    <h6 class="text-center mb-0">Running Balance</h6>
                                                </div>
                                            </div>
                                            
                                            <template v-if="item.warehouses && item.warehouses.length">
                                                <div v-for="(wh, whIndex) in item.warehouses" :key="`wh-${index}-${whIndex}`" class="warehouse-data py-3 row mx-0 align-items-center justify-content-between">
                                                    <div class="col text-center">{{ wh.warehouse }}</div>
                                                    <div class="col text-center">{{ wh.qty_in }}</div>
                                                    <div class="col text-center">{{ wh.qty_out }}</div>
                                                    <div class="col text-center">{{ wh.running_bal }}</div>
                                                </div>
                                            </template>

                                            <template v-else>
                                                <div class="alert m-alert--default mb-0" role="alert">
                                                    <p class="text-center text-muted mb-0" style="font-weight: 600;">No warehouse available</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <template v-else>
                                <div class="alert m-alert--default mb-0" role="alert">
                                    <p class="text-center text-muted mb-0" style="font-weight: 600;">No item selected</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var _currentActions = <?php echo json_encode($actions); ?>;
    var _csrf_token = "<?php echo $this->security->get_csrf_token_name(); ?>";
    var _csrf_hash = "<?php echo $this->security->get_csrf_hash(); ?>";
    let _sku = null;

    $(document).ready( function(){
        function get_last_sync_date(){
            $.ajax({
                url: '<?=base_url('items/get_last_sync_date') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    _csrf_token: _csrf_hash,
                },
                success: function(response){
                    if(response.state){
                        $("#last_sync_date").text( moment(response.last_sync_date).format('MMM DD, YYYY hh:mm a'));
                    }else{
                        $("#last_sync_date").text('No sync yet');
                    }
                }
            });
        }

        $("#select-warehouse").select2({
            placeholder: "Select Warehouse (Optional)",
            width: "100%",
            ajax: {
                url: `<?=base_url('items/get_all_warehouses') ?>`,
                dataType: "JSON ",
                delay: 500,
                type: "GET"
            }
        }).on('select2:select', function(e){
            var data = e.params.data;

            items(data.id)
        });

        items();
        get_last_sync_date();
    });

    function items(id = 0, select2Destroy = false){
        var url = "";

        if(select2Destroy){ currentTarget.empty(); }

        if(id){
            url = `<?=base_url('items/get_all_items') ?>/${id}`;
        }else{
            url = `<?=base_url('items/get_all_items') ?>`;
        }

        $("#select-items").select2({
            placeholder: "Select an item",
            width: "100%",
            ajax: {
                url: url,
                dataType: "JSON",
                delay: 500,
                type: "GET"
            },
        });
    }

    function searchItem(){
        var sku = $("#select-items").val();
        var warehouse = $("#select-warehouse").val();

        if((typeof sku != 'undefined' && sku) || (typeof warehouse != 'undefined' && warehouse)){

            $.ajax({
                url: '<?=base_url('items/get_item') ?>',
                type: 'POST',
                dataType: 'JSON',
                data: {
                    sku: sku,
                    warehouse: warehouse,
                    _csrf_token: _csrf_hash,
                },
                success: function(response){
                    vm_skus.skus_data = response.data;
                    vm_skus.data_for_export = response.data_for_export;

                    if(response.data.length > 0){
                        $("#export-excel").prop('disabled', false);
                        $("#export-pdf").prop('disabled', false);
                    }else{
                        $("#export-excel").prop('disabled', true);
                        $("#export-pdf").prop('disabled', true);
                    }
                }
            });
        }else{
            toastr.error('Please select an item or a warehouse', 'Generate Report');
        }
    }

    function reset(){
        $("#select-items").val("").trigger('change');
        $("#select-warehouse").val("").trigger('change');

        items();
    }

    $(document).on('click', "#btnSync", function(){
        const warehouses = [];
        
        $.ajax({
            url: '<?= base_url('warehouse/get_all_warehouse') ?>',
            type: 'POST',
            dataType: 'JSON',
            data: {
                _csrf_token: _csrf_hash
            },
            global: false,
            success: function(res) {

                if (!res.length) {
                    toastr.warning('No warehouses found', 'Sync');
                    return;
                }

                res.forEach(wh => {
                    warehouses.push({
                        id: wh.id,
                        name: wh.name
                    });
                });

                let total = warehouses.length;
                let completed = 0;

                // SHOW LOADER
                Swal.fire({
                    title: 'Syncing Warehouses',
                    html: `
                        <div id="sync-progress">
                            <strong>0 / ${total}</strong><br>
                            Please wait while data is being synchronized...
                        </div>
                    `,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // =================================================

                let time = 0;
                
                warehouses.forEach(warehouse => {
                    setTimeout(() => {
                        $.ajax({
                            url: '<?=base_url('warehouse/check_available') ?>',
                            type: 'POST',
                            dataType: 'JSON',
                            data: {
                                _csrf_token: _csrf_hash,
                                id: warehouse.id
                            },
                            global: false,
                            success: function(response) {
                                if(response.state) {
                                    toastr.success(`Warehouse "${warehouse.name.toUpperCase()}" Available for Sync`, 'Sync', 8000);

                                    $.ajax({
                                        url: '<?=base_url('warehouse/sync_warehouse_data') ?>/' + warehouse.id,
                                        type: 'GET',
                                        dataType: 'JSON',
                                        global: false,
                                        success: function(data) {
                                            if(data.state) {
                                                toastr.success(data.msg, 'Sync');
                                            } else {
                                                toastr.error(data.msg, 'Sync');
                                            }

                                            done();
                                        }, 
                                    });
                                } else {
                                    toastr.error(`Warehouse "${warehouse.name.toUpperCase()}" is unavailable for syncing data.`, 'Sync', 8000);
                                    done();
                                }
                            }
                        });
                    }, time);

                    time += 300;
                });

                function done() {
                    completed++;

                    $('#sync-progress strong').text(`${completed} / ${total}`);

                    if (completed === total) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sync Complete',
                            text: 'All warehouses have finished syncing.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            }
        });
    });

    const vm_skus = new Vue({
        el: "#sku_data",
        data: {
            skus_data: [],
            data_for_export: []
        },
        methods: {
            exportExcel() {
                if (!this.data_for_export.length) {
                    alert('No data to export');
                    return;
                }

                // Collect all unique warehouses
                let warehouseSet = new Set();
                this.data_for_export.forEach(item => {
                    item.warehouses.forEach(w => {
                        warehouseSet.add(w.warehouse);
                    });
                });

                let warehouseColumns = Array.from(warehouseSet);

                // Build header row
                let headers = [
                    'SKU',
                    'Item Name',
                    'Inventory SKU',
                    ...warehouseColumns,
                    'Total Balance'
                ];

                // Build rows
                let rows = this.data_for_export.map(item => {
                    let row = {
                        'SKU': item.sku,
                        'Item Name': item.name,
                        'Inventory SKU': item.inventory_sku ?? ''
                    };

                    // initialize warehouse columns with 0
                    warehouseColumns.forEach(w => {
                        row[w] = 0;
                    });

                    // assign running balances
                    item.warehouses.forEach(w => {
                        row[w.warehouse] = parseFloat(
                            String(w.running_bal).replace(/,/g, '')
                        );
                    });

                    row['Total Balance'] = item.total;

                    return row;
                });

                // Convert to worksheet
                let worksheet = XLSX.utils.json_to_sheet(rows, { header: headers });

                // Create workbook
                let workbook = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(workbook, worksheet, 'Inventory');

                let filename = `inventory_export_${moment().format('YYYY-MM-DD_HHmm')}.xlsx`;

                // Export
                XLSX.writeFile(workbook, filename);
            }
        }
    });
</script>