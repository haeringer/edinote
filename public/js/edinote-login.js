/**
 * Edinote login page functions
 *
 * Ben Haeringer
 * ben.haeringer@gmail.com
 *
 */

$(function() {
	$("#submit-login").click(function() {

		console.log('trying to log user in....');

		var username = $("#inputUsername").val();
		var password = $("#inputPassword").val();

		$.ajax({
			type: "POST",
			url: "login.php",
			data: { username: username, password: password },
			beforeSend: function() {
				$("#submit-login").html('Connecting...').addClass('disabled');
			}
		})

		.done(function(response) {
			console.log('login.php returned ' + JSON.stringify(response));

			if (response.rval === 0) {
				// redirect to main
				document.location.href = "/";
			} else if (response.rval === 1) {
				$(".alert").css('display', 'none');
				$(".invalid-login").show();
				submitBtnRevert();
			} else if (response.rval === 2) {
				$(".alert").css('display', 'none');
				$(".empty-username").show();
				submitBtnRevert();
			} else if (response.rval === 3) {
				$(".alert").css('display', 'none');
				$(".empty-password").show();
				submitBtnRevert();
			} else {
				console.log('oops?!');
			}
		})

		.fail(function(jqXHR, textStatus, errorThrown) {
			console.log(errorThrown.toString());
		});
	});

	$('#inputPassword').on('keypress', function(e) {
		submit(e);
	});
	$('#inputUsername').on('keypress', function(e) {
		submit(e);
	});
});

function submit(e) {
	if (e.keyCode === 13) {
		e.preventDefault();
		$('#submit-login').trigger('click');
	}
}

function submitBtnRevert() {
	$("#submit-login").delay(500)
	.queue(function(n) {
		$(this).html("Log in").removeClass('disabled');
		n();
	});
}
