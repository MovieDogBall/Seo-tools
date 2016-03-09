/**
 * Created by Yaroslav on 08.12.2015.
 *
 * Code for second tab
 *
 */
var words = [];
var numSent = [];
var text = '';

function ajaxAnalyzer() {
    /**
     * Clear our table, graphs, textarea
     */
    $('#table-found-words').DataTable().clear().draw();
    $('#table-not-found-words').DataTable().clear().draw();
    $('#table-analyze-sentences').DataTable().clear().draw();
    $('.text-info p').empty();

    /**
     * AJAX code to submit form.
     */

    $.ajax({
        type: "POST",
        url: '/web/index.php?r=site%2Fanalyzer',
        data: {
            sentences: $("#sentences").val(),
            keywordWords: $("#keyword-words").val(),
            _csrf: '<?=Yii::$app->request->getCsrfToken()?>'
        },
        cache: false,
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            var senten = [];
            var average = [];
            var mediana = [];

            if (obj.success == true) {

                //Draw table with founded words
                jQuery.each(obj.resultPositive, function (i, val) {
                    $('#table-found-words').DataTable().row.add([i, val]).draw();
                    words.push(i);
                });

                // Draw table with not-founded words
                jQuery.each(obj.resultNegative, function (i) {
                    $('#table-not-found-words').DataTable().row.add([i]).draw();
                });

                numSent = [];
                senten = [];
                //Draw analyze sentences table
                jQuery.each(obj.analyzeSentences, function (num, sentences) {
                    numSent.push(num);
                    senten.push(sentences);
                    $('#table-analyze-sentences').DataTable().row.add([num, sentences]).draw();
                });

                average = [];
                mediana = [];
                //Get average and median value for line chart
                jQuery.each(obj.averageAndMediana, function (ave, med) {
                    jQuery.each(numSent, function () {
                        average.push(ave);
                        mediana.push(med);
                    });
                });

                //options for chart
                var lineChartData = {
                    labels: numSent,
                    datasets: [
                        {
                            label: "Words",
                            fillColor: "rgba(220,220,220,0.2)",
                            strokeColor: "rgba(220,220,220,1)",
                            pointColor: "rgba(220,220,220,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(220,220,220,1)",
                            data: senten
                        },
                        {
                            label: "Average",
                            fillColor: "rgba(151,187,205,0.2)",
                            strokeColor: "rgba(151,187,205,1)",
                            pointColor: "rgba(151,187,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: average
                        },
                        {
                            label: "Mediana",
                            fillColor: "rgba(120,111,205,0.2)",
                            strokeColor: "rgba(120,111,205,1)",
                            pointColor: "rgba(120,111,205,1)",
                            pointStrokeColor: "#fff",
                            pointHighlightFill: "#fff",
                            pointHighlightStroke: "rgba(151,187,205,1)",
                            data: mediana
                        }
                    ]
                }

                var ctx = document.getElementById("canvas").getContext("2d");

                window.myLine = new Chart(ctx).Line(lineChartData, {
                    responsive: true,
                    multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>"
                });

                text = '';
                //Get full text for right column
                jQuery.each(obj.fullText, function (numSe, senten) {
                    text += senten;
                });


                //Выделение текста по клику его номера на графике
                canvas.onclick = function (evt) {
                    var activePoints = window.myLine.getPointsAtEvent(evt); // => activePoints is an array of points on the canvas that are at the same position as the click event.
                    var elemSent = $("#sentences-" + activePoints[0].label)[0];

                    if (elemSent.className == "founded") {
                        elemSent.className = "";
                    } else {
                        elemSent.className = "founded";
                    }
                };

            } else {
                jQuery.each(obj.errors, function (i) {
                    alert(i);
                });
            }
            
            $(".text-info p").append(text);
            $('.text-info p').html(highlight(
                $('.text-info p').html(), // текст для поиска
                words, // слова для обрамления
                'strong' // тег обрамления
            ));

        }
    });

    return false;
}

function highlight(text, word, tag) {

    // Default tag if no tag is provided
    tag = tag || 'span';

    var i, len = word.length, re;
    for (i = 0; i < len; i++) {
        // Global regex to highlight all matches
        re = new RegExp(word[i], 'gi');
        if (re.test(text)) {
            text = text.replace(re, '<' + tag + ' class="highlight">$&</' + tag + '>');
        }
    }
    return text;
}

$(document).ready(function () {
    $('#table-found-words').DataTable(
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

    $('#table-not-found-words').DataTable(
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
