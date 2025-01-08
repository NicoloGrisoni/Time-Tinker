//javascript per l'interattività della timeline della pagina home
document.addEventListener("DOMContentLoaded", function () {
    //chiamata del metodo querySelectorAll per ottenere tutti i div della linea del tempo
    const inputs = document.querySelectorAll(".input");
    const paras = document.querySelectorAll(".description-flex-container p");
    let img = document.getElementById("image");

    inputs.forEach((input, index) => {
        input.addEventListener("click", function () {
            // Aggiungi la classe 'active' all'elemento cliccato e al paragrafo corrispondente
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