var request;
$("#update").on('click',function(event){
 event.preventDefault();
 SendAjaxRequest();
})

function SendAjaxRequest()
{
    if(request)
    request.abort();
    //var serializedData = "";
    //var serializedData = new FormData();
    //serializedData.append('postTextArea', "aabbccc") ;
    //serializedData.append('mediaSelect', $('#fileSelect').val()) ;

    // setup some local variables
   var $form = $("Form");

// Let's select and cache all the fields
   var $inputs = $form.find("input");

// Serialize the data in the form
   // var serializedData = $form.find("input, textarea, #update").serialize();
  //var serializedData = $("#updateForm").serializeArray();
   //serializedData.push({name:$("#update").name,value:$("#update").value})
//var serializedData =  $form.find("input").serialize()

    // Let's disable the inputs for the duration of the Ajax request.
    // Note: we disable elements AFTER the form data has been serialized.
    // Disabled form elements will not be serialized.
    $inputs.prop("disabled", true);

    // Fire off the request to /form.php
    request = $.ajax({
        url:"./index.php?idPost=101",
        type:"POST",
       // data: serializedData,
       data: $('#updateForm').serialize(),
        //dataType:"text",
          processDate: false,
        contentType:false
    });

 // Callback handler that will be called on success
 request.done(function (response, textStatus, jqXHR){
    // Log a message to the console
    console.log("Hooray, it worked!");
    //$('body').html(response);
});

// Callback handler that will be called on failure
request.fail(function (jqXHR, textStatus, errorThrown){
    // Log the error to the console
    console.error(
        "The following error occurred: "+
        textStatus, errorThrown
    );
});

// Callback handler that will be called regardless
// if the request failed or succeeded
request.always(function () {
    // Reenable the inputs
    $inputs.prop("disabled", false);
});
}
