/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function () {
   // Editable fields
   $('.etColumn').click(function(){
      var t = $(this);
      if(t.hasClass("etEditableField")){
          console.log("editableField");
          if(t.blur()){
              console.log("blur");
          }
      }
   });
});
