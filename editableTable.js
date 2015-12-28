/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    // Editable fields
    $('.etEditableField').click(function(){
        var t = $(this);
        t.blur(function(){
            var postArr = {
                columnName: t.data("colname"),
                tableKeys: t.data("tablekeys"),
                newValue: t.val(),
                token: t.data("token")
            };
            $.post("index.php?edit=true", postArr).done(function(ret){
                console.log("blur");
            });
        });      
    });
});