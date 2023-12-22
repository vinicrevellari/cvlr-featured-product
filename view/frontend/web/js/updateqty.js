require(['jquery'], function($) {
    $(document).ready(function() {
        function updateQtyAvailable() {
            $.ajax({
                url: '/featuredproduct/index/ajax',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#qty-available').text(response.qty_available);
                },
                error: function() {
                    console.error('Erro ao obter a quantidade disponível.');
                }
            });
        }
        updateQtyAvailable();
        setInterval(updateQtyAvailable, 20000);
    });
});
