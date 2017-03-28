<!-- START signup -->
<div>
  
  <h2>SignUp</h2>
  
  <form id ="form_signUp" action="regUser.php" method="post">
      
  <p><label>User Name</label></p> 
  <p><input type="text" name="user"></p>
  
  <p><label>Password</label>
  <p><input type="password" name="pass" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters!"></p>
  
  <p><label>Date Of Birth: </label></p>
  <p>
    <label>Day</label>
    <label>Month</label>
    <label>Year</label>
  </p>
  <p><input type="number" class="dob" id="day" name="dia" pattern="\d{1,2}">
     <input type="number" class="dob" id="month" name="mes" pattern="\d{1,2}">
     <input type="number" class="dob" id="year" name="anio" pattern="\d{4}">
  </p>
     
  <p><label>Sex: </label>
  <p>
    <input name="sexo" type="radio" value="1"/> <label>Male</label>
    <input name="sexo" type="radio" value="2"/> <label>Female</label>
  </p>
  
  <p><label>Web Site:</label></p>
  <p><label>Site </label><input type="text" name="site"></p>
  <p><label>Icon </label><input type="file" name="icon"/>
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