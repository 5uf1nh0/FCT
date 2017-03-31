<!-- START profile --> 
  <h2>Profile</h2>
  
  <form id ="form_sites">
    <p><label>Name: </label>{name}</p> 
    <p><label>Date Of Birth: </label>{date}</p>
    <p><label>Sex: </label>{sex}</p>
    <p><label>WebSite/s:</label>
    <button id="show" type="button">Show</button>
 <!-- START sitestempfather -->
    <table id= "sitesTable">
    <!-- START sitestemp -->
      <tr>
	<td>{sites}</td>
	<td><img src="{icons}" id="icon" height="42" width="42"/></td>
      </tr>
      <!-- END sitestemp -->
    </table>
<!-- END sitestempfather -->
      <a href="login.php" id="LogIn"><button type="button">Cerrar Sesion</button></a>
      </p>
    
  </form>
  
  <h2>New Site</h2>   
     <div id="newSites">
	<form id = "form_upload" action="profile.php" method="post" enctype = "multipart/form-data"> 
	<label>Site: </label>
	<input type="text" class="Textbox" name="site"/>
	<p>
	  <label>Image: </label>
	  <input type = "file" name = "file"/>
	  <div id="result"></div>
	</p>
	<input id="upload" type="submit" value="Upload" name="upload"/>
	</form>
    </div>
<!-- END profile -->