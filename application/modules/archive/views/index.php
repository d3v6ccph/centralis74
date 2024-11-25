<style type="text/css">.redClass{background: #ff8888 !important;} .capitalize{text-transform:capitalize;}</style>
<div class="m-content">
	<div class="row">
		<div class="col-lg-12">
			<!--begin::Portlet-->
			<div class="m-portlet m-portlet--mobile">
				<div class="m-portlet__head">
					<div class="m-portlet__head-caption">
						<div class="m-portlet__head-title">
							<h3 class="m-portlet__head-text">
								Recieving Report Archive
							</h3>
						</div>
					</div>
					<div class="m-portlet__head-tools">
						<ul class="m-portlet__nav">
							<li class="m-portlet__nav-item"></li>
						</ul>
					</div>
				</div>
				<div class="m-portlet__body">
					<!--begin: Datatable -->
						<div class="m_datatable m-datatable m-datatable--default m-datatable--loaded m-datatable--scroll">
							<table class="table table-striped table-bordered" id="table_repository" style="width:100%;">
								<thead>
									<tr>
									<th>REF</th>
										<th>Company</th>
										<th>SUPPLIER</th>
										<th>DOCUMENT NO</th>
										<th>DOCUMENT DATE</th>
										<th>PO NO</th>
										
                     
										
									</tr>
								</thead>
								<tbody>	
								</tbody>
							</table>
						</div>
					<!--end: Datatable -->
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Search Issuance Data :: Start -->



<!-- Search Attendance Data :: End -->
<script type="text/javascript">
 $(document).ready(function() {
                  $('#toolbar').find('select').change(function () {
                      $lctable_repository.bootstrapTable('refreshOptions', {
                          exportDataType: $(this).val()
                      });
                  });


          
                  repositorylist();
                });

                function repositorylist()
                {
                   
               //    $.ajax({
               //      url : "<?php echo site_url('archive/Receiving_report_generate/ajax_list')?>",
               //      type: "POST",
               //      dataType: "JSON",
               //      success: function(data)
               //      {    
               //          var a = new Array();  
                        
               //          for (x = 0; x < data.data.length; x++) {
               //            a[x] = 
               //            {
               //               "rn": data.data[x][0],
               // "company": data.data[x][1], 
               // "supp": data.data[x][2], 
               // "dn": data.data[x][3], 
               // "dd": data.data[x][4], 
               // "po": data.data[x][5], 
               //            };
               //          }
               //          console.log(a);
               //          $('#table_repository').bootstrapTable({ data: a });
               //          $('#table_repository').bootstrapTable('load',a);
               //      },
               //      error: function (jqXHR, textStatus, errorThrown)
               //      {
               //          alert('Error get data from ajax');
               //      }
               //    });
                     tbl = $('#table_repository').DataTable({  

                            "ajax": "<?php echo site_url('archive/Receiving_report_generate/ajax_list')?>", 
                            "columns": [

                              { "data": "rn" },
                                { "data": "company" },
                                { "data": "supp" },
                                { "data": "dn" },
                                { "data": "dd" },
                                { "data": "po" },
                                
                            ],
                            //  filterControl: true,
                            // responsive: false,
                            // "columnDefs": [
                            //       {
                            //           "targets": [ 2 ],
                            //           "visible": false,
                            //           "searchable": false,
                            //           "filterControl": true,
                            //           "filterControl": 'input',

                            //       }
                            //   ] ,  
                            //   "order": [[ 1, "desc" ]],
                            //   "filterControl": 'input',
                              // "data-filter-control":"input",
                             // order: ['id', 'desc']  
                    });
                }

</script>