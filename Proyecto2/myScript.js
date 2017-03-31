function myFunction() {
   document.getElementById('demo').style.display = 'block';
   alert(document.getElementById('demo').innerHTML);
}
function showSites(){
   var sites = document.getElementById('mySites');
    if (sites.style.display === 'none') {
        sites.style.display = 'block';
    } else {
        sites.style.display = 'none';
    }
}

function insertText(){
  var p = document.createElement("p");
  var msg = document.createTextNode("Invalid user or password.Please SignIn!");
  p.setAttribute("id", "text");
  p.appendChild(msg);  
  document.getElementById("myDiv").appendChild(p);
}

function errorLogin(){
  var div = document.getElementById("myDiv");
  
  if(div.hasChildNodes()){
    div.removeChild(div.childNodes[0]);
    insertText();
  }else{
    insertText();
  }
}

function addResetButton(){
  var btn = document.createElement("button");        
  var txt = document.createTextNode("Reset");       	 
  btn.appendChild(txt);
  btn.setAttribute("id", "reset");
  btn.setAttribute("type", "button");
  btn.setAttribute("onclick", "clearForm()");
  document.getElementById("buttons").appendChild(btn);
}

function clearForm(){
  document.getElementById('user').value = "";
  document.getElementById('pass').value = "";
  
  var div = document.getElementById("myDiv");
  
  if(div.hasChildNodes()){
    div.removeChild(div.childNodes[0]);
  }
  
}

//Script from LogInForm
$(document).ready(function(){
  
    $("#formulario").submit(function(event){
      
      var request;
      event.preventDefault();
      
      if (request) {
	request.abort();
      }
      
      var $form = $(this);
      var $inputs = $form.find("input");
      var serializedData = $form.serialize();
      
      request = $.ajax({
	    url: "login.php",
	    type: "post",
	    data: serializedData
	});
      request.done(function (response, textStatus, jqXHR){
	addResetButton();
	if(response == 0){
	  errorLogin();
	}else if(response == 1){
	  window.location.href = 'profile.php';
	}
	
      });
      
  });   
});

//Script from Sites
$(document).ready(function(){
    $("#show").click(function(){
        $("#mySites").toggle();
    });
    
    $("#form_upload").submit(function(event){
      var request;
      //var form = $('form')[0];
      //var formData = new FormData(form);
      var form = document.getElementById('form_upload');
      formData = new FormData(form);
     
      event.preventDefault();
  

      if (request) {
	request.abort();
      }
    
      request=$.ajax({
	url: 'profile.php',
	data:  formData,
	type: 'POST',
	contentType: false,
	processData: false 
      });
      
      request.done(function (response, textStatus, jqXHR){
      document.getElementById("sitesTable").innerHTML=response;

      });
   }); 
});