document.addEventListener("DOMContentLoaded", function () {
    let cards = document.getElementsByClassName("card");
    for (let index = 0; index < cards.length; index++) {
        cards[index].addEventListener("click", function(){
            let srcImage = cards[index].getElementsByTagName("img")[0].src;
            window.location.href = "event_description.php?src=" + srcImage;
        })   
    }
});