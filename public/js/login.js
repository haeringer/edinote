$(function() {

		$(".invalid-login").css('display', 'none', 'important');

		$("#submit-login").click(function(){

				console.log('trying to log user in....');

			  var username = $("#inputUsername").val();
			  var password = $("#inputPassword").val();

		  	$.ajax({
						type: "POST",
						url: "login.php",
						data: { username: username, password: password }
		   	})

				.done (function(response) {
		        console.log('login.php returned ' + response);

		        if (response === '1') {
		        		// redirect to main
								document.location.href = "/";
						}
		        else if (response === '0') {
		            $(".invalid-login").show();
								console.log('invalid credentials');
		        }
		        else {
		            // clear changed state of file
								console.log('what happened?');
		        }
		    })

		    .fail(function(jqXHR, textStatus, errorThrown) {
		        console.log(errorThrown.toString());
		    });
		});

		$('#inputPassword').on('keypress', function(e) {
        if(e.keyCode === 13) {
            e.preventDefault();
            $('#submit-login').trigger('click');
        }
		});
		$('#inputUsername').on('keypress', function(e) {
				if(e.keyCode === 13) {
						e.preventDefault();
						$('#submit-login').trigger('click');
				}
		});

});
