//Alert se briše 2 sekunde nakon prikazivanja
$(".alert").delay(2000).slideUp(500);

//Blokiraj submit button kada se jednom forma pošalje
function buttonSubmit(btn){
	btn.disabled = true;
	btn.form.submit();
};

//Funkcija za klik na usera u users tablici
$('.users-table tr.clickable').click(function(){
	window.location = $(this).data('url');
});


//Prikazi-sakrij send-image-container
function toggleSend(){
	$("#send-image-container").slideToggle();
};


//Prikazivanje slike preko cijelog zaslona
$("#myImg").click(function(){
	$("#myModal").css('display', 'block');
	$("#img01").attr('src', this.src);
	$("#caption").text(this.alt);
});


//Zatvaranje slike sa cijelog zaslona
$("span.close").click(function(){
	$("#myModal").css('display', 'none');
});


$('.nav-home li').click(function () {
    $('.nav-home li').not(this).removeClass('active');
    $(this).addClass('active');
});


//Prikaz imena odabrane slike za učitavanje
$('#inputImage').change(function() {
   return $(".selected-file-name").text('Selected file: '+this.files[0].name);
});