$(document).ready(function () {
    $('form').on('submit', function (e) { //on click submit
        var id = $(this).attr('data-counter');
           //$("#submit_"+id).css('background-color','#32CD32');
        e.preventDefault();
        $.ajax({
            type: "post",
            data: $(this).serialize(),
            url: "update.php",
            success: function () {
                var counter = parseInt($("#poke_" + id).val());
                counter++;
                $("#poke_" + id).val(counter);
                $("#msg").show();
                $("#msg").fadeOut(1500);
            }
        });
        return false;
    });
});