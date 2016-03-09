/**
 * Created by Yaroslav on 09.12.2015.
 */

(function($){
    jQuery.fn.lightTabs = function(){

        var createTabs = function(){
            var tabs = this;
            var i = 0;

            showPage = function(i){
                $(tabs).children("div").children("div").hide();
                $(tabs).children("#tab-"+i).children(".row").show();
                $('#ulTab').children("li").removeClass("active");
                $('#ulTab').children("li").eq(i).addClass("active");
            }

            showPage(0);

            $('#ulTab').children("li").each(function(index, element){
                $(element).attr("data-page", i);
                i++;
            });

            $('#ulTab').children("li").click(function(){
                showPage(parseInt($(this).attr("data-page")));
            });
        };
        return this.each(createTabs);
    };
})(jQuery);

$(document).ready(function(){
    $(".tabs").lightTabs();
});