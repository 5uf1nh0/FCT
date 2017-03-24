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

$(document).ready(function(){
    $("#show").click(function(){
        $("#mySites").toggle();
    });
    
    $("#form_sites").submit(function(event){
      var request;
      event.preventDefault();
      
      if (request) {
	request.abort();
      }
      
      var $form = $(this);
      var $inputs = $form.find("input");
      var serializedData = $form.serialize();
      
      request = $.ajax({
	    url: "/update.php",
	    type: "post",
	    data: serializedData
	});
      request.done(function (response, textStatus, jqXHR){
	      // show successfully for submit message
	document.getElementById("mySites").innerHTML = response;
	      $("#result").html('Submitted successfully!');
      });                   
      
  });
    
});


$(document).ready(function(){
  var btn = document.createElement("button");        
  var txt = document.createTextNode("Reset");       	 
  btn.appendChild(txt);                                
  document.getElementById("myDiv").appendChild(btn);
  
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
	    url: "/profile.php",
	    type: "post",
	    data: serializedData
	});
      request.done(function (response, textStatus, jqXHR){
	document.getElementById("mySites").innerHTML = response;
	      alert('LogIn successfully!');
      });                   
      request.fail(function() {
	  var p = document.createElement("p");                       
	  var msg = document.createTextNode("Invalid user or password.Please SignIn!");      
	  para.appendChild(msg);                                          
	  document.getElementById("myDiv").appendChild(p); 
	})
  });   
});