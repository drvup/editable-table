/**
 * Project 'editable-table'
 * This javascript file supplies the function to edit field-values in the editable-table.
 * You need jQuery 2 activate this function.  
 *
 * @category editableTable
 * @author dr_vup aka Cedric
 * @company FHR Websolutions GbR
 * @version 0.9
 * @date 28.12.2015
 */

$(document).ready(function () {
    // Editable fields
    $('.etEditableField').click(function(){
        var t = $(this);
            // Disable double click
            if(t.data("noDoubleClick") == true){
                return false;
            }else{
                t.data("noDoubleClick", true);
            }
            
        var safeHtml = t.html();
        var jsonKeys = t.data("tablekeys");
        var postArr = {
            columnName: t.data("colname"),
            tableKeys: JSON.stringify(jsonKeys),
            newValue: "",
            token: t.data("token")
        };
        // Change to input field
        t.html('<input type="text" value="' + t.text() + '">');
        //t.find("input").blur(function(){
        t.find("input").attr('size', t.find("input").val().length).focus().val(t.find("input").val()).blur(function(){
            var newVal = $(this).val();
            postArr.newValue = newVal;
            $.post("index.php?edit=true", postArr).done(function(ret){
                t.html(safeHtml);
                // overwrite new text
                t.text(newVal);
                t.data("noDoubleClick", false);
            });
            
        }).keypress(function(e){if(e.which==13)$(this).blur()}); 
    });
});