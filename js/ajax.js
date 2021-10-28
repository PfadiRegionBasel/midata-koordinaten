$(document).ready(function(){
	$("#abteilung").change(function(){
		var allbooks = $(this).val();
		var dataString = "abteilungsId="+allbooks;
		$.ajax({
			type: "POST",
			url: "get-data.php",
			data: dataString,
			success: function(result){
				$("#show").html(result);
			}
		});
	});
});