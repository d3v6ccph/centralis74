<div class="m-content">
    <div class="row">
        <div class="col-md-12">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon">
                                    <i class="flaticon-graph"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    WAREHOUSE TOTAL SYNCED ITEMS
                                </h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="chart" style="height: 450px"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    $(document).ready( function(){
        get_all_warehouse();

        $.ajax({
            url: '<?=base_url('dashboard/get_all_warehouse') ?>',
            type: 'GET',
            dataType: 'JSON',
            success: function(response){
                get_all_warehouse(response.data);
            }
        });
    });

    function get_all_warehouse(data = []){
        am4core.useTheme(am4themes_animated);

        am4core.ready( function(){
            ScopeChart = am4core.create("chart", am4core.Container);
            ScopeChart.width = am4core.percent(100);
            ScopeChart.height = am4core.percent(100);

            ScopeChartData = new am4charts.XYChart3D();
            ScopeChartData.responsive.enabled = true;
            ScopeChartData.parent = ScopeChart;
            ScopeChartData.data = data;
            
            var categoryAxis_empStatus = ScopeChartData.yAxes.push(new am4charts.CategoryAxis());
            categoryAxis_empStatus.dataFields.category = "warehouse";
            categoryAxis_empStatus.renderer.minGridDistance = 100;

            var valueAxis_empStatus = ScopeChartData.xAxes.push(new am4charts.ValueAxis());
            valueAxis_empStatus.dataFields.category = "total_items";
            valueAxis_empStatus.renderer.minGridDistance = 200;

            var series = ScopeChartData.series.push(new am4charts.ColumnSeries3D());
            series.dataFields.categoryY = "warehouse";
            series.dataFields.valueX = "total_items";
            series.columns.template.propertyFields.fill = "color";
            series.columns.template.tooltipText = "{valueX.value}";
            series.columns.template.column3D.stroke = am4core.color("#fff");
            series.columns.template.column3D.strokeOpacity = 0.2;
            series.columns.template.adapter.add("fill", function(fill, target){
                return ScopeChartData.colors.getIndex(target.dataItem.index);
            });
        });
    }
</script>
