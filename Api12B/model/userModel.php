<?php 
require_once ("ConDB.php");
class UserModel{
    static public function createUser($data){        
        $cantMail = self::getMail($data['user_mail']);
        if($cantMail == 0){
            $query = "INSERT INTO users(user_id, user_mail, user_pss, user_dateCreate, 
            user_identifier, user_key,  user_status) VALUES (NULL, :user_mail, :user_pss, :user_dateCreate, 
            :user_identifier, :us_key, :user_status)";
            $status = "0"; //0 inactivo 1->com_get_active_object
            $stament = Connection::connecction()-> prepare($query);
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
            $stament->bindParam(":user_dateCreate", $data['user_dateCreate'], PDO::PARAM_STR);
            $stament->bindParam(":user_identifier", $data['user_identifier'], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data['us_key'], PDO::PARAM_STR);
            $stament->bindParam(":user_status", $status, PDO::PARAM_STR);
            $message = $stament->execute() ? "OK" : Connection::connecction()->errorInfo();//hasta ahora va en el INSERT INTO
            $stament -> closeCursor();
            $stament = null;
            $query = "";
        }else{
            $message = "Usuario ya esta registrado";
        }
        return $message;
    }
    static private function getMail($mail){
        $query = "";
        $query="SELECT user_mail FROM users WHERE user_mail = '$mail';";
        $stament = Connection::connecction()->prepare($query);
        $stament->execute();
        $result = $stament -> rowCount();
        return $result;
    }
    //Trae todos los usuarios
    static function getUsers($id){
        $query = "";
        $id = is_numeric($id) ? $id : 0;
        $query = "SELECT user_id, user_mail, user_dateCreate FROM users";
        $query.= ($id > 0) ? " WHERE users.user_id  = '$id' AND " : "";
        $query.= ($id > 0) ? " user_status = '1';" : " WHERE user_status = '1';";
        $stament = Connection::connecction()->prepare($query);
        $stament->execute();
        $result = $stament -> fetchAll(PDO::FETCH_ASSOC);//Mandar los registros asociados
        return $result;
    }
    //Login
    static public function login($data){
        $query = "";
        $user = $data['user_mail'];
        $pss = $data['user_pss'];
        if(!empty($user) && !empty($pss)){
            $query = "SELECT user_id, user_identifier, user_key FROM users WHERE user_mail = '$user' and
            user_pss = '$pss' and user_status = '1';";
            $stament = Connection::connecction()->prepare($query);
            $stament->execute();
            $result = $stament -> fetchAll(PDO::FETCH_ASSOC);//Mandar los registros asociados
            return $result;
        }else{
            $mensaje = array(
                "COD" => "001",
                "MENSAJE" => ("ERROR EN CREDENCIASLES")
            );
            return $mensaje;
        }
    }
    static public function getUseAuth(){
        $query = "";
        $query = "SELECT user_identifier, user_key FROM users WHERE user_status = '1';";
        $stament = Connection::connecction()->prepare($query);
        $stament->execute();
        $result = $stament -> fetchAll(PDO::FETCH_ASSOC);   
        return $result;
    }
    static public function updateUsers($data){
        $cantMail = self::getMail($data['user_mail']);
        if ($cantMail != 0) {
            $query = "UPDATE users SET user_mail = :user_mail, user_pss = :user_pss WHERE user_id = :user_id AND user_status = '1';";
            $stament = Connection::connecction()->prepare($query);
            $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR); 
            $message = $stament->execute() ? "Usuario actualizado" : Connection::connecction()->errorInfo();  
            $stament->closeCursor();
            $stament = null;
            $query = "";
        } else {
            $message = "No se ha registrado el usuario";
        }
        return $message;
    }       
    static public function deleteUsers($data){
        $cantMail = self::getMail($data['user_mail']);
        if($cantMail == 1){
            $query = "DELETE FROM users WHERE user_mail = :user_mail OR user_id = :user_id";
            $stament = Connection::connecction()->prepare($query);
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
            $message = $stament->execute() ? "Usuario eliminado" : Connection::connecction()->errorInfo();
            $stament->closeCursor();
            $stament = null;
            $query = "";
        }else{
            $message = "La cuenta no existe";
        }
        return $message;
    }
    static public function changeStatus($data){
        $query = "UPDATE users SET user_status = :user_status WHERE user_id = :user_id";
        $stament = Connection::connecction()->prepare($query);
        $status = "1";
        $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stament->bindParam(":user_status", $status, PDO::PARAM_STR); // Cambiado a PDO::PARAM_STR
        $message = $stament->execute() ? "Status actualizado" : Connection::connecction()->errorInfo();  
        $stament->closeCursor();
        $stament = null; 
        return $message;
    }       
}
?>