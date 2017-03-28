<!-- START profile --> 
<div>
  
  <h2>Profile</h2>
  
  <form id ="form_sites">
    <p><label>Name: </label>{name}</p> 
    <p><label>Date Of Birth: </label>{date}</p>
    <p><label>Sex: </label>{sex}</p>
    <p><label>WebSite/s:</label>
    <button id="show" type="button">Show</button>
    
    <div id="mySites" style="display:none">
	<!-- START sites -->
	<div id="pageList">
	    
	    <table>
	      <tr>
		<td>{sites}</td>
		<td><img src="{icons}" id="icon" height="42" width="42"/></td>
	      </tr>
	    </table>
	    
	    
	</div>
	<!-- END sites -->
    </div>
      <a href="login.php" id="LogIn"><button type="button">Cerrar Sesion</button></a>
      </p>
    </div>
  </form>
  
  <h2>New Site</h2>   
     <div id="newSites">
	<form id = "form_upload" action = "upload_pic.php" method = "post" enctype = "multipart/form-data"> 
	<label>Site: </label>
	<input type="text" class="Textbox" name="site"/>
	<p>
	  <label>Image: </label>
	  <input id="fileToUpload" type="file" name="fileToUpload" />
	  <input id="upload" type="submit" value="Upload" name="upload"/>
	</p>
	
	</form>	
      <div id="result"></div>
      <p>
      <input id="NewSite" type="submit" value="Add Site" name="addSite"/>
    </div>
<!-- END profile -->