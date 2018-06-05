
//Funkcija za klik na usera u users tablici
$('.users-table tr').click(function(){
	window.location = $(this).data('url');
});

//Blokiraj submit button kada se jednom forma pošalje
function buttonSubmit(btn){
	btn.disabled = true;
	btn.form.submit();
}