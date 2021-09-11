$(document).ready(function() {
    var $grid = $('.dog-grid').masonry({
        itemSelector: '.dog-card',
        percentPosition: true,
        gutter: 16
    });
    $grid.imagesLoaded().progress( function() {
        $grid.masonry('layout');
    });
});
