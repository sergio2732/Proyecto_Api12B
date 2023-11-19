# Proyecto_Api12B
Cambios realizados:
En el index se cambio la variable if($endPoint != 'login') por un == 'login', para que pueda realizar la autentificación correctamente sin interrumpor los otros procesos en los diferentes endpoint como 'users'.
En el userController se comentaron las variables //echo($this->_method); //echo( $this->_complement."    "); //var_dump($this->_data); para que la salida del thunder client no tenga variables innecesarias y se pueda mostrar las variables deseadas en un formato json.


