<!-- START signup -->
<div>
  
  SignUp:
  
  <form id ="form_signUp" action="regUser.php" method="post">
      
  <p><label>User Name</label></p> 
  <p><input type="text" name="user"></p>
  
  <p><label>Password</label>
  <p><input type="password" name="pass"></p>
  
  <p><label>Date Of Birth</label></p> 
  <p><input type="date" name="fecha"></p>
  
  <p><label>Sex</label>
  <p>
    <p><input name="sexo" type="radio" value="0"/> Masculino</p>
    <p><input name="sexo" type="radio" value="1"/> Femenino</p>
  </p>
  
  <p><label>Web Sites</label></p>
  <p><input type="text" name="site"></p>
  
  <p><input id="boton" type="submit" value="Aceptar" />
     <a href="login.php" id="Cancel"><button type="button">Cancelar</button></a>
  </p>

  </form>

</div>
  <!-- END signup -->
  
  
  <!-- START show -->
  <p>{varn1}</p>
  <p>pass</p>
  <p>fecha</p>
  <p>sexo</p>
  <ul>
  <!-- START bla -->
  <li>{random}</li>
  <!-- END bla -->
  </ul>
  <!-- END show -->