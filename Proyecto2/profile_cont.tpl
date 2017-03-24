<!-- START profile --> 
<div>
  
  <h2>Perfil</h2>
  <form id ="form_sites"> <!--//action="update.php" method="post" onsubmit="return false" --> 
    <p>Name: {name}</p> 
    <p>DateOfBirth: {date}</p>
    <p>Sex: {sex}</p>
    <p>WebSite/s:
    <button id="show" type="button">Show</button>
    <p><div id="mySites" style="display:none">
	<ul>
	<!-- START sites -->
	<div id="pageList">
	    <li><u>{sites}</u></li>
	</div>
	<!-- END sites -->
	</ul>
    </div></p>
      <p>
      <label>NewSite: </label>
      <input type="text" name="site"/>
      <input id="NewSite" type="submit" value="+"/>
      <div id="result"></div>
      </p>
    </p>
    
    
    <p><a href="login.php" id="LogIn"><button type="button">Cerrar Sesion</button></a></p>
  </form>

</div>
<!-- END profile -->