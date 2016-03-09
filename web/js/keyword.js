/**
 * Created by Yaroslav on 08.12.2015.
 */
var wordsResult = [];

function ajaxKeyword() {
    $('#table-result').DataTable().clear().draw();
// AJAX code to submit form.
    $.ajax({
        type: "POST",
        url: '/web/index.php?r=site%2Fkeyword',
        data: {
            phrases: $("#phrases").val(),
            length: $("#length").val(),
            count: $("#count").val(),
            negative: $("#negative").val(),
            _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
        },
        cache: false,
        success: function (data) {
            var obj = jQuery.parseJSON(data);

            if (obj.success == true) {
                jQuery.each(obj.result, function (i, val) {
                    wordsResult.push(i);
                    $('#table-result').DataTable().row.add([i, val]).draw();
                });
            } else {
                jQuery.each(obj.errors, function (i, val) {
                    alert(val);
                });
            }
        }
    });

    return false;
}

function copyAnalyzer() {
    var wordsString = wordsResult.toString();
    var words = wordsString.replace(/,/g, "\n")
    $('#keyword-words').append(words);
}

$(document).ready(function () {
    $('#table-result').DataTable(
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
            }
        });
});
