<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <header>
        <a href="#" class="logo"><img src="imagesHome/logo.png" id="imgLogo"></a>
        <ul>
            <li><a href="#" class="active">Home</a></li>
            <li><a href="login/login.php">Login</a></li>
            <li><a href="login/register.php">Register</a></li>
        </ul>
    </header>
    <section>
        <img src="imagesHome/nuvole-completo.png" id="nuvole">
        <img src="imagesHome/center-mountain.png" id="montagna_centro">
        <h2 id="text">TIME TINKER</h2>
        <a href="#sec" id="btn">Explore</a>
        <img src="imagesHome/side-mountain.png" id="montagna_lato">
    </section>
    <div class="sec" id="sec">
        <h2>TIME TINKER</h2>
        <p>
            Time Tinker è un progetto dedicato agli eventi storici, dai più remoti fino alle novità più recenti,
            e offre la possibilità di esplorarli, visualizzarli e persino immaginare nuovi scenari. 
            Le sue funzionalità principali sono le seguenti: <br><br>

            Visualizzazione degli eventi storici, nella quale ogni evento è rappresentato con: <br>
                1- Nome, come "Seconda Guerra Mondiale" o "Trattato di Versailles"<br>
                2- Descrizione sintetica <br>
                3- Anno o intervallo di anni in cui si è svolto<br>
                4- Immagine rappresentativa <br><br>
            
            Viene inserito inoltre a ciascun evento storico un indicatore di importanza:<br>
                1- Importante, per gli eventi più significativi<br>
                2- Non importante, per quelli meno rilevanti<br>
            Gli eventi più importanti sono visualizzati sulla timeline principale del sito, permettendo agli utenti di
            navigare attraverso i momenti chiave della storia<br><br>

            Ricerca per anno: <br>
            gli utenti possono cercare eventi storici selezionando un anno specifico e ottenere come
            risultato i principali eventi accaduti durante esso.<br><br>

            Modifica del corso della storia: <br>
            una funzionalità innovativa che consente di alterare ipoteticamente gli eventi storici. <br>
            Ad esempio, selezionando "Seconda Guerra Mondiale" e inserendo un prompt come "E se la
            Germania avesse vinto?", il sistema genera uno scenario alternativo, mostrando le possibili conseguenze di
            questa modifica, anche sul presente.<br><br>

            Obiettivo del progetto:<br>
            Time Tinker mira non solo a informare ma anche a coinvolgere gli utenti in un viaggio interattivo nel tempo,
            stimolando la curiosità, la creatività e il pensiero critico. È un modo unico per esplorare la storia,
            immaginare nuovi scenari e approfondire la conoscenza degli eventi più significativi del passato.<br><br>

            Scopri il potere del tempo e riscrivi la storia con Time Tinker! </p>
    </div>

    <script>
        let nuvole = document.getElementById("nuvole");
        let montagna_centro = document.getElementById("montagna_centro");
        let montagna_lato = document.getElementById("montagna_lato");
        let text = document.getElementById("text");
        let btn = document.getElementById("btn");
        let header = document.querySelector("header");

        window.addEventListener("scroll", function () {
            let value = window.scrollY;
            nuvole.style.left = value * 0.25 + "px";
            montagna_centro.style.top = value * 0.5 + "px";
            montagna_lato.style.top = value * 0 + "px";
            text.style.marginRight = value * 3 + "px";
            text.style.marginTop = value * 1.5 + "px";
            btn.style.marginTop = value * 1.5 + "px";
            header.style.top = value * 0.5 + "px";
        })
    </script>
</body>

</html>