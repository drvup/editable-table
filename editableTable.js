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
            var jsonKeys = t.data("tablekeys");
            var postArr = {
                columnName: t.data("colname"),
                tableKeys: JSON.stringify(jsonKeys),
                newValue: t.val(),
                token: t.data("token")
            };
            $.post("index.php?edit=true", postArr).done(function(ret){
                console.log("blur");
            });
        });      
    });
});