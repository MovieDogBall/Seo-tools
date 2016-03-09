/**
 * Created by Yaroslav on 08.12.2015.
 *
 * Code for second tab
 *
 */
var words = [];
var text = '';

function ajaxMedian() {
    /**
     * Clear our table, graphs, textarea
     */
    $('#table-median').DataTable().clear().draw();

    /**
     * AJAX code to submit form.
     */
    var articles = [];
    for (var n = 1; n < 11; n++) {
        articles.push($("#article-" + n).val())
    }

    $.ajax({
        type: "POST",
        url: '/web/index.php?r=site%2Fmedians',
        data: {
            ourArticle: $("#our-article").val(),
            keywordMedian: $("#keyword-median").val(),
            articles: articles,
            _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
        },
        cache: false,
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.success == true) {
                jQuery.each(obj.data, function (i, vals) {
                    $('#table-median').DataTable().row.add(vals).draw();
                });
            } else {
                jQuery.each(obj.errors, function (i) {
                    alert(i);
                });
            }
        }
    });

    return false;
}


$(document).ready(function () {
    $('#table-median').DataTable(
        {
            dom: 'T<"clear">lfrtip',
            "tableTools": {
                "aButtons": [
                    {
                        "sExtends": "copy",
                        "sButtonText": "Copy to clipboard",
                        "mColumns": [0]
                    }
                ]
            },
            columns: [
                { title: "Keyword" },
                { title: "1-st article" },
                { title: "2-st article" },
                { title: "3-st article" },
                { title: "4-st article" },
                { title: "5-st article" },
                { title: "6-st article" },
                { title: "7-st article" },
                { title: "8-st article" },
                { title: "9-st article" },
                { title: "10-st article" },
                { title: "AVRG" },
                { title: "Median" },
                { title: "Our article" }
            ]
        });
});
