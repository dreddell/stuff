function showlogin(){
	$("#logdiv").dialog({
			modal: true,
			buttons: {
	        	"Login": function() {
	          		proclogin();
	        	},
	        	Cancel: function() {
	          		$( this ).dialog( "close" );
	        	}
	      }
		}).dialog('open');
}


function logout(){
	$.post("actions.php", { action: 'logout' }, function(data){
		window.location.reload();
	});
}

function proclogin(){
	var loguser = $('#loguser').val();
	var logpass = $('#logpass').val();
	if(loguser.length > 1 && logpass.length > 1){
		$('#logmsg').html('Processing your credentials');
		$.post("actions.php", { user: loguser, pass: logpass, action: 'login' }, function(data){
			if(data=="success"){
		   		window.location.reload();
			}else{
				$('#logmsg').html(data);
			}
		});
	}else{
		$('#logmsg').html('Please use a username and password to proceed');
	}

}


$( document ).ready(function() {
  $("#logbut").click(function() {
  		showlogin();
  	}
  );
  
    $("#logout").click(function() {
  		logout();
  	}
  );
  
  
});

