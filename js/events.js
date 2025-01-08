//javascript per aggiungere l'evento click ai div di ciascun evento storico:
//al click del div, deve avvenire il reindirizzamento alla pagina dei dettagli del singolo evento
document.addEventListener("DOMContentLoaded", function () {
    //chiamata del metodo getElementsByClassName per ottenere tutti i div degli eventi storici
    let cards = document.getElementsByClassName("card");
    //ciclo per scorrere ogni div
    for (let index = 0; index < cards.length; index++) {
        //aggiunta dell'evento click al div
        cards[index].addEventListener("click", function(){
            //chiamata del metodo getElementsByTagName per ottenere l'immagine all'interno del div dell'evento
            let srcImage = cards[index].getElementsByTagName("img")[0].src;
            //reindirizzamento alla pagina dei dettagli del singolo evento passando come parametro il path dell'immagine
            window.location.href = "event_description.php?src=" + srcImage;
        })   
    }
});