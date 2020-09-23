$(document).ready(function () {
    var current_session=$(".my-porfile").attr("data-id");
    $.ajax({
        method:"POST",
        url: "controller/chat action.php",
        data: {current_session:current_session,action:"getUsers"},
        dataType: "json",
        success: function (response) {
            $("#user-list").html(response.users);
        }
    }); 

});