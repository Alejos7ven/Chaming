<?php 
    session_start();
     if(!empty($_SESSION["user"])){
        header('location:index.php');
    }
    include('view/header.php');
?>
<body>
    <div class="container">		
        <h2>Sistema de chat en vivo con Ajax, PHP y MySQL</h1>		
        <div class="row">
            <div class="col-sm-4 col-md-4 col-10 offset-1 form-login">
                <h4>Chat Login:</h4>		
                <form method="post" action="controller/controller user.php">
                    <div class="form-group">
                        <label for="username">Usuario:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Contraseña:</label>
                        <input type="password" class="form-control" id="psw" name="psw" required>
                    </div>  
                    <div class="form-group btn-login">
                        <button type="submit" name="login" id="login" class="btn btn-info">Iniciar Sesion</button>
                    </div>
                    
                </form>
            </div>
            
        </div>
    </div>

<?php include("view/footer.php");?>