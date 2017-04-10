<div class="logout">
  <a id="logout" href="login.php">
    <img border="0" src="logout.png" width="50" height="50">
  </a>
  <p><label>Log Out</label></p>
</div>

<!-- START profile --> 
  <div><h1>Profile</h1></div>
  
  <form id ="form_sites" action="delete.php" method="post" >
    <p><label>Name: </label>{name}</p> 
    <p><label>Date Of Birth: </label>{date}</p>
    <p><label>Sex: </label>{sex}</p>
    <p><label>WebSite/s:</label>
      <button id="show" type="button">Show</button>
      <input id="delete" type="submit" value="Delete" name="del"/>
    </p>   
  </form>
    
<div id="mySites" display="none">
 <!-- START sitestempfather -->
    <div id= "row">
    <!-- START sitestemp -->
      <div class="cell">
	<img src="{icons}" id="icon"/>
	<p id="site_check">
	<label id="row_id" hidden>{rowID}</label>
	<label>{sites}</label>
	<input id="chbox" class="Check" type="checkbox" name="cbox[]" value="{rowID}"/>
	</p>
      </div>
    <!-- END sitestemp -->
    </div>
 <!-- END sitestempfather -->  
  </div>
<!-- END profile --> 
<div id="end"></div>
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
   
   <a href="#" class="back-to-top">
   <img border="0" src="up.png" width="50" height="50">
   <p><label>Back To Top</label><p>
   </a>
