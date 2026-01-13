<?php $actions = $this->core_layout->getCurrentActions(); ?>
<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text">
                                Masterfile - Items
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools"></div>
                </div>
                <div class="m-portlet__body">
                    <div class="row align-items-end mb-4">
                        <div class="col-md-7">
                            <div class="row align-items-end">
                                <div class="col-md-4 col-sm-12">
                                    <label for=""><strong>FILTER BY:</strong></label>
                                    <select id="select-warehouse" class="form-control"></select>
                                </div>
                                <div class="col-md-4 col-sm-12 pl-0">
                                    <select id="select-items" class="form-control" multiple></select>
                                </div>
                                <div class="col-md-4 col-sm-12 pl-0">
                                    <button class="btn btn-info m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" onclick="searchItem()">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>
                                                Search
                                            </span>
                                        </span>
                                    </button>

                                    <button class="btn btn-warning m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill text-white" onclick="reset()">
                                        <span>
                                            <i class="flaticon-refresh"></i>
                                            <span>
                                                Clear
                                            </span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="d-flex align-items-center justify-content-end" style="gap: 5px">
                                <button id="export-excel" class="btn btnNew btn-success m-btn m-btn--custom m-btn--icon m-btn--air" disabled>EXCEL</button>
                                <button id="export-pdf" class="btn btnNew btn-success m-btn m-btn--custom m-btn--icon m-btn--air" disabled>PDF</button>
                            </div>
                        </div>
                    </div>

                    <div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
                        <table class="table table-striped table-bordered" id="table-items" width="100%">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
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
    var table;
    let _sku = null;

    $(document).ready( function(){
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

    table = $("#table-items").DataTable({
        dom: "rtlip",
        data: [],
        columns: [
            { title: "<input type='checkbox' id='selectAll'>", data: null, },
            { title: '', data: null, width: '5%', orderable: false,
                render: function(data, type, row, meta){
                    var html = '';

                    if(row.image){
                        html += `<a class='image-link1' href='${row.image_path}' data-lightbox='${row.image}'>`;
                            html += `<img class='image' src='${row.image_path}' alt='${row.image}' width='50px'/>`;
                        html += '</a>';
                    }else{
                        var img = "<?=base_url('uploads/images/no image.png') ?>";

                        html += `<a class='image-link2' href='${img}' data-lightbox='no image.png'>`;
                            html += `<img class='image' src='${img}' alt='no image.png' width='50px' style="border-radius: 50%" />`;
                        html += '</a>';
                    }

                    return html;
                }
            },
            { title: 'Warehouse', data: 'warehouse', width: '10%',
                render: function(data){
                    return `<strong>${ data.toUpperCase() }</strong>`;
                }
            },
            { title: 'Stock Code', data: 'sku', width: '10%',
                render: function(data){
                    return data.toUpperCase();
                }
            },
            { title: 'Inventory Code', data: 'inventory_sku', width: '10%',
                render: function(data){
                    return data ? data : ' --- ';
                }
            },
            { title: 'Item Description', data: 'name', width: '30%',
                render: function(data){
                    return data.toUpperCase();
                }
            },
            { title: 'Category', data: 'category_name', width: '10%',
                render: function(data){
                    return data ? data.toUpperCase() : ' --- ';
                }
            },
            { title: 'In', data: 'qty_in', width: '5%' },
            { title: 'Out', data: 'qty_out', width: '5%' },
            { title: 'Running Balance', data: 'running_bal', width: '13%' },
        ],
        order: [],
        columnDefs: [
            {
                targets: 0,
                defaultContent: '', 
                className: "select-checkbox", 
                width: '2%', 
                orderable: false, 
                searchable: false,
            }
        ],
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        buttons:[
            { 
                extend: "pdfHtml5",
                title: `Central Inventory - Generated Item as of ` + moment().format('LL'),
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [2,3,4,5,6,7,8,9]
                },
                // customize: function (win) {
                //     var tblBody = win.content[1].table.body;

                //     for (i = 1; i < tblBody.length; i++) {
                //         win.content[1].table.body[i][0].alignment = 'center';
                //         win.content[1].table.body[i][1].alignment = 'center';
                //         win.content[1].table.body[i][2].alignment = 'center';
                //         win.content[1].table.body[i][3].alignment = 'center';
                //         win.content[1].table.body[i][4].alignment = 'center';
                //         win.content[1].table.body[i][5].alignment = 'center';
                //         win.content[1].table.body[i][6].alignment = 'center';
                //         win.content[1].table.body[i][7].alignment = 'center';
                //     }
                // }
                customize: function(doc) {
                    // Set dynamic widths for all columns
                    let columnWidths = new Array(doc.content[1].table.body[0].length).fill('*');

                    // Define custom widths for specific columns (adjust index as needed)
                    columnWidths[0] = '18%';
                    columnWidths[1] = '10%';
                    columnWidths[2] = '10%';
                    columnWidths[4] = '15%';
                    columnWidths[5] = '6%';
                    columnWidths[6] = '6%';
                    columnWidths[7] = '10%';
                    
                    // Set font size for header row
                    doc.styles = doc.styles || {};
                    doc.styles.tableHeader = doc.styles.tableHeader || {};
                    doc.styles.tableHeader.fontSize = 9; 
                    doc.styles.tableHeader.fillColor = '#2d4154'; // Set header background color

                    // Apply column widths
                    doc.content[1].table.widths = columnWidths;

                    // Loop through table body and target specific column
                    doc.content[1].table.body.forEach(function (row, rowIndex) {

                        // Skip header row from all styles
                        if (rowIndex === 0) { return; }

                        let targetUppercase = [0, 1, 2, 3,  4]; // Columns to make uppercase
                        let targetCenter = [1, 2, 4, 5, 6, 7]; // Columns to center align
                        let targetRight = []; // Column to right align
                        let removeSpecialChar = []; // Remove special characters from these columns like peso sign

                        row.forEach((cell, columnIndex) => {
                            if (!cell.text) { return; }

                            // Set font size for other rows
                            cell.style = { fontSize: 9 }; 

                            // Background color for even and odd rows
                            if (rowIndex % 2 === 0) {
                                cell.fillColor = '#f9f9f9'; // Light gray for even rows
                            } else {
                                cell.fillColor = '#ffffff'; // White for odd rows
                            }

                            // Set text to uppercase for specific columns
                            if (targetUppercase.includes(columnIndex)) {
                                cell.text = cell.text.toUpperCase();
                            }

                            // Center align specific columns
                            if (targetCenter.includes(columnIndex)) {
                                cell.alignment = 'center';
                            } 
                            
                            // Right align specific columns
                            if (targetRight.includes(columnIndex)) {
                                cell.alignment = 'right';
                            }

                            // Remove special characters from specific columns
                            if (removeSpecialChar.includes(columnIndex)) {
                                cell.text = cell.text.replace(/[^\w\s,.]/gi, '');
                            }
                        });
                    });
                }
            },
            { 
                extend: "excelHtml5",
                title: `Central Inventory - Generated Item as of ` + moment().format('LL'),
                exportOptions: {
                    columns: [1,2,3,4,5,6,7,8]
                },
                customize: function(xlsx){
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];

                    var col = $('col', sheet);

                    $('row c[r^="C"]', sheet).each(function() {
                        $(this).attr('s', '55');
                    });
                }
            },
        ],
    });

    // Checkbox functions
    // ==============================================================
    $('#selectAll').on('change', function() {
        if (this.checked) {
            table.rows({page:'current'}).every(function() {
                let data = this.data();
                if(data.is_archive != 1) {
                    this.select();
                }
            });
        } else {
            table.rows({page:'current'}).deselect();
        }
    });

    table.on('select deselect', function() {
        if (table.rows({ selected: true }).count() !== table.rows().count()) {
            $('#selectAll').prop('checked', false);  
        } else {
            $('#selectAll').prop('checked', true);
        }

        const totalSelected = table.rows({page: 'current'}).data().toArray().length;
        const selectedCount = table.rows({ selected: true, page: 'current' }).count();

        $('#selectAll').prop('checked', selectedCount === totalSelected && totalSelected > 0);
    });

    // Uncheck checkbox when table is redrawn or page is changed
    table.on('draw', function() {
        $('#selectAll').prop('checked', false);
    });
    // ==============================================================

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
                    table.rows().invalidate();
                    table.clear().rows.add(response.data).draw();

                    if(response.data.length > 0){
                        $("#export-excel").prop('disabled', false);
                        $("#export-pdf").prop('disabled', false);
                    }else{
                        $("#export-excel").prop('disabled', true);
                        $("#export-pdf").prop('disabled', true);
                    }
                }
            });

            // if(typeof sku != 'undefined' && sku){
            //     $.ajax({
            //         url: '<?//=base_url('items/get_item') ?>',
            //         type: 'POST',
            //         dataType: 'JSON',
            //         data: {
            //             sku: sku,
            //             _csrf_token: _csrf_hash,
            //         },
            //         success: function(response){
            //             table.rows().invalidate();
            //             table.clear().rows.add(response.data).draw();
    
            //             if(response.data.length > 0){
            //                 $("#export-excel").prop('disabled', false);
            //                 $("#export-pdf").prop('disabled', false);
            //             }else{
            //                 $("#export-excel").prop('disabled', true);
            //                 $("#export-pdf").prop('disabled', true);
            //             }
            //         }
            //     })
            // } else{
            //     toastr.error('Please select an item first', 'Generate Report');
            // }
        }else{
            toastr.error('Please select an item or a warehouse', 'Generate Report');
        }
    }

    $("#export-excel").on('click', function(){
        table.button('.buttons-excel').trigger();
    });
    
    $("#export-pdf").on('click', function(){
        table.button('.buttons-pdf').trigger();
    });

    function reset(){
        $("#select-items").val("").trigger('change');
        $("#select-warehouse").val("").trigger('change');

        items();
    }
</script>