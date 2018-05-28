

//Funkcija za klik na usera u users tablici
$('.users-table tr').click(function(){
	window.location = $(this).data('url');
});

