<?php
    class Chat{
        private $conex;

        public function __construct($conex){
            $this->setConex($conex);
        }
        public function setConex(PDO $conex){
            $this->conex=$conex; 
        }
        public function validateUser($username,$pass){
            $sql="SELECT * FROM `users` WHERE username='$username' AND password='$pass'";
            $result=$this->conex->query($sql);
            $cantidad=$result->rowCount();
            return $cantidad;
        }
        public function getCurrentID($username,$pass){
            $sql="SELECT usrID FROM `users` WHERE username='$username' AND password='$pass'";
            $result=$this->conex->query($sql);
            $i=0;
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
                $id=$aux['usrID'];
                $i++;
            }
            return $id;
        }
        public function updateStatusConnection($id, $status){
            switch($status){
                case "on":
                    $update="UPDATE `users` SET `online` = '1' WHERE `usrID` = $id";
                    $this->conex->query($update);
                break;
                case "off":
                    $update="UPDATE `users` SET `online` = '0' WHERE `usrID` = $id";
                    $this->conex->query($update);
                break;
            }
            $sql="SELECT online FROM users where usrID=$id";
            $result=$this->conex->query($sql);
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
				$newStatus=$aux['online'];
			}
            return $newStatus;
        }
        public function updateLastSeen($id){
            $sql="UPDATE users SET last_connect=now() where usrID=$id";
            $this->conex->query($sql);
        }
        public function getUserDetails($id){
            $sql="SELECT * FROM `users` WHERE usrID=$id";
            $result=$this->conex->query($sql);
            $i=0;
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
				$array[$i]["id"]=$aux['usrID'];
				$array[$i]["username"]=$aux['username'];
				$array[$i]["psw"]=$aux['password'];
				$array[$i]["avatar"]=$aux['avatar'];
				$array[$i]["status"]=$aux['online'];
				$array[$i]["last"]=$aux['last_connect'];
				$i++;
				
			}
            return $array;
        }
        public function getAvatar($id){
            $sql="SELECT avatar FROM `users` WHERE usrID=$id";
            $result=$this->conex->query($sql); 
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
				 $avatar=$aux['avatar'];
			}
            return $avatar;
        }
        public function getAllUsers($id){
            $sql="SELECT * FROM `users` WHERE usrID!=$id";
            $result=$this->conex->query($sql);
            $users="";
            $current_session=$id;
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
                $current_chat=$this->getChatID($id,$aux["usrID"]);
                // $lastMessage=$this->getLastMessage($current_chat);
                $users .=
                "<li class='li_userlist' data-id='" . $aux["usrID"] . "'> <div class='d-none' id='usrID" . $aux["usrID"] . "'>" . $aux['usrID'] . "</div>
                <img src='view/userpics/" . $aux['avatar'] . "' class='my-porfile-pic'>
                <p><b>" . $aux['username'];
                $users.= "</b><div class='last-message' data-id='" . $aux["usrID"] . "' id='chatIDunread" . $aux["usrID"] . "'>";
                // $users.=$this->getUnreadMessages($current_chat,$current_session);
                $users.="</div></p> 
                </li>
                "; 
            }
            $allUsers=array(
                "users"=>$users
            );
            return json_encode($allUsers);
        }
         
        public function getUnreadMessages($chatID,$current_session){
            $sql="SELECT mensaje FROM `actividad` WHERE chatID=$chatID AND readed=0 AND sender_id!=$current_session";
            $cantidad= $this->getRowCount($sql);
            $texto=$cantidad!=0 ? "<span class='badge badge-danger'>" .  $cantidad . "</span> Mensajes no leídos<br>" : "Aún no hay nada.";
            return $texto;
        }
        public function updateUnreadMessages($chatID,$current_session){
            $sql="UPDATE actividad SET readed=1 WHERE chatID=$chatID AND sender_id!=$current_session";
            $this->conex->query($sql);
        }
        public function getRowCount($sql){
            $result=$this->conex->query($sql);
            return $result->rowCount();
        }
        public function getChatID($sender,$receiver){
            $sql="SELECT chatID FROM `chat` WHERE (receiver_id=$sender OR sender_id=$sender) AND (receiver_id=$receiver OR sender_id=$receiver)";
            $find_chat=$this->getRowCount($sql);
            while($find_chat==0){
                if ($find_chat==0) {
                    $insert_before="INSERT INTO chat (sender_id,receiver_id) VALUES ($sender,$receiver)";
                    $this->conex->query($insert_before);
                    $find_chat=$this->getRowCount($sql);
                }
            }
            
            $result=$this->conex->query($sql);
            while($aux=$result->fetch(PDO::FETCH_ASSOC)){
                $chatID=$aux["chatID"];
            }
            return $chatID;
        }
        public function getChatReceiver($id){
            $receiver=$this->getUserDetails($id);
            $porfile_details="
            <img src='view/userpics/" . $receiver[0]["avatar"] . "' class='my-porfile-pic'><div class='receiver-porfile d-none' data-id='" . $receiver[0]["id"] . "'></div><p><b>" . $receiver[0]['username'] . "</b>";
            if ($receiver[0]["status"]==1) {
                $porfile_details.= "<div class='status-online'>Online</div></p>";
            }else{
                $porfile_details.= "<div class='status-last'>Last seen at " . $receiver[0]['last'] . "</div></p>";
            } 
            return $porfile_details;
        }
        public function getMessages($sender,$receiver){
            $chatID=$this->getChatID($sender,$receiver);
            $sql="SELECT * FROM `actividad` WHERE chatID=$chatID ORDER BY fecha";
            $result=$this->conex->query($sql);
            $conversation="<div class='d-none' id='chatid' data-id='" . $chatID . "'></div>";
            while($message=$result->fetch(PDO::FETCH_ASSOC)){
                if($message["sender_id"]==$sender){
                    $conversation.="
                    <li class='sender-message' id='" . $message['ID'] . "'><div class='d-none' ></div><p class='message-body-sender'>" . $message['mensaje'] . "</p>
                    <img src='view/userpics/" . $this->getAvatar($message["sender_id"]) . "' class='my-porfile-pic'></li>
                    ";
                } else {
                    $conversation.= "
                    <li class='receiver-message' id='" . $message['ID'] . "'><img src='view/userpics/" . $this->getAvatar($message["sender_id"]) . "' class='my-porfile-pic'>
                    <p class='message-body-receiver'>" . $message['mensaje'] . "</p></li>
                    ";
                } 
            }
            $details=$this->getChatReceiver($receiver);
            $conversation_array=array(
                "conversation"=>$conversation,
                "details"=>$details
            );
            return json_encode($conversation_array);
        }
        public function sendMessage($sender, $chatID,$text){
            $select="SELECT * FROM `actividad` WHERE chatID=$chatID ORDER BY fecha";
            $old_chat=$this->getRowCount($select);
            $sql="INSERT INTO actividad (chatID,sender_id,mensaje,fecha,readed) VALUES ($chatID,$sender,'$text',now(),0)";
            $this->conex->query($sql);
            $new_chat=$this->getRowCount($select);
            if ($new_chat==$old_chat) {
                $response=array("response"=>false);
                
            }else{
                $response=array("response"=>true);
            }
            return json_encode($response);
        }
        
    }
?>