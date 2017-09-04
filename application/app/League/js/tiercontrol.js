function tierControl() {
    tier = $('#tier-control').val()

    if (tier == 'all') {
        $('[tier]').show();
    } else {
        $('[tier]').hide();
        $("[tier='"+tier+"']").show();
    }
}

tierControl();

$('#tier-control').on('change', function(){
    tierControl();
})