/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
    // Editable fields
    $('.etEditableField').click(function(){
        var t = $(this);
        var safeHtml = t.html();
        var jsonKeys = t.data("tablekeys");
        var postArr = {
            columnName: t.data("colname"),
            tableKeys: JSON.stringify(jsonKeys),
            newValue: t.text(),
            token: t.data("token")
        };
        // Change to input field
        t.html('<input type="text" value="' + t.text() + '">');
        //t.find("input").blur(function(){
        t.find("input").attr('size', t.find("input").val().length).focus().val(t.find("input").val()).blur(function(){
            postArr.newValue = $(this).val();
            $.post("index.php?edit=true", postArr).done(function(ret){
                t.html(safeHtml);
                t.text = $(this).val();
            });
        });      
    });
});