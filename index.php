<?php 
    include('view/header.php');
    include_once('model/classes.php');
    include_once('controller/comprobar.php') ;
?>
<body>
    <div class="container">	 
        <div class="row ">
            <div class="col-md-12 col-sm-12 d-block d-sm-block d-md-block botonera">
                
                <a href="controller/controller user.php?logout=true" class="btn-logout">Cerrar sesión</a> 
            </div>
        </div>		
        <div class="row chat-panel"> 
           <div class="col-md-4 col-sm-4 d-sm-block d-md-block d-block col-12 userlist-panel">
                <div class="my-porfile" id="current_session" data-id="<?php echo $_SESSION['id'];?>">
                    <img src="view/userpics/<?php echo $_SESSION["avatar"];?>" class="my-porfile-pic">
                    <p><?php echo $_SESSION["user"];?><br>
                        <?php
                            if ($_SESSION["status"]==1) {
                                # code...
                                echo "<div class='status-online'>Online</div>";
                            }else{
                                echo "<div class='status-offline'>offline</div>";
                            }
                        ?>
                    </p>
                </div>
                <div class="user-chats">
                    <ul class="contact contact-default">
                        <li id="default-list" class='d-none d-sm-block d-md-block user-active'>
                            <div class='d-none usrID' id='0'>0</div>
                            <img src='view/userpics/chaming.jpg' class='my-porfile-pic'>
                            <p><b>Chaming</b><div class='status-online'>Online</div></p>
                        </li>
                    </ul>
                    <ul class="contact" id="user-list">
                        
                    </ul>
                </div>

                <div class="find-user">
                    <input type="text" class="form-control find-user-input" placeholder="Buscar usuario...">
                    <button type="submit" class="btn btn-primary find-user-btn">Go</button>
                </div>
           </div>
           <div class="col-md-8 col-sm-8 col-12 d-none d-sm-block d-md-block chat-content">
           <button class='d-block d-sm-none d-md-none btn btn-primary' id='goback'>&laquo;</button>
               <div class="receiver-porfile" id="receiver_p">
                   <img src="view/userpics/chaming.jpg" class="my-porfile-pic"><p><b>Chaming</b><div class='status-online'>Online</div></p>
               </div>
               <div class="messages">
                    <ul class="message-list" id="messages"></ul>
               </div>
               
               <div class="input-messages">
                    <form method="post" name='sendingMessages' id="sendingMessages">
                        <textarea name="message-body" id="message-body-input" class="form-control write-messages" placeholder="Escribe tu mensaje..."></textarea>
                        <button type="submit" name='send' id='send-msg' class="btn btn-info">&raquo;</button> 
                    </form>  
               </div>
               
           </div>
        </div>  
    </div>

<?php include("view/footer.php");?>