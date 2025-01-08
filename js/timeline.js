//javascript per l'interattività della timeline della pagina home
document.addEventListener("DOMContentLoaded", function () {
    //chiamata del metodo querySelectorAll per ottenere tutti i div della linea del tempo
    const inputs = document.querySelectorAll(".input");
    const paras = document.querySelectorAll(".description-flex-container p");
    //chiamata del metodo getElementById per ottenere l'immagine
    let img = document.getElementById("image");

    //ciclo foreach per scorrere tutti i div della linea del tempo
    inputs.forEach((input, index) => {
        //aggiunta evento al click di ciascun div
        input.addEventListener("click", function () {
            //rimozione da tutti i div della classe active
            inputs.forEach(el => el.classList.remove("active"));
            paras.forEach(el => el.classList.remove("active"));

            let pathImg = input.getElementsByTagName("label")[0].innerHTML;

            img.src = pathImg;

            input.classList.add("active");
            if (paras[index]) {
                paras[index].classList.add("active");
            }
        });
    });

    img.addEventListener("click", function() {
        let srcImage = img.src;
        window.location.href = "event_description.php?src=" + srcImage;
    });
});