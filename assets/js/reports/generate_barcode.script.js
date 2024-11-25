function print() {
    const content = $("#generated-bar-codes").html();
    let mywindow = window.open('', 'PRINT');

    mywindow.title = "Item Barcodes";
    mywindow.document.write(`<html>
                                <head>
                                    <link rel="stylesheet" href="${base_url}assets/plugins/bootstrap/bootstrap.min.css">
                                    <style type="text/css">
                                        #generated-bar-codes {
                                            display: grid;
                                            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
                                            justify-items: center;
                                            grid-gap: 24px;
                                        }
                                        
                                        #generated-bar-codes .card {
                                            margin-right: 4px;
                                            margin-bottom: 8px;
                                            min-width: 200px;
                                            max-width: 200px;
                                        }
                                        
                                        #generated-bar-codes .card p.title {
                                            margin-top: 8px;
                                            margin-bottom: 0;
                                            font-size: 14px;
                                            font-weight: 700;
                                        }
                                    
                                        #generated-bar-codes .card p.subtitle {
                                            margin-bottom: 0;
                                            margin-top: 0;
                                            font-weight: 500;
                                            line-height: 16px;
                                            font-size: 14px;
                                        }
                                        
                                        @media print {
                                            #generated-bar-codes .card {
                                                clear: both;
                                                page-break-before: always;
                                                page-break-after: always;
                                                page-break-inside: avoid;
                                            }                                        
                                        }
                                    </style>
                                </head>
                                <body onload="window.print();window.close()">
                                    <div id="generated-bar-codes" class="">${content}</div>                               
                                </body>
                             </html>`);

    mywindow.document.close(); // necessary for IE >= 10
    mywindow.focus(); // necessary for IE >= 10*/

    return true;
}

const getItems = function () {
    $.ajax({
        url: `${base_url}reports/barcode/get_items_for_barcode`,
        type: "POST",
        dataType: "JSON",
        data: {
            filter: $("#items").val()
        },
        success: function (response) {
            const items = response.data;
            $("#generated-bar-codes").empty();
            items.forEach((item) => {
                const template = `<div class="card p-2">
                                      <img id="barcode-${item.id}">
                                      <p class="title">${item.sku.toUpperCase()}</p>
                                      <p class="subtitle">${item.name.toUpperCase()}</p>
                                  </div>`;
                $("#generated-bar-codes").append(template);
                JsBarcode("#barcode-" + item.id, item.sku, {
                    width: 1,
                    height: 60,
                    fontSize: 12,
                    margin: 0,
                    displayValue: false,
                });
            });
        }
    });
};

getItems();

$("#items")
    .select2({
        allowClear: true,
        placeholder: "",
        ajax: {
            url: `${base_url}reports/barcode/get_items_for_select`,
            type: "GET",
            dataType: "JSON",
        },
        escapeMarkup: function (markup) {
            return markup;
        },
        templateResult: function (data) {
            return data.html;
        },
        templateSelection: function (data) {
            return data.text;
        }
    });
