$(document).ready(function () {
    var current_session=$("#current_session").attr("data-id");
    
    
    setTimeout(() => {
        if($("#default-list").hasClass('user-active')){
            $("#sendingMessages").hide(); 
        }
        $("#goback").click(function(){
            $(".userlist-panel").addClass("d-block");
            $(".chat-content").addClass("d-none"); 
            $(".userlist-panel").removeClass("d-none");
            $(".chat-content").removeClass("d-block");
            
            $("#default-list").addClass("user-active");
            $(".li_userlist").removeClass("user-active");
            $("#message-body-input").attr('value','');
            $("#sendingMessages").fadeOut(300);
            var chamingChat=`<img src="view/userpics/chaming.jpg" class="my-porfile-pic"><p><b>Chaming</b><div class='status-online'>Online</div></p>`;
            $("#messages").html('');
            $("#receiver_p").html(chamingChat);
        });
        $(".li_userlist").each(function(){
            var receiver=$(this).attr("data-id");
            $(this).click(function(){
                $("#default-list").removeClass("user-active");
                $(".li_userlist").removeClass("user-active");
                $(this).addClass("user-active");
                $("#message-body-input").attr('value','');
                $("#sendingMessages").fadeIn(300);
                $("#message-body-input").focus();
                $(".userlist-panel").addClass("d-none");
                $(".chat-content").addClass("d-block");
                $(".chat-content").removeClass("d-none");
                $(".userlist-panel").removeClass("d-block");
                updateChat(current_session,receiver);
                let carga=0;
                //establecer retardo de medio segundo para ejecutar esta funcion esperando a que cargue todo
                
            });
        });
        $("#default-list").click(function(){
            $("#default-list").addClass("user-active");
            $(".li_userlist").removeClass("user-active");
            $("#message-body-input").attr('value','');
            $("#sendingMessages").fadeOut(300);
            var chamingChat=`<img src="view/userpics/chaming.jpg" class="my-porfile-pic"><p><b>Chaming</b><div class='status-online'>Online</div></p>`;
            $("#messages").html('');
            $("#receiver_p").html(chamingChat);
        });
        setInterval(() => {
            $(".li_userlist.user-active").each(function(){
                var receiver=$(this).attr("data-id");
                var update=true;
                updateChat(current_session,receiver); 
            }); 
            
        }, 1000);
        setInterval(() => {
            $(".last-message").each(function(){
                let current_chat=$(this).attr("data-id");
                    $.ajax({
                        method:"POST",
                        url: "controller/chat action.php",
                        data: {current_chat:current_chat,current_session:current_session,action:"updateUnread"},
                        dataType: "json",
                        success: function (response) {
                            $("#chatIDunread" + current_chat).html(response.unreaded);
                        }
                    });
            });
        }, 30000);
        
        
        $("#sendingMessages").submit(function(){
            if ($("#message-body-input").val()!='') {
                var message=$("#message-body-input").val();
                var chat=$("#chatid").attr("data-id");
                sendChat(message,current_session,chat);
                return false;
            }
            else{
                alert("no puedes enviar un mensaje vacio!");
                $("#message-body-input").focus();
                return false;

            }
        });
        
     }, 500);
        
    function updateChat(current_session,receiver){
        $.ajax({
            method:"POST",
            url: "controller/chat action.php",
            data: {my_id:current_session,receiver_id:receiver,action:"show"},
            dataType: "json",
            success: function (response) { 
                $("#messages").html(response.conversation);
                $("#receiver_p").html(response.details);
                
                $(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
            }
        });
    }
    function sendChat(message,current_session,chat){
        $.ajax({
            method:"POST",
            url: "controller/chat action.php",
            data: {message:message,current_session:current_session,chatid:chat,action:'sendMessage'},
            dataType: "json",
            success: function (response) {
                if(response.response){
                    $("#message-body-input").val('');
                }else{
                    alert('message was not sent');
                }
            }
        });
    }
    function updateUserlist(current_session){
        $.ajax({
            method:"POST",
            url: "controller/chat action.php",
            data: {current_session:current_session,action:"getUsers"},
            dataType: "json",
            success: function (response) {
                $("#user-list").html(response.users);
            }
        }); 
    
    }
});
