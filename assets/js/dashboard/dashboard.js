let inv_stock_storage_life_page_size = 300;
let inv_stock_storage_life_offset = 1;
let inv_stock_storage_life_eof = 0;

let filter_inv_stock_storage_life = {priority: "", category: ""};
let temp_filter_inv_stock_storage_life = {priority: "", category: ""};

let sort_inv_stock_storage_life = [{field: "priority.name", order: "ASC"}];
let temp_sort_inv_stock_storage_life = [{field: "priority.name", order: "ASC"}];

let inv_nearing_min_qty_page_size = 300;
let inv_nearing_min_qty_offset = 1;
let inv_nearing_min_qty_eof = 0;
let filter_inv_nearing_min_qty = {priority: "", category: ""};
let temp_filter_inv_nearing_min_qty = {priority: "", category: ""};

let sort_inv_nearing_min_qty = [{field: "priority.name", order: "ASC"}];
let temp_sort_inv_nearing_min_qty = [{field: "priority.name", order: "ASC"}];

let _withMinQty;
let _withMaxQty;
let _withReorderQty;
let _withCritLevelPercentage;

let dtableMinQtySettings;
let dtableMaxQtySettings;
let dtableReorderQtySettings;
let dtableCritLevelSettings;
let dtItemsNearingForMinQty;
let dtStorageLife;

let chartInventoryNearingMinQty;
let chartStorageLife;

am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    var chart = am4core.create("chartdiv", am4charts.PieChart);

    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "numbers";
    pieSeries.dataFields.category = "type";

    chart.innerRadius = am4core.percent(30);

    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        .cursorOverStyle = [{
        "property": "cursor",
        "value": "pointer"
    }];

    pieSeries.alignLabels = false;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = true;

    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    var hoverState = pieSeries.slices.template.states.getKey("hover");

    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0.7;
    hoverShadow.blur = 5;

    chart.legend = new am4charts.Legend();
    var active1 = 0;
    var archive1 = 0;
    $.ajax({
        url: base_url + "dashboard/count1",
        type: "POST",
        dataType: "JSON",
        success: function (data) {

            active1 = data;
            $.ajax({
                url: base_url + "dashboard/count2",
                type: "POST",
                dataType: "JSON",
                success: function (data) {

                    archive1 = data;
                    chart.data = [{
                        "type": "Active",
                        "numbers": active1
                    }, {
                        "type": "Archived",
                        "numbers": archive1
                    }];
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
});

am4core.ready(function () {
    am4core.useTheme(am4themes_animated);
    var chart = am4core.create("chart2div", am4charts.PieChart);

    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "numbers";
    pieSeries.dataFields.category = "type";

    chart.innerRadius = am4core.percent(30);

    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        .cursorOverStyle = [{
        "property": "cursor",
        "value": "pointer"
    }];

    pieSeries.alignLabels = false;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = true;

    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    var hoverState = pieSeries.slices.template.states.getKey("hover");

    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0.7;
    hoverShadow.blur = 5;

    chart.legend = new am4charts.Legend();
    var active1 = 0;
    var archive1 = 0;
    $.ajax({
        url: base_url + "dashboard/count3",
        type: "POST",
        dataType: "JSON",
        success: function (data) {
            active1 = data;
            $.ajax({
                url: base_url + "dashboard/count4",
                type: "POST",
                dataType: "JSON",
                success: function (data) {

                    archive1 = data;
                    chart.data = [{
                        "type": "Active",
                        "numbers": active1
                    }, {
                        "type": "Archived",
                        "numbers": archive1
                    }];
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('Error get data from ajax');
                }
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
});

loadInventoryStockStorageLife(filter_inv_stock_storage_life);
loadInventoryNearingMinimumQty(filter_inv_nearing_min_qty);

function move(mode, graph) {
    if (mode === 'next') {
        if (graph === 'stock storage life') {
            inv_stock_storage_life_offset += 1;
        } else if (graph === 'nearing min. qty.') {
            inv_nearing_min_qty_offset += 1;
        }
    } else if (mode === 'first') {
        if (graph === 'stock storage life') {
            inv_stock_storage_life_offset = 1
        } else if (graph === 'nearing min. qty.') {
            inv_nearing_min_qty_offset = 1;
        }
    } else if (mode === 'last') {
        if (graph === 'stock storage life') {
            inv_stock_storage_life_offset = inv_stock_storage_life_eof;
        } else if (graph === 'nearing min. qty.') {
            inv_nearing_min_qty_offset = inv_nearing_min_qty_eof;
        }
    } else {
        if (graph === 'stock storage life') {
            inv_stock_storage_life_offset = inv_stock_storage_life_offset >= 1 ? inv_stock_storage_life_offset - 1 : 0;
        } else if (graph === 'nearing min. qty.') {
            inv_nearing_min_qty_offset = inv_nearing_min_qty_offset >= 1 ? inv_nearing_min_qty_offset - 1 : 0;
        }
    }

    if (graph === 'stock storage life') {
        loadInventoryStockStorageLife(filter_inv_stock_storage_life);
    } else if (graph === 'nearing min. qty.') {
        loadInventoryNearingMinimumQty(filter_inv_nearing_min_qty);
    }
}

const pageContentInvStockStorageLife = $("#page-content-inv-stock-storage-life");
const pageContentInvNearingMinQty = $("#page-content-inv-nearing-min-qty");

function loadInventoryStockStorageLife(filter = null) {
    $.ajax({
        url: base_url + "dashboard/get_inventory_statistics" + "/" + inv_stock_storage_life_offset + "/" + inv_stock_storage_life_page_size,
        type: "POST",
        dataType: "JSON",
        data: {
            search: $("#search-inventory-inv-stock-storage-life").val(),
            filter: filter_inv_stock_storage_life,
            sort: sort_inv_stock_storage_life,
        },
        success: function (response) {
            const data = response.data;
            chartStorageLife.data = data;
            const colors = response.colors;
            const legend = response.legend;
            const start = response.start;
            const end = response.end;
            const total = response.total;
            inv_stock_storage_life_eof = response.eof;

            pageContentInvStockStorageLife.val(start + " to " + (end > total ? total : end) + " of " + total);

            if (inv_stock_storage_life_offset <= 1 || inv_stock_storage_life_eof <= start) {
                $("#first-inv-stock-storage-life").attr("disabled", "true");
                $("#prev-inv-stock-storage-life").attr("disabled", "true");
            } else {
                $("#first-inv-stock-storage-life").removeAttr("disabled");
                $("#prev-inv-stock-storage-life").removeAttr("disabled");
            }

            if (end >= total) {
                $("#last-inv-stock-storage-life").attr("disabled", "true");
                $("#next-inv-stock-storage-life").attr("disabled", "true");
            } else {
                $("#last-inv-stock-storage-life").removeAttr("disabled");
                $("#next-inv-stock-storage-life").removeAttr("disabled");
            }
            chartStorageLife.scrollbarX = new am4core.Scrollbar();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

// START INITIALIZATION OF CHART FOR INVENTORY NEARING MINIMUM QTY
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    chartInventoryNearingMinQty = am4core.create("inventory-chart-nearing-min-qty", am4charts.XYChart);
    chartInventoryNearingMinQty.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chartInventoryNearingMinQty.legend = new am4charts.Legend();
    chartInventoryNearingMinQty.legend.position = 'top';
    chartInventoryNearingMinQty.legend.paddingBottom = 20;

    var categoryAxis = chartInventoryNearingMinQty.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "namex";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.renderer.minGridDistance = 30;
    categoryAxis.title.text = "ITEM NAME";
    categoryAxis.title.fontWeight = 600;
    categoryAxis.cursorTooltipEnabled = false;
    categoryAxis.renderer.inversed = true;

    var valueAxis = chartInventoryNearingMinQty.xAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.cursorTooltipEnabled = false;
    valueAxis.title.text = "QUANTITY";
    valueAxis.title.fontWeight = 600;

    var label = categoryAxis.renderer.labels.template;
    label.wrap = true;
    label.maxWidth = 300;
    label.fontSize = 10;
    label.fontWeight = 500;

    $.post(base_url + "dashboard/get_color_setting", {nonConditional: [1]},
        function (response) {
            createSeriesForNearingMinQty('qty', 'Current Qty', response[1].color);
            createSeriesForNearingMinQty('min_qty', 'Minimum Qty.', response[0].color);
        }, 'json');

    // Set cell size in pixels
    let cellSize = 90;
    chartInventoryNearingMinQty.events.on("datavalidated", function (ev) {
        // Get objects of interest
        let chart = ev.target;
        let categoryAxis = chart.yAxes.getIndex(0);

        // Calculate how we need to adjust chart height
        let adjustHeight = chart.data.length * cellSize - categoryAxis.pixelHeight;

        // get current chart height
        let targetHeight = chart.pixelHeight + adjustHeight;

        // Set it on chart's container
        chart.svgContainer.htmlElement.style.height = targetHeight + "px";
    });

    chartInventoryNearingMinQty.cursor = new am4charts.XYCursor();
    chartInventoryNearingMinQty.cursor.lineX.disabled = true;
    chartInventoryNearingMinQty.cursor.lineY.disabled = true;
    chartInventoryNearingMinQty.scrollbarX = new am4core.Scrollbar();
});

function createSeriesForNearingMinQty(value, name, color) {
    var series = chartInventoryNearingMinQty.series.push(new am4charts.ColumnSeries());
    series.dataFields.valueX = value;
    series.dataFields.categoryY = 'namex';
    series.clustered = false;
    series.name = name;

    if (value === "min_qty") {
        series.columns.template.height = am4core.percent(50);
    }

    series.tooltipText = "[bold]" + name + ": {valueX}[/]";
    series.columns.template.adapter.add("fill", function (fill, target) {
        return am4core.color(color);
    });
    series.strokeWidth = 0;

    return series;
}

// END INITIALIZATION OF CHART FOR INVENTORY NEARING MINIMUM QTY

// START INITIALIZATION OF CHART FOR INVENTORY STOCK STORAGE LIFE
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    chartStorageLife = am4core.create("inventory-chart-stock-storage-life", am4charts.XYChart);
    chartStorageLife.hiddenState.properties.opacity = 0; // this creates initial fade-in

    chartStorageLife.legend = new am4charts.Legend();
    chartStorageLife.legend.position = 'top';
    chartStorageLife.legend.paddingBottom = 20;

    var categoryAxis = chartStorageLife.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis.dataFields.category = "item_description";
    categoryAxis.renderer.grid.template.location = 0;
    categoryAxis.title.text = "ITEM NAME & QUANTITY";
    categoryAxis.title.fontWeight = 600;
    categoryAxis.cursorTooltipEnabled = false;
    categoryAxis.renderer.inversed = true;

    var valueAxis = chartStorageLife.xAxes.push(new am4charts.ValueAxis());
    valueAxis.min = 0;
    valueAxis.max = 100;
    valueAxis.strictMinMax = true;
    valueAxis.calculateTotals = true;
    valueAxis.renderer.minWidth = 50;
    valueAxis.cursorTooltipEnabled = false;
    valueAxis.title.text = "PERCENTAGE";
    valueAxis.title.fontWeight = 600;

    var label = categoryAxis.renderer.labels.template;
    label.wrap = true;
    label.maxWidth = 300;
    label.fontSize = 10;
    label.fontWeight = 500;

    $.post(base_url + "dashboard/get_color_setting", {nonConditional: [0, 1]},
        function (response) {
            response.forEach((item) => {
                let desc = item.description.replace(/\./g, "");
                desc = desc.replace(/\s/g, "_").toLowerCase();
                createSeriesForStorageLife(desc, item.description, item.color);
            });
        }, 'json');

    // Set cell size in pixels
    let cellSize = 50;
    chartStorageLife.events.on("datavalidated", function (ev) {
        // Get objects of interest
        let chart = ev.target;
        let categoryAxis = chartStorageLife.yAxes.getIndex(0);

        // Calculate how we need to adjust chart height
        let adjustHeight = chartStorageLife.data.length * cellSize - categoryAxis.pixelHeight;

        // get current chart height
        let targetHeight = chartStorageLife.pixelHeight + adjustHeight;

        // Set it on chart's container
        chartStorageLife.svgContainer.htmlElement.style.height = targetHeight + "px";
    });
});

function createSeriesForStorageLife(value, name, color) {
    var series = chartStorageLife.series.push(new am4charts.ColumnSeries());
    series.columns.template.width = am4core.percent(80);
    series.columns.template.propertyFields.dummyData = "curr_qty";
    series.columns.template.tooltipText = "[bold]Qty: {valueX} ({valueX.totalPercent.formatNumber('#.00')}%)[/]";

    series.name = name;
    series.dataFields.categoryY = "item_description";
    series.dataFields.valueX = value;
    series.dataFields.valueXShow = "totalPercent";
    series.dataItems.template.locations.categoryY = 0.5;
    series.stacked = true;
    series.tooltip.pointerOrientation = "vertical";
    series.columns.template.adapter.add("fill", function (fill, target) {
        return am4core.color(color);
    });
    series.strokeWidth = 0;
}

// START INITIALIZATION OF CHART FOR INVENTORY STOCK STORAGE LIFE

function loadInventoryNearingMinimumQty(filter = null) {
    $.ajax({
        url: base_url + "dashboard/get_nearing_minimum_items" + "/" + inv_nearing_min_qty_offset + "/" + inv_nearing_min_qty_page_size,
        type: "POST",
        dataType: "JSON",
        data: {
            search: $("#search-inventory-inv-nearing-min-qty").val(),
            filter: filter_inv_nearing_min_qty,
            sort: sort_inv_nearing_min_qty,
        },
        success: function (response) {

            const data = response.data;
            chartInventoryNearingMinQty.data = data;

            const start = response.start;
            const end = response.end;
            const total = response.total;

            const colors = response.colors;
            const legend = response.legend;

            // TODO : hide graph if no data

            if (data.length <= 0) {
                $("#inventory-chart-nearing-min-qty").fadeOut();
                $("#no-data-nearing-min-qty").fadeIn();
            } else {
                $("#inventory-chart-nearing-min-qty").fadeIn();
                $("#no-data-nearing-min-qty").fadeOut();
            }

            inv_nearing_min_qty_eof = response.eof;

            pageContentInvNearingMinQty.val(start + " to " + (end > total ? total : end) + " of " + total);

            if (inv_nearing_min_qty_offset <= 1 || inv_nearing_min_qty_eof <= 1) {
                $("#first-inv-nearing-min-qty").attr("disabled", "true");
                $("#prev-inv-nearing-min-qty").attr("disabled", "true");
            } else {
                $("#first-inv-nearing-min-qty").removeAttr("disabled");
                $("#prev-inv-nearing-min-qty").removeAttr("disabled");
            }

            if (end >= total) {
                $("#last-inv-nearing-min-qty").attr("disabled", "true");
                $("#next-inv-nearing-min-qty").attr("disabled", "true");
            } else {
                $("#last-inv-nearing-min-qty").removeAttr("disabled");
                $("#next-inv-nearing-min-qty").removeAttr("disabled");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error get data from ajax');
        }
    });
}

$("#search-inventory-inv-stock-storage-life")
    .on("input", function () {
        if ($(this).val()) {
            $("#clear-search-container-inv-stock-storage-life").removeClass("d-none");
        } else {
            $("#clear-search-container-inv-stock-storage-life").addClass("d-none");
        }
    })
    .on("keyup", delay(function () {
        loadInventoryStockStorageLife(filter_inv_stock_storage_life);
    }, 1000));

$("#search-inventory-inv-nearing-min-qty")
    .on("input", function () {
        if ($(this).val()) {
            $("#clear-search-container-inv-nearing-min-qty").removeClass("d-none");
        } else {
            $("#clear-search-container-inv-nearing-min-qty").addClass("d-none");
        }
    })
    .on("keyup", delay(function () {
        mapBlockUI();
        loadInventoryNearingMinimumQty();
    }, 1000));

function delay(callback, ms) {
    var timer = 0;
    return function () {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

$("#clear-search-inv-stock-storage-life")
    .on("click", function () {
        $("#search-inventory-inv-stock-storage-life").val("");
        $("#clear-search-container-inv-stock-storage-life").addClass("d-none");
        loadInventoryStockStorageLife(filter_inv_stock_storage_life);
    });

$("#clear-search-inv-nearing-min-qty")
    .on("click", function () {
        $("#search-inventory-inv-nearing-min-qty").val("");
        $("#clear-search-container-inv-nearing-min-qty").addClass("d-none");
        loadInventoryNearingMinimumQty(filter_inv_nearing_min_qty);
    });

$(document)
    .on("submit", "#frm-inventory-filter", function (e) {
        e.preventDefault();
        const form = $(this);
        const priority = form.find("[name='priority']").val();
        const category = form.find("[name='category']").val();
        const fields = $("select[name='field[]']").map(function () {
            return $(this).val();
        }).get();

        const sort_order = $("select[name='sort_order[]']").map(function () {
            return $(this).val();
        }).get();

        const graph = $("#frm-inventory-filter").attr("data-graph");

        if (graph === 'stock storage life') {
            temp_filter_inv_stock_storage_life = filter_inv_stock_storage_life; // store old filter
            filter_inv_stock_storage_life = {priority, category};

            temp_sort_inv_stock_storage_life = sort_inv_stock_storage_life;

            const sort = [];
            $.each(fields, function (index, item) {
                sort.push({field: item, order: sort_order[index]});
            });

            sort_inv_stock_storage_life = sort;
        } else if (graph === 'nearing min qty') {
            temp_filter_inv_nearing_min_qty = filter_inv_nearing_min_qty; // store old filter
            filter_inv_nearing_min_qty = {priority, category};

            temp_sort_inv_nearing_min_qty = sort_inv_nearing_min_qty;

            const sort = [];
            $.each(fields, function (index, item) {
                sort.push({field: item, order: sort_order[index]});
            });

            sort_inv_nearing_min_qty = sort;
        }

        $("#inventory-filter-modal").modal("hide");
    });

function openAdvanceFilterModal(graph) {
    const modalTitle = $("#inventory-filter-modal").find(".modal-title");
    if (graph === 'stock storage life') {
        modalTitle.empty().html("Stock Storage Life - SORT & Filter Options");
        $("select[name='priority']").val(filter_inv_stock_storage_life.priority).trigger('change');
        $("select[name='category']").val(filter_inv_stock_storage_life.category).trigger('change');

        sort_inv_stock_storage_life.forEach((sort, index) => {
            const selectField = $("#sort-container").find(".row").find("select.sort-field");
            const selectOrder = $("#sort-container").find(".row").find("select.sort-order");
            $(selectField[index]).val(sort.field).trigger('change');
            $(selectOrder[index]).val(sort.order).trigger('change');

            if (index > 0) {
                addSortField(true, sort.field, sort.order);
            }
        });
    } else if (graph === 'nearing min qty') {
        $("#sort-container > .row:not('.main-sort-field')").remove();
        sortFieldCtr = 0;

        modalTitle.empty().html("Nearing Min. Qty. - SORT & Filter Options");
        $("select[name='priority']").val(filter_inv_nearing_min_qty.priority).trigger('change');
        $("select[name='category']").val(filter_inv_nearing_min_qty.category).trigger('change');

        sort_inv_nearing_min_qty.forEach((sort, index) => {
            const selectField = $("#sort-container").find(".row").find("select.sort-field");
            const selectOrder = $("#sort-container").find(".row").find("select.sort-order");
            $(selectField[index]).val(sort.field).trigger('change');
            $(selectOrder[index]).val(sort.order).trigger('change');

            if (index > 0) {
                addSortField(true, sort.field, sort.order);
            }
        });
    }

    $("#frm-inventory-filter").attr("data-graph", graph);
    $("#inventory-filter-modal").modal("show");
}

$('#inventory-filter-modal')
    .on('hidden.bs.modal', function (e) {
        const graph = $("#frm-inventory-filter").attr("data-graph");

        if (graph === 'stock storage life') {
            const isSameFilter = JSON.stringify(filter_inv_stock_storage_life) === JSON.stringify(temp_filter_inv_stock_storage_life);
            const isSameSort = JSON.stringify(sort_inv_stock_storage_life) === JSON.stringify(temp_sort_inv_stock_storage_life);

            if (!isSameFilter || !isSameSort) {
                loadInventoryStockStorageLife(filter_inv_stock_storage_life);
                // store current to temp to disable reloading on close with no changes
                temp_filter_inv_stock_storage_life = filter_inv_stock_storage_life;

                temp_sort_inv_stock_storage_life = sort_inv_stock_storage_life;
            }
        } else if (graph === 'nearing min qty') {
            const isSameFilter = JSON.stringify(filter_inv_nearing_min_qty) === JSON.stringify(temp_filter_inv_nearing_min_qty);
            const isSameSort = JSON.stringify(sort_inv_nearing_min_qty) === JSON.stringify(temp_sort_inv_nearing_min_qty);

            if (!isSameFilter || !isSameSort) {
                loadInventoryNearingMinimumQty(filter_inv_nearing_min_qty);
                // store current to temp to disable reloading on close with no changes
                temp_filter_inv_nearing_min_qty = filter_inv_nearing_min_qty;

                temp_sort_inv_nearing_min_qty = sort_inv_nearing_min_qty;
            }
        }
    });

// START GRAPH FOR MIN. QTY SETTING
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("graph-min-qty-setting", am4charts.PieChart);

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "key";

    // Let's cut a hole in our Pie chart the size of 30% the radius
    chart.innerRadius = am4core.percent(30);

    // Put a thick white border around each Slice
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        // change the cursor on hover to make it apparent the object can be interacted with
        .cursorOverStyle = [
        {
            "property": "cursor",
            "value": "pointer"
        }
    ];
    pieSeries.slices.template.tooltipText = "[bold]{key} : {value}[/]";

    pieSeries.alignLabels = true;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = false;

    // Create a base filter effect (as if it's not there) for the hover to return to
    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    // Create hover state
    var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

    // Slightly shift the shadow and make it more prominent on hover
    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0;
    // hoverShadow.blur = 5;

    // Add a legend
    chart.legend = new am4charts.Legend();

    pieSeries.slices.template.states.getKey("hover").properties.scale = 1;
    pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;

    pieSeries.slices.template.events.on("hit", function (ev) {
        const key = ev.target.dataItem.dataContext.key;
        const withMinQty = (key === "Without Minimum Qty.") ? 0 : 1;
        const modal = withMinQty === 1 ? $("#inventory_items_without_min_qty") : $("#inventory_items_with_min_qty");
        const title = withMinQty === 1 ? "<span class='m--font-boldest'>" +
            "   Items <span style='color: #1E88E5;'>with</span> Minimum Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Minimum Qty." +
            "</span>";
        modal.find(".modal-title").empty().html(title);
        _withMinQty = withMinQty;
        $(modal).modal("show");
    }, this);

    $.ajax({
        url: base_url + "dashboard/get_inventory_with_and_without_min_qty_count",
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            chart.data = response;
        }
    });
});
// END GRAPH FOR MIN. QTY SETTING

// START GRAPH FOR MAX. QTY SETTING
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("graph-max-qty-setting", am4charts.PieChart);

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "key";

    // Let's cut a hole in our Pie chart the size of 30% the radius
    chart.innerRadius = am4core.percent(30);

    // Put a thick white border around each Slice
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        // change the cursor on hover to make it apparent the object can be interacted with
        .cursorOverStyle = [
        {
            "property": "cursor",
            "value": "pointer"
        }
    ];
    pieSeries.slices.template.tooltipText = "[bold]{key} : {value}[/]";

    pieSeries.alignLabels = true;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = false;

    // Create a base filter effect (as if it's not there) for the hover to return to
    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    // Create hover state
    var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

    // Slightly shift the shadow and make it more prominent on hover
    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0;
    // hoverShadow.blur = 5;

    // Add a legend
    chart.legend = new am4charts.Legend();

    pieSeries.slices.template.states.getKey("hover").properties.scale = 1;
    pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;

    pieSeries.slices.template.events.on("hit", function (ev) {
        const key = ev.target.dataItem.dataContext.key;
        const withMaxQty = (key === "Without Maximum Qty.") ? 0 : 1;
        const modal = withMaxQty === 1 ? $("#inventory_items_without_max_qty") : $("#inventory_items_with_max_qty");
        const title = withMaxQty === 1 ? "<span class='m--font-boldest'>" +
            "   Items <span style='color: #1E88E5;'>with</span> Maximum Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Maximum Qty." +
            "</span>";
        modal.find(".modal-title").empty().html(title);
        _withMaxQty = withMaxQty;
        $(modal).modal("show");
    }, this);

    $.ajax({
        url: base_url + "dashboard/get_inventory_with_and_without_max_qty_count",
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            chart.data = response;
        }
    });
});
// END GRAPH FOR MAX. QTY SETTING

// START GRAPH FOR REORDER QTY SETTING
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("graph-reorder-qty-setting", am4charts.PieChart);

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "key";

    // Let's cut a hole in our Pie chart the size of 30% the radius
    chart.innerRadius = am4core.percent(30);

    // Put a thick white border around each Slice
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        // change the cursor on hover to make it apparent the object can be interacted with
        .cursorOverStyle = [
        {
            "property": "cursor",
            "value": "pointer"
        }
    ];
    pieSeries.slices.template.tooltipText = "[bold]{key} : {value}[/]";

    pieSeries.alignLabels = true;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = false;

    // Create a base filter effect (as if it's not there) for the hover to return to
    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    // Create hover state
    var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

    // Slightly shift the shadow and make it more prominent on hover
    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0;
    // hoverShadow.blur = 5;

    // Add a legend
    chart.legend = new am4charts.Legend();

    pieSeries.slices.template.states.getKey("hover").properties.scale = 1;
    pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;

    pieSeries.slices.template.events.on("hit", function (ev) {
        const key = ev.target.dataItem.dataContext.key;
        const withReorderQty = (key === "Without Reorder Qty.") ? 0 : 1;
        const modal = withReorderQty === 1 ? $("#inventory_items_without_reorder_qty") : $("#inventory_items_with_reorder_qty");
        const title = withReorderQty === 1 ? "<span class='m--font-boldest'>" +
            "   Items <span style='color: #1E88E5;'>with</span> Reorder Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Reorder Qty." +
            "</span>";
        modal.find(".modal-title").empty().html(title);
        _withReorderQty = withReorderQty;
        $(modal).modal("show");
    }, this);

    $.ajax({
        url: base_url + "dashboard/get_inventory_with_and_without_reorder_qty_count",
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            chart.data = response;
        }
    });
});
// END GRAPH FOR REORDER QTY SETTING

// START GRAPH FOR CRITICAL SETTING
am4core.ready(function () {
    am4core.useTheme(am4themes_animated);

    // Create chart instance
    var chart = am4core.create("graph-critical-level-setting", am4charts.PieChart);

    // Add and configure Series
    var pieSeries = chart.series.push(new am4charts.PieSeries());
    pieSeries.dataFields.value = "value";
    pieSeries.dataFields.category = "key";

    // Let's cut a hole in our Pie chart the size of 30% the radius
    chart.innerRadius = am4core.percent(30);

    // Put a thick white border around each Slice
    pieSeries.slices.template.stroke = am4core.color("#fff");
    pieSeries.slices.template.strokeWidth = 2;
    pieSeries.slices.template.strokeOpacity = 1;
    pieSeries.slices.template
        // change the cursor on hover to make it apparent the object can be interacted with
        .cursorOverStyle = [
        {
            "property": "cursor",
            "value": "pointer"
        }
    ];
    pieSeries.slices.template.tooltipText = "[bold]{key} : {value}[/]";

    pieSeries.alignLabels = true;
    pieSeries.labels.template.bent = true;
    pieSeries.labels.template.radius = 3;
    pieSeries.labels.template.padding(0, 0, 0, 0);

    pieSeries.ticks.template.disabled = false;

    // Create a base filter effect (as if it's not there) for the hover to return to
    var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
    shadow.opacity = 0;

    // Create hover state
    var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

    // Slightly shift the shadow and make it more prominent on hover
    var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
    hoverShadow.opacity = 0;
    // hoverShadow.blur = 5;

    // Add a legend
    chart.legend = new am4charts.Legend();

    pieSeries.slices.template.states.getKey("hover").properties.scale = 1;
    pieSeries.slices.template.states.getKey("active").properties.shiftRadius = 0;

    pieSeries.slices.template.events.on("hit", function (ev) {
        const key = ev.target.dataItem.dataContext.key;
        const withCritLevelPercentage = (key === "Without Critical Level") ? 0 : 1;
        const modal = withCritLevelPercentage === 1 ? $("#inventory_items_without_critical_level_percentage") : $("#inventory_items_with_critical_level_percentage");
        const title = withCritLevelPercentage === 1 ? "<span class='m--font-boldest'>" +
            "Items <span style='color: #1E88E5;'>with</span> Critical Level Percentage</span>" :
            "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Critical Level Percentage" +
            "</span>";
        modal.find(".modal-title").empty().html(title);
        _withCritLevelPercentage = withCritLevelPercentage;
        $(modal).modal("show");
    }, this);

    $.ajax({
        url: base_url + "dashboard/get_inventory_with_and_without_critical_level_count",
        type: "GET",
        dataType: "JSON",
        success: function (response) {
            chart.data = response;
        }
    });
});
// END GRAPH FOR CRITICAL SETTING


$("#inventory_items_with_min_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForMinQtySettings(_withMinQty, "tbl-with-min-qty");
    });

$("#inventory_items_without_min_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForMinQtySettings(_withMinQty, "tbl-without-min-qty");
    });

$("#inventory_items_with_max_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForMaxQtySettings(_withMaxQty, "tbl-with-max-qty");
    });

$("#inventory_items_without_max_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForMaxQtySettings(_withMaxQty, "tbl-without-max-qty");
    });

$("#inventory_items_with_reorder_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForReorderQtySettings(_withReorderQty, "tbl-with-reorder-qty");
    });

$("#inventory_items_without_reorder_qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForReorderQtySettings(_withReorderQty, "tbl-without-reorder-qty");
    });

$("#inventory_items_with_critical_level_percentage")
    .on('shown.bs.modal', function (e) {
        loadItemsForCriticalLevelPercentageSettings(_withCritLevelPercentage, "tbl-with-critical-level-percentage");
    });

$("#inventory_items_without_critical_level_percentage")
    .on('shown.bs.modal', function (e) {
        loadItemsForCriticalLevelPercentageSettings(_withCritLevelPercentage, "tbl-without-critical-level-percentage");
    });

function loadItemsForMinQtySettings(withMinQty, table) {
    const date = moment(new Date()).unix();
    let title = withMinQty === 1 ? "ITEMS WITH MINIMUM QUANTITY" : "ITEMS WITHOUT MINIMUM QUANTITY";
    title += " " + date;
    dtableMinQtySettings = $("#" + table).DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
            "<'row'<'col-12'tr>><'row'<'col-12'i>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: title,
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: title,
                customize: function (doc) {
                    doc.content[1].table.widths = ["20%", "60%", "20%"];
                    doc.defaultStyle.alignment = 'left';
                    doc.styles.tableHeader.alignment = 'left';
                }
            }
        ],
        serverSide: false,
        processing: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: base_url + "dashboard/get_inventory_items_form_min_qty_setting/" + withMinQty,
            type: "post",
            dataType: "json"
        }, columns: [
            {data: 'sku', width: "20%"},
            {data: 'name', width: "50%"},
            {data: 'min_qty', width: "20%"},
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#" + table + "_filter input[type='search']")
                .removeClass("form-control-sm");

            const dropdown = '' +
                '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                '             data-dropdown-toggle="hover" aria-expanded="true">' +
                '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                '                EXPORT AS' +
                '            </button>' +
                '            <div class="m-dropdown__wrapper">' +
                '                <div class="m-dropdown__inner">' +
                '                    <div class="m-dropdown__body">' +
                '                        <div class="m-dropdown__content">' +
                '                            <ul class="m-nav">' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'excel\', \'min\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                           Excel File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'pdf\', \'min\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                          PDF File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                            </ul>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>';

            $(dropdown).appendTo("#" + table + "_wrapper .exportDropdown");
        }
    })
}

function loadItemsForMaxQtySettings(withMaxQty, table) {
    const date = moment(new Date()).unix();
    let title = withMaxQty === 1 ? "ITEMS WITH MAXIMUM QUANTITY" : "ITEMS WITHOUT MAXIMUM QUANTITY";
    title += " " + date;
    dtableMaxQtySettings = $("#" + table).DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
            "<'row'<'col-12'tr>><'row'<'col-12'i>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: title,
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: title,
                customize: function (doc) {
                    doc.content[1].table.widths = ["20%", "60%", "20%"];
                    doc.defaultStyle.alignment = 'left';
                    doc.styles.tableHeader.alignment = 'left';
                }
            }
        ],
        serverSide: false,
        processing: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: base_url + "dashboard/get_inventory_items_form_max_qty_setting/" + withMaxQty,
            type: "post",
            dataType: "json"
        }, columns: [
            {data: 'sku', width: "20%"},
            {data: 'name', width: "50%"},
            {data: 'max_qty', width: "20%"},
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#" + table + "_filter input[type='search']")
                .removeClass("form-control-sm");

            const dropdown = '' +
                '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                '             data-dropdown-toggle="hover" aria-expanded="true">' +
                '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                '                EXPORT AS' +
                '            </button>' +
                '            <div class="m-dropdown__wrapper">' +
                '                <div class="m-dropdown__inner">' +
                '                    <div class="m-dropdown__body">' +
                '                        <div class="m-dropdown__content">' +
                '                            <ul class="m-nav">' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'excel\', \'max\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                           Excel File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'pdf\', \'max\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                          PDF File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                            </ul>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>';

            $(dropdown).appendTo("#" + table + "_wrapper .exportDropdown");
        }
    })
}

function loadItemsForReorderQtySettings(witReorderQty, table) {
    const date = moment(new Date()).unix();
    let title = witReorderQty === 1 ? "ITEMS WITH REORDER QUANTITY" : "ITEMS WITHOUT REORDER QUANTITY";
    title += " " + date;
    dtableReorderQtySettings = $("#" + table).DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
            "<'row'<'col-12'tr>><'row'<'col-12'i>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: title,
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: title,
                customize: function (doc) {
                    doc.content[1].table.widths = ["20%", "60%", "20%"];
                    doc.defaultStyle.alignment = 'left';
                    doc.styles.tableHeader.alignment = 'left';
                }
            }
        ],
        serverSide: false,
        processing: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: base_url + "dashboard/get_inventory_items_form_reorder_qty_setting/" + witReorderQty,
            type: "post",
            dataType: "json"
        }, columns: [
            {data: 'sku', width: "20%"},
            {data: 'name', width: "50%"},
            {
                data: 'reorder_qty',
                width: "20%",
                render: function (data) {
                    return data || "--";
                }
            },
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#" + table + "_filter input[type='search']")
                .removeClass("form-control-sm");

            const dropdown = '' +
                '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                '             data-dropdown-toggle="hover" aria-expanded="true">' +
                '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                '                EXPORT AS' +
                '            </button>' +
                '            <div class="m-dropdown__wrapper">' +
                '                <div class="m-dropdown__inner">' +
                '                    <div class="m-dropdown__body">' +
                '                        <div class="m-dropdown__content">' +
                '                            <ul class="m-nav">' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'excel\', \'reorder\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                           Excel File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'pdf\', \'reorder\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                          PDF File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                            </ul>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>';

            $(dropdown).appendTo("#" + table + "_wrapper .exportDropdown");
        }
    })
}

function loadItemsForCriticalLevelPercentageSettings(withCriticalLevelPercentage, table) {
    const date = moment(new Date()).unix();
    let title = withCriticalLevelPercentage === 1 ? "ITEMS WITH CRITICAL LEVEL PERCENTAGE" : "ITEMS WITHOUT CRITICAL LEVEL PERCENTAGE";
    title += " " + date;
    dtableCritLevelSettings = $("#" + table).DataTable({
        dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
            "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
            "<'row'<'col-12'tr>><'row'<'col-12'i>>",
        buttons: [
            {
                extend: 'excel',
                text: 'EXCEL',
                title: title,
            },
            {
                extend: 'pdf',
                text: 'PDF',
                title: title,
                customize: function (doc) {
                    doc.content[1].table.widths = ["20%", "60%", "20%"];
                    doc.defaultStyle.alignment = 'left';
                    doc.styles.tableHeader.alignment = 'left';
                }
            }
        ],
        serverSide: false,
        processing: true,
        destroy: true,
        autoWidth: false,
        ajax: {
            url: base_url + "dashboard/get_inventory_items_form_critical_level_percentage_setting/" + withCriticalLevelPercentage,
            type: "post",
            dataType: "json"
        }, columns: [
            {data: 'sku', width: "20%"},
            {data: 'name', width: "50%"},
            {
                data: 'reorder_qty',
                width: "20%",
                render: function (data) {
                    return data || "--";
                }
            },
        ],
        order: [[1, "asc"]],
        initComplete: function () {
            $("#" + table + "_filter input[type='search']")
                .removeClass("form-control-sm");

            const dropdown = '' +
                '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                '             data-dropdown-toggle="hover" aria-expanded="true">' +
                '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                '                EXPORT AS' +
                '            </button>' +
                '            <div class="m-dropdown__wrapper">' +
                '                <div class="m-dropdown__inner">' +
                '                    <div class="m-dropdown__body">' +
                '                        <div class="m-dropdown__content">' +
                '                            <ul class="m-nav">' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'excel\', \'critical level\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                           Excel File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                                <li class="m-nav__item">' +
                '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                '                                       onclick="exportAs(\'pdf\', \'critical level\'); return false;" class="m-nav__link">' +
                '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                '                                          PDF File' +
                '                                        </span>' +
                '                                    </a>' +
                '                                </li>' +
                '                            </ul>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>';

            $(dropdown).appendTo("#" + table + "_wrapper .exportDropdown");
        }
    })
}

function exportAs(type, dt) {
    const dropdown = $(".m-dropdown__toggle.export-as");
    dropdown.addClass("m-btn--custom m-loader m-loader--light m-loader--left");
    let clearHere = true;

    let dataTable = null;
    switch (dt) {
        case 'min':
            dataTable = dtableMinQtySettings;
            break;
        case 'max':
            dataTable = dtableMaxQtySettings;
            break;
        case 'reorder':
            dataTable = dtableReorderQtySettings;
            break;
        case 'critical level':
            dataTable = dtableCritLevelSettings;
            break;
        case 'nearing minimum':
            dataTable = dtItemsNearingForMinQty;
            clearHere = false;
            break;
        case 'storage life':
            dataTable = dtStorageLife;
            clearHere = false;
            break;
    }

    setTimeout(() => {
        switch (type) {
            case "excel":
                dataTable.button(".buttons-excel").trigger();
                break;
            case "pdf":
                dataTable.button(".buttons-pdf").trigger();
                break;
        }

        if (clearHere) {
            dropdown.removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
        }
    }, 150);
}

function openModalForStockStorageLifeItems() {
    $("#tbl-items-for-stock-storage-life").dataTable().fnClearTable();
    loadItemsForStockStorageLife();
    $("#inventory-items-for-stock-storage-life").modal("show");
}

$("#inventory-items-for-stock-storage-life")
    .on("shown.bs.modal", function (e) {
    });

/* LOAD COLUMN HEADERS FOR TABLE OF STOCK STORAGE LIFE ITEMS */
const tableName = "#tbl-items-for-stock-storage-life";
let cols;
$.ajax({
    url: base_url + "dashboard/get_inventory_items_header_for_stock_storage_life",
    type: "GET",
    dataType: "JSON",
    success: function (data) {
        let str;
        $(tableName + '>thead>tr').empty();
        $.each(data, function (k, colObj) {
            str = '<th>' + colObj.name + '</th>';
            $(str).appendTo(tableName + '>thead>tr');
        });
        cols = data;
    },
});

/* LOAD COLUMN HEADERS FOR TABLE OF STOCK STORAGE LIFE ITEMS */

function loadItemsForStockStorageLife() {
    const date = moment(new Date()).unix();
    let title = "Inventory Items-Stock Storage Life Report";
    title += " " + date;

    dtStorageLife = $(tableName)
        .DataTable({
            dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
                "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
                "<'row'<'col-12'rt>><'row'<'col-12'i>>",
            ajax: {
                url: base_url + "dashboard/get_inventory_items_for_stock_storage_life",
                type: "POST",
                dataType: "JSON",
                data: function (d) {
                    d.initial_search = $("#search-inventory-inv-stock-storage-life").val();
                    d.filter = filter_inv_stock_storage_life;
                    d.sort = sort_inv_stock_storage_life;
                }
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'EXCEL',
                    title: title.toUpperCase(),
                    action: function (e, dt, node, config) {
                        getExportData(e, dt, node, config, this, dtStorageLife,
                            `${base_url + 'dashboard/get_inventory_items_for_stock_storage_life/1'}`, 'excelHtml5')
                            .then(() => {
                                $(".m-dropdown__toggle.export-as", document)
                                    .removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                            });
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: title.toUpperCase(),
                    customize: function (doc) {
                        // doc.content[1].table.widths = ["20%", "60%", "20%"];
                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                    },
                    action: function (e, dt, node, config) {
                        getExportData(e, dt, node, config, this, dtStorageLife,
                            `${base_url + 'dashboard/get_inventory_items_for_stock_storage_life/1'}`, 'pdfHtml5')
                            .then(() => {
                                $(".m-dropdown__toggle.export-as", document)
                                    .removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                            });
                    }
                }
            ],
            serverSide: true,
            processing: true,
            destroy: true,
            autoWidth: false,
            columns: cols,
            ordering: false,
            pageLength: 10,
            initComplete: function () {
                $("#tbl-items-for-stock-storage-life_filter input[type='search']")
                    .removeClass("form-control-sm");

                const dropdown = '' +
                    '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                    '             data-dropdown-toggle="hover" aria-expanded="true">' +
                    '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                    '                EXPORT AS' +
                    '            </button>' +
                    '            <div class="m-dropdown__wrapper">' +
                    '                <div class="m-dropdown__inner">' +
                    '                    <div class="m-dropdown__body">' +
                    '                        <div class="m-dropdown__content">' +
                    '                            <ul class="m-nav">' +
                    '                                <li class="m-nav__item">' +
                    '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                    '                                       onclick="exportAs(\'excel\', \'storage life\'); return false;" class="m-nav__link">' +
                    '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                    '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                    '                                           Excel File' +
                    '                                        </span>' +
                    '                                    </a>' +
                    '                                </li>' +
                    '                                <li class="m-nav__item">' +
                    '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                    '                                       onclick="exportAs(\'pdf\', \'storage life\'); return false;" class="m-nav__link">' +
                    '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                    '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                    '                                          PDF File' +
                    '                                        </span>' +
                    '                                    </a>' +
                    '                                </li>' +
                    '                            </ul>' +
                    '                        </div>' +
                    '                    </div>' +
                    '                </div>' +
                    '            </div>' +
                    '        </div>';

                $(dropdown).appendTo("#tbl-items-for-stock-storage-life_wrapper .exportDropdown");

                let search_thread = null;
                $("#tbl-items-for-stock-storage-life_filter input")
                    .unbind()
                    .bind("input", function (e) {
                        clearTimeout(search_thread);
                        search_thread = setTimeout(function () {
                            const elem = $("#tbl-items-for-stock-storage-life_filter input");
                            return dtStorageLife.search($(elem).val()).draw();
                        }, 1000);
                    });
            }
        });
}

// END GRAPH FOR MIN. QTY SETTING

$("#inventory-items-for-nearing-min-qty")
    .on('shown.bs.modal', function (e) {
        loadItemsForNearingMinQty();
    });

function loadItemsForNearingMinQty() {
    const date = moment(new Date()).unix();
    let title = "Inventory Items-Nearing Minimum Qty. Report";
    title += " " + date;

    dtItemsNearingForMinQty = $("#tbl-items-for-nearing-min-qty")
        .DataTable({
            dom: "<'row'<'col-xl-6 col-lg-6 col-md-6 col-sm-12 exportDropdown'><'col-xl-6 col-lg-6 col-md-6 col-sm-12'f>>" +
                "<'row mt-3'<'col-xl-6 col-lg-6 col-md-6 col-sm-12'l><'col-xl-6 col-lg-6 col-md-6 col-sm-12'p>>" +
                "<'row'<'col-12'rt>><'row'<'col-12'i>>",
            ajax: {
                url: base_url + "dashboard/get_inventory_items_for_nearing_min_qty",
                type: "POST",
                dataType: "JSON",
                data: function (d) {
                    d.initial_search = $("#search-inventory-inv-nearing-min-qty").val();
                    d.filter = filter_inv_nearing_min_qty;
                    d.sort = sort_inv_nearing_min_qty;
                }
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'EXCEL',
                    title: title.toUpperCase(),
                    className: "btn btn-warning btn-small text-white",
                    action: function (e, dt, node, config) {
                        getExportData(e, dt, node, config, this, dtItemsNearingForMinQty,
                            `${base_url + 'dashboard/get_inventory_items_for_nearing_min_qty/1'}`, 'excelHtml5')
                            .then(() => {
                                $(".m-dropdown__toggle.export-as", document)
                                    .removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                            });
                    }
                },
                {
                    extend: 'pdf',
                    text: 'PDF',
                    title: title.toUpperCase(),
                    className: "btn btn-warning btn-small text-white",
                    customize: function (doc) {
                        doc.content[1].table.widths = ["20%", "40%", "20%", "20%"];
                        doc.defaultStyle.alignment = 'left';
                        doc.styles.tableHeader.alignment = 'left';
                    },
                    action: function (e, dt, node, config) {
                        getExportData(e, dt, node, config, this, dtItemsNearingForMinQty,
                            `${base_url + 'dashboard/get_inventory_items_for_nearing_min_qty/1'}`, 'pdfHtml5')
                            .then(() => {
                                $(".m-dropdown__toggle.export-as", document)
                                    .removeClass("m-btn--custom m-loader m-loader--light m-loader--left");
                            });
                    }
                }
            ],
            columns: [
                {data: "sku", width: "15%"},
                {data: "name"},
                {
                    data: "qty",
                    width: "15%",
                    render: function (data) {
                        return parseFloat(data).toFixed(2);
                    }
                },
                {data: "min_qty", width: "15%"},
            ],
            serverSide: true,
            processing: true,
            destroy: true,
            autoWidth: false,
            ordering: false,
            pageLength: 10,
            initComplete: function () {
                $("#tbl-items-for-nearing-min-qty_filter input[type='search']")
                    .removeClass("form-control-sm");

                const dropdown = '' +
                    '       <div class="m-dropdown m-dropdown--inline m-dropdown--align-left" ' +
                    '             data-dropdown-toggle="hover" aria-expanded="true">' +
                    '            <button class="m-dropdown__toggle btn btn-success dropdown-toggle export-as">' +
                    '                EXPORT AS' +
                    '            </button>' +
                    '            <div class="m-dropdown__wrapper">' +
                    '                <div class="m-dropdown__inner">' +
                    '                    <div class="m-dropdown__body">' +
                    '                        <div class="m-dropdown__content">' +
                    '                            <ul class="m-nav">' +
                    '                                <li class="m-nav__item">' +
                    '                                    <a id="export-as-excel" style="cursor:pointer;" ' +
                    '                                       onclick="exportAs(\'excel\', \'nearing minimum\'); return false;" class="m-nav__link">' +
                    '                                        <i class="m-nav__link-icon fa fa-file-excel-o m--font-success"></i>' +
                    '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                    '                                           Excel File' +
                    '                                        </span>' +
                    '                                    </a>' +
                    '                                </li>' +
                    '                                <li class="m-nav__item">' +
                    '                                    <a id="export-as-pdf" style="cursor:pointer;" ' +
                    '                                       onclick="exportAs(\'pdf\', \'nearing minimum\'); return false;" class="m-nav__link">' +
                    '                                        <i class="m-nav__link-icon fa fa-file-pdf-o m--font-danger"></i>' +
                    '                                        <span class="m-nav__link-text" style="text-transform: none;">' +
                    '                                          PDF File' +
                    '                                        </span>' +
                    '                                    </a>' +
                    '                                </li>' +
                    '                            </ul>' +
                    '                        </div>' +
                    '                    </div>' +
                    '                </div>' +
                    '            </div>' +
                    '        </div>';

                $(dropdown).appendTo("#tbl-items-for-nearing-min-qty_wrapper .exportDropdown");

                let search_thread = null;
                $("#tbl-items-for-nearing-min-qty_filter input")
                    .unbind()
                    .bind("input", function (e) {
                        clearTimeout(search_thread);
                        search_thread = setTimeout(function () {
                            const elem = $("#tbl-items-for-nearing-min-qty_filter input");
                            return dtItemsNearingForMinQty.search($(elem).val()).draw();
                        }, 1000);
                    });
            }
        });
}

function openModalForMinQtySettingItemList(withMinQty = 1) {
    const modal = withMinQty === 1 ? $("#inventory_items_without_min_qty") : $("#inventory_items_with_min_qty");
    const title = withMinQty === 1 ? "<span class='m--font-boldest'>" +
        "   Items <span style='color: #1E88E5;'>with</span> Minimum Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Minimum Qty." +
        "</span>";
    modal.find(".modal-title").empty().html(title);
    _withMinQty = withMinQty;
    $(modal).modal("show");
}

function openModalForMaxQtySettingItemList(withMaxQty = 1) {
    const modal = withMaxQty === 1 ? $("#inventory_items_without_max_qty") : $("#inventory_items_with_max_qty");
    const title = withMaxQty === 1 ? "<span class='m--font-boldest'>" +
        "   Items <span style='color: #1E88E5;'>with</span> Minimum Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Minimum Qty." +
        "</span>";
    modal.find(".modal-title").empty().html(title);
    _withMaxQty = withMaxQty;
    $(modal).modal("show");
}

function openModalForReorderQtySettingItemList(withReorderQty = 1) {
    const modal = withReorderQty === 1 ? $("#inventory_items_without_reorder_qty") : $("#inventory_items_with_reorder_qty");
    const title = withReorderQty === 1 ? "<span class='m--font-boldest'>" +
        "   Items <span style='color: #1E88E5;'>with</span> Reorder Qty.</span>" : "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Reorder Qty." +
        "</span>";
    modal.find(".modal-title").empty().html(title);
    _withReorderQty = withReorderQty;
    $(modal).modal("show");
}

function openModalForCriticalLevelPercentageSettingItemList(withCritLevelPercentage = 1) {
    const modal = withCritLevelPercentage === 1 ? $("#inventory_items_without_critical_level_percentage") : $("#inventory_items_with_critical_level_percentage");
    const title = withCritLevelPercentage === 1 ? "<span class='m--font-boldest'>" +
        "Items <span style='color: #1E88E5;'>with</span> Critical Level Percentage</span>" :
        "<span class='m--font-boldest'>Items <span style='color: #F44336;'>without</span> Critical Level Percentage" +
        "</span>";
    modal.find(".modal-title").empty().html(title);
    _withCritLevelPercentage = withCritLevelPercentage;
    $(modal).modal("show");
}

async function getExportData(e, dt, node, config, self, dtTable, url, type) {
    const data = dtTable.ajax.params();
    const result = await $.ajax({
        url,
        type: "POST",
        dataType: "JSON",
        data,
        success: function (response) {
            dt.rows().remove();
            dt.rows.add(response.data).draw();
            $.fn.dataTable.ext.buttons[type].action.call(self, e, dt, node, config);
        }
    });

    return result;
	
}

	// start:: chart average inventory
	am4core.ready(function() {

	// Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end
	
	var chart = am4core.create("chart-average_inventory", am4charts.XYChart);
	chart.paddingRight = 20;
	
	$.ajax({
		url: base_url + "dashboard/get_average_inventory_graph",
        type: "POST",
        dataType: "JSON",
		async: true,
        success: function (data) {
			chart.data = data;
		}
	});
	
	// Set input format for the dates
	chart.dateFormatter.inputDateFormat = "yyyy-MM-dd";

	// Create axes
	var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

	// Create series
	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.valueY = "value";
	series.dataFields.dateX = "date";
	series.tooltipText = "{value}"
	series.strokeWidth = 2;
	series.minBulletDistance = 15;

	// Drop-shaped tooltips
	series.tooltip.background.cornerRadius = 20;
	series.tooltip.background.strokeOpacity = 0;
	series.tooltip.pointerOrientation = "vertical";
	series.tooltip.label.minWidth = 40;
	series.tooltip.label.minHeight = 40;
	series.tooltip.label.textAlign = "middle";
	series.tooltip.label.textValign = "middle";

	// Make bullets grow on hover
	var bullet = series.bullets.push(new am4charts.CircleBullet());
	bullet.circle.strokeWidth = 2;
	bullet.circle.radius = 4;
	bullet.circle.fill = am4core.color("#fff");

	var bullethover = bullet.states.create("hover");
	bullethover.properties.scale = 1.3;

	// Make a panning cursor
	chart.cursor = new am4charts.XYCursor();
	chart.cursor.behavior = "panXY";
	chart.cursor.xAxis = dateAxis;
	chart.cursor.snapToSeries = series;

	// Create vertical scrollbar and place it before the value axis
	chart.scrollbarY = new am4core.Scrollbar();
	chart.scrollbarY.parent = chart.leftAxesContainer;
	chart.scrollbarY.toBack();

	// Create a horizontal scrollbar with previe and place it underneath the date axis
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);
	chart.scrollbarX.parent = chart.bottomAxesContainer;

	dateAxis.start = 0.79;
	dateAxis.keepSelection = true;

	
	}); // end am4core.ready()

	// end:: chart average inventory


	// start:: chart cancelled transactions

	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		// Create chart instance
		var chart = am4core.create("chart-cancelled-transactions", am4charts.XYChart);
		
		// Increase contrast by taking evey second color
		chart.colors.step = 2;
		
		// Add data
		$.ajax({
			url: base_url + "dashboard/get_cancelled_transactions",
			type: "POST",
			dataType: "JSON",
			async: true,
			success: function (data) {
				chart.data = data;
			}
		});
		
		// Create axes
		var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
		dateAxis.renderer.minGridDistance = 50;
		
		// Create series
		function createAxisAndSeries(field, name, opposite, bullet) {
		  var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		  if(chart.yAxes.indexOf(valueAxis) != 0){
			  valueAxis.syncWithAxis = chart.yAxes.getIndex(0);
		  }
		  
		  var series = chart.series.push(new am4charts.LineSeries());
		  series.dataFields.valueY = field;
		  series.dataFields.dateX = "date";
		  series.strokeWidth = 2;
		  series.yAxis = valueAxis;
		  series.name = name;
		  series.tooltipText = "{name}: [bold]{valueY}[/]";
		  series.tensionX = 0.8;
		  series.showOnInit = true;
		  
		  var interfaceColors = new am4core.InterfaceColorSet();
		  
		  switch(bullet) {
			case "triangle":
			  var bullet = series.bullets.push(new am4charts.Bullet());
			  bullet.width = 12;
			  bullet.height = 12;
			  bullet.horizontalCenter = "middle";
			  bullet.verticalCenter = "middle";
			  
			  var triangle = bullet.createChild(am4core.Triangle);
			  triangle.stroke = interfaceColors.getFor("background");
			  triangle.strokeWidth = 2;
			  triangle.direction = "top";
			  triangle.width = 12;
			  triangle.height = 12;
			  break;
			case "rectangle":
			  var bullet = series.bullets.push(new am4charts.Bullet());
			  bullet.width = 10;
			  bullet.height = 10;
			  bullet.horizontalCenter = "middle";
			  bullet.verticalCenter = "middle";
			  
			  var rectangle = bullet.createChild(am4core.Rectangle);
			  rectangle.stroke = interfaceColors.getFor("background");
			  rectangle.strokeWidth = 2;
			  rectangle.width = 10;
			  rectangle.height = 10;
			  break;
			default:
			  var bullet = series.bullets.push(new am4charts.CircleBullet());
			  bullet.circle.stroke = interfaceColors.getFor("background");
			  bullet.circle.strokeWidth = 2;
			break;
		  }
		  
		  valueAxis.renderer.line.strokeOpacity = 1;
		  valueAxis.renderer.line.strokeWidth = 2;
		  valueAxis.renderer.line.stroke = series.stroke;
		  valueAxis.renderer.labels.template.fill = series.stroke;
		  valueAxis.renderer.opposite = opposite;
		}
		
		createAxisAndSeries("cancelledIssuance", "Cancelled Issuances", false, "circle");
		createAxisAndSeries("cancelledReceiving", "Cancelled Receivings", true, "triangle");
		
		// Add legend
		chart.legend = new am4charts.Legend();
		
		// Add cursor
		chart.cursor = new am4charts.XYCursor();
		
		}); // end am4core.ready()

		// end:: chart cancelled transactions
// receive daterangepicker
var fromDate1, toDate1;
var receiveDateRange = $('#receive_daterange');
receiveDateRange.daterangepicker({
    buttonClasses: 'm-btn btn',
    applyClass: 'btn-primary',
    cancelClass: 'btn-secondary',
    opens: "center",
    ranges: {
      'Today': [moment(), moment()],
      'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      'Last 7 Days': [moment().subtract(6, 'days'), moment()],
      'Last 30 Days': [moment().subtract(29, 'days'), moment()],
      'Last Year': [moment().subtract(364, 'days'), moment()],
      'All Time': [moment('1999-01-01', 'YYYY-MM-DD'), moment()],
  },
    locale: {
        format: 'YYYY-MM-DD',
        cancelLabel: 'Clear',
    }
});
receiveDateRange.on('apply.daterangepicker', function (ev, picker) {
    fromDate1 = picker.startDate.format('YYYY-MM-DD');
    toDate1 = picker.endDate.format('YYYY-MM-DD');
    receiveitemsTable.ajax.reload();
});
receiveDateRange.on('cancel.daterangepicker', function (ev, picker) {
  $(this).val('');
  fromDate1 = ''; 
  toDate1 = '';
    receiveitemsTable.ajax.reload();
});

// <!-- start::Total Receiving transactions graph -->
var typingTimer; //timer identifier
var doneTypingInterval = 1000;
var $receiveInput = $('#receiveInput');
let inputVal = '';
var receiveitemsTable = $('#receiving1').DataTable({
  "processing": true,
  "serverSide": true,
  "searching": false,
  "lengthChange": false,
  "scrollY": "410px",
  ajax: {
    url:  base_url + "dashboard/get_total_receiving_perItem",
    type: 'post',
    dataType: 'JSON',  
    data: function(data) {
      data.search['value'] = inputVal;
      data.fromDate = fromDate1;
      data.toDate = toDate1;
    } 
  },
  columns: [
    { data: 'sku' },
    { data: 'name'},
    { data: 'total_quantity', className: 'text-right',},
],
});
$receiveInput.on('keyup', function() {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping1, doneTypingInterval);
});
$receiveInput.on('keydown', function() {
  clearTimeout(typingTimer);
});
function doneTyping1() {
  inputVal = $receiveInput.val();
  receiveitemsTable.ajax.reload();
}



// <!-- end::Total receiving transactions graph -->
// issuance daterangepicker
var fromDate2, toDate2;
var issuanceDateRange = $('#issuance_daterange');
issuanceDateRange.daterangepicker({
  buttonClasses: 'm-btn btn',
  applyClass: 'btn-primary',
  cancelClass: 'btn-secondary',
  ranges: {
    'Today': [moment(), moment()],
    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
    'Last Year': [moment().subtract(364, 'days'), moment()],
    'All Time': [moment('1999-01-01', 'YYYY-MM-DD'), moment()],
},
  locale: {
    format: 'YYYY-MM-DD',
    cancelLabel: 'Clear',
}
});

issuanceDateRange.on('apply.daterangepicker', function (ev, picker) {
  fromDate2 = picker.startDate.format('YYYY-MM-DD');
  toDate2 = picker.endDate.format('YYYY-MM-DD');
  issuanceitemsTable.ajax.reload();
});
issuanceDateRange.on('cancel.daterangepicker', function (ev, picker) {
  $(this).val('');
  fromDate2 = ''; 
  toDate2 = '';
  issuanceitemsTable.ajax.reload();
});


    // <!-- start::Total issuance transactions graph -->
    var $issuanceInput = $('#issuanceInput');

    var issuanceitemsTable = $('#issuance1').DataTable({
      "processing": true,
      "serverSide": true,
      "searching": false,
      "lengthChange": false,
      "scrollY": "410px",
      ajax: {
        url:  base_url + "dashboard/get_total_issuance_perItem",
        type: 'post',
        dataType: 'JSON',
        data: function(data) {
        data.search['value'] = inputVal;
        data.fromDate = fromDate2;
        data.toDate = toDate2;
        
      }, 
      },
      columns: [
        { data: 'sku' },
        { data: 'name'},
        { data: 'total_quantity', className: 'text-right',},
    ],
    });
    $issuanceInput.on('keyup', function() {
      clearTimeout(typingTimer);
      typingTimer = setTimeout(doneTyping2, doneTypingInterval);
    });
    $issuanceInput.on('keydown', function() {
      clearTimeout(typingTimer);
    });
    function doneTyping2() {
      inputVal = $issuanceInput.val();
      issuanceitemsTable.ajax.reload();
    }





		// start::active transactions
		am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end
						
			var chart = am4core.create('chart-active-transactions', am4charts.XYChart)
			chart.colors.step = 2;
			
			chart.legend = new am4charts.Legend()
			chart.legend.position = 'top'
			chart.legend.paddingBottom = 20
			chart.legend.labels.template.maxWidth = 95
			
			var xAxis = chart.xAxes.push(new am4charts.CategoryAxis())
			xAxis.dataFields.category = 'category'
			xAxis.renderer.cellStartLocation = 0.1
			xAxis.renderer.cellEndLocation = 0.9
			xAxis.renderer.grid.template.location = 0;
			
			var yAxis = chart.yAxes.push(new am4charts.ValueAxis());
			yAxis.min = 0;
			
			function createSeries(value, name) {
				var series = chart.series.push(new am4charts.ColumnSeries())
				series.dataFields.valueY = value
				series.dataFields.categoryX = 'category'
				series.name = name
			
				series.events.on("hidden", arrangeColumns);
				series.events.on("shown", arrangeColumns);
			
				var bullet = series.bullets.push(new am4charts.LabelBullet())
				bullet.interactionsEnabled = false
				bullet.dy = 30;
				bullet.label.text = '{valueY}'
				bullet.label.fill = am4core.color('#ffffff')
			
				return series;
			}
			
			$.ajax({
				url: base_url + "dashboard/get_active_transactions",
				type: "POST",
				dataType: "JSON",
				async: true,
				success: function (data) {
					chart.data = data;
					
				}
			});
			
			createSeries('issuance', 'Issuance');
			createSeries('receiving', 'Receiving');
			
			function arrangeColumns() {
			
				var series = chart.series.getIndex(0);
			
				var w = 1 - xAxis.renderer.cellStartLocation - (1 - xAxis.renderer.cellEndLocation);
				if (series.dataItems.length > 1) {
					var x0 = xAxis.getX(series.dataItems.getIndex(0), "categoryX");
					var x1 = xAxis.getX(series.dataItems.getIndex(1), "categoryX");
					var delta = ((x1 - x0) / chart.series.length) * w;
					if (am4core.isNumber(delta)) {
						var middle = chart.series.length / 2;
			
						var newIndex = 0;
						chart.series.each(function(series) {
							if (!series.isHidden && !series.isHiding) {
								series.dummyData = newIndex;
								newIndex++;
							}
							else {
								series.dummyData = chart.series.indexOf(series);
							}
						})
						var visibleCount = newIndex;
						var newMiddle = visibleCount / 2;
			
						chart.series.each(function(series) {
							var trueIndex = chart.series.indexOf(series);
							var newIndex = series.dummyData;
			
							var dx = (newIndex - trueIndex + middle - newMiddle) * delta
			
							series.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
							series.bulletsContainer.animate({ property: "dx", to: dx }, series.interpolationDuration, series.interpolationEasing);
						})
					}
				}
			}
			
			}); // end am4core.ready()

			//end::active transactions
