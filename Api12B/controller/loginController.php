<?php 
class LoginController{
    private $_method;
    private $_data;

    function __construct($method, $data){
        $this-> _method = $method;
        $this-> _data = $data;
    }
    public function index(){
        switch($this->_method){
            case 'POST':
                $credencials = UserModel::login($this->_data);
                $result = [];//arreglo
                if(!empty($credencials)){
                    $result["credenciales"] = $credencials;
                    $result["mensaje"] = "OK";
                }else{
                    $result["credenciales"] = null;
                    $result["mensaje"] = "ERROR EN CREDENCIALES";
                    $header = "HTTP/1.1 400 FAIL";
                }
            echo json_encode($result, JSON_UNESCAPED_UNICODE);
            return;
            default: 
            $json = array(
                "ruta: " => "not found"
            );
            echo json_encode($json, true);
            return;
        }
    }
}
?>