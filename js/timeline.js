document.addEventListener("DOMContentLoaded", function () {
    const inputs = document.querySelectorAll(".input");
    const paras = document.querySelectorAll(".description-flex-container p");

    inputs.forEach((input, index) => {
        input.addEventListener("click", function () {
            // Aggiungi la classe 'active' all'elemento cliccato e al paragrafo corrispondente
            inputs.forEach(el => el.classList.remove("active"));
            paras.forEach(el => el.classList.remove("active"));

            let pathImg = input.getElementsByTagName("label")[0].innerHTML;
            let img = document.getElementById("image");

            img.src = pathImg;

            input.classList.add("active");
            if (paras[index]) {
                paras[index].classList.add("active");
            }
        });
    });
});