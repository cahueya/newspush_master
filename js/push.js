$(function(){

    var url = window.location.pathname.toString();

    $("a[data-pane-toggle]").click(function(e){
        e.preventDefault();
        var paneTarget = $(this).attr('href');
        paneTarget = paneTarget.replace('#','');
        $(".store-pane").removeClass('active');
        $('a[data-pane-toggle]').parent().removeClass('active');
        $('#'+paneTarget).addClass("active");
        $(this).parent().addClass("active");
        localStorage.setItem("selectedTab", paneTarget);
        localStorage.setItem("selectedTabIndex",  $(this).parent().index());
        if(history.pushState) {
            history.pushState(null, null, '#'+paneTarget);
        }
        else {
            location.hash = '#'+paneTarget;
        }
    });

});

function updateActiveTab(historyPopEvent) {
    var url = window.location.pathname.toString();
    var hash = window.location.hash;
    var urlArray = url.split('/');

    if (hash) {
        $(".store-pane").removeClass('active');
        var toggle = $('a[data-pane-toggle]');
        toggle.parent().removeClass('active');

        var link = $('a[data-pane-toggle][href="'+hash+'"]');
        var paneTarget = hash.replace('#','');
        var paneTargetIndex = toggle.index( link );

        $('#'+paneTarget).addClass("active");
        $('a[data-pane-toggle]:eq('+paneTargetIndex+')').parent().addClass("active");

        window.location = '#';

        if(history.pushState) {
            history.pushState(null, null, hash);
        }
    }
}


if ($('a[data-pane-toggle]').length > 0) {
     updateActiveTab();

}
