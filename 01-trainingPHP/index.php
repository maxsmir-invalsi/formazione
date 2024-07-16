<?php

$json = '[{"marca":"MulinoNero","nome":"Biscotti Acchiappasonni","prezzo":2.9,"quantita":33,"reparto":"Colazione"},{"marca":"AlceBianco","nome":"Biscotti Ammorbanti","prezzo":3.8,"quantita":37,"reparto":"Colazione"},{"marca":"MulinoNero","nome":"Biscotti Fuggisvegli","prezzo":3.1,"quantita":34,"reparto":"Colazione"},{"marca":"MulinoNero","nome":"Biscotti Lenticole","prezzo":2.5,"quantita":35,"reparto":"Colazione"},{"marca":"AlceBianco","nome":"Biscotti Svelticole","prezzo":3.6,"quantita":36,"reparto":"Colazione"},{"marca":"Sfindus","nome":"Filetti d\'identici","prezzo":4.5,"quantita":16,"reparto":"Surgelati"},{"marca":"Sfindus","nome":"Filetti di Orazio","prezzo":4.5,"quantita":15,"reparto":"Surgelati"},{"marca":"LatteriaItalia","nome":"Latte A Meta","prezzo":0.6,"quantita":39,"reparto":"Colazione"},{"marca":"LatteriaItalia","nome":"Latte A Un Quarto","prezzo":0.3,"quantita":30,"reparto":"Colazione"},{"marca":"LatteriaItalia","nome":"Latte Intero","prezzo":1.2,"quantita":38,"reparto":"Colazione"},{"marca":"Divelta","nome":"Pasta Impastati","prezzo":0.8,"quantita":29,"reparto":"Primi piatti"},{"marca":"Divelta","nome":"Pasta Liscioni","prezzo":0.7,"quantita":28,"reparto":"Primi piatti"},{"marca":"Divelta","nome":"Pasta Rigatoni","prezzo":0.7,"quantita":27,"reparto":"Primi piatti"},{"marca":"MoltoBueno","nome":"Pizza Pizzicante","prezzo":7.1,"quantita":13,"reparto":"Surgelati"},{"marca":"MoltoBueno","nome":"Pizza Prelibata","prezzo":4.5,"quantita":14,"reparto":"Surgelati"},{"marca":"MoltoBueno","nome":"Pizza Scacciapensieri","prezzo":5.2,"quantita":12,"reparto":"Surgelati"},{"marca":"MoltoBueno","nome":"Pizza Violetta","prezzo":4.5,"quantita":11,"reparto":"Surgelati"},{"marca":"Sfindus","nome":"Sofficelli Pomodargento","prezzo":3,"quantita":17,"reparto":"Surgelati"},{"marca":"Sfindus","nome":"Sofficelli Pomodoro","prezzo":3.2,"quantita":15,"reparto":"Surgelati"},{"marca":"Multi","nome":"Sugo di patate marce","prezzo":3.5,"quantita":21,"reparto":"Primi piatti"},{"marca":"Multi","nome":"Sugo di pomodori scaduti","prezzo":3.4,"quantita":20,"reparto":"Primi piatti"},{"marca":"Multi","nome":"Sugo di sale","prezzo":3.5,"quantita":22,"reparto":"Primi piatti"}]';
$data = json_decode($json, true);

echo '<h4>Esercizio</h4>
<p>
    Il compito è quello di partire da un insieme di dati in un array <i>$data</i> (come visualizzato sotto) e organizzarli in una struttura dati più complessa in modo da:
    <ol>
        <li>Raggruppare i dati in una maniera sensata</li>
        <li>Rendere l\'accesso ai dati semplice e intuitivo sfruttando delle chiavi associative e non numeriche</li>
    </ol>
</p>
<p>
    Successivamente utilizzare la nuova struttura dati per visualizzare le informazioni graficamente.
</p>';
echo '<pre>';
var_dump($data);
echo '</pre>';
