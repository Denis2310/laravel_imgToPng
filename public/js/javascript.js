
//Funkcija za klik na usera u users tablici
$('.users-table tr.clickable').click(function(){
	window.location = $(this).data('url');
});

//Blokiraj submit button kada se jednom forma pošalje
function buttonSubmit(btn){
	btn.disabled = true;
	btn.form.submit();
}

//Brisanje alert diva
function remove(param){
	$(param).css('display', 'none');
}

//Prikazivanje slike preko cijelog zaslona
$("#myImg").click(function(){
	$("#myModal").css('display', 'block');
	$("#img01").attr('src', this.src);
	$("#caption").text(this.alt);
});

$("span.close").click(function(){
	$("#myModal").css('display', 'none');
});
