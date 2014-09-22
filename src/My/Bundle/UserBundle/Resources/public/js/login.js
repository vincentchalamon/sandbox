/**
 * Author: Vincent Chalamon <vincentchalamon@gmail.com>
 */
$(function () {
    $('form').on('submit', function () {
        Ladda.create($(':input:submit', $(this)).get(0)).start();
    });
});