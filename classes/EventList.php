<?php 
    //inclusione delle classi necessarie
    require_once "event.php";
    require_once "FileManager.php";

    class EventList {
        private const FILE = "events.csv";

        // Metodo per ottenere tutti gli eventi importanti
        public static function GetImportants() {
            $allEvents = self::GetEvents();
            $importants = array();
            foreach ($allEvents as $event) {
                if ($event->isImportant() == "importante") {
                    $importants[] = $event;
                }
            }

            return $importants;
        }

        // Metodo privato per ottenere tutti gli eventi dal file
        private static function GetEvents() {
            $events = array();
            $rows = FileManager::GetRowFromFile(self::FILE);
            if (is_array($rows)) {
                foreach ($rows as $row) {
                    $fields = FileManager::GetFieldsFromRow(";", $row);
                    if (is_array($fields) && count($fields) == 5) {
                        $event = new Event($fields[0], $fields[1], $fields[2], $fields[3], $fields[4]);
                        $events[] = $event;
                    }
                }
            }

            return $events;
        }

        //metodo per ottenere tutti gli eventi dato un determinato anno
        public static function GetEventsByYear($year) {
            $events = self::GetEvents();
            $eventsByYear = array();
            foreach ($events as $event) {
                if ($event->getDate() == $year) {
                    $eventsByYear[] = $event;
                }
            }

            return $eventsByYear;
        }

        //metodo per prendere l'evento dal path dell'immagine
        public static function GetEventByImagePath($path) {
            $events = self::GetEvents();
            foreach ($events as $event) {
                if ($event->getImage() == $path) {
                    return $event;
                }
            }
            return null;
        }

        //metodo per ordinare gli eventi per anno
        public static function OrderEventsByYear($array) {
            $date = array();
            foreach ($array as $e) {
                $data = $e->getDate();
                if(str_contains($e->getDate(), ":"))
                    $data = explode(":", $e->getDate());

                if(is_array($data)) {
                    $data = $data[0];
                }

                $date[] = $data;
            }

            $n = count($date);
            // Loop esterno per passare più volte nell'array
            for ($i = 0; $i < $n - 1; $i++) {
                // Loop interno per confrontare gli elementi adiacenti
                for ($j = 0; $j < $n - 1 - $i; $j++) {
                    // Se l'elemento corrente è maggiore di quello successivo, scambiali
                    if ($date[$j] > $date[$j + 1]) {
                        // Scambio
                        $temp = $array[$j];
                        $array[$j] = $array[$j + 1];
                        $array[$j + 1] = $temp;

                        $temp = $date[$j];
                        $date[$j] = $date[$j + 1];
                        $date[$j + 1] = $temp;
                    }
                }
            }
            
            return $array;
        }

        //metodo per aggiungere un evento
            //controlla se non esistre l'evento e lo inserisce
            //altrimenti non lo aggiunge
        //metodo chiamata alle API

        //metodo API per cercare eventi
        public static function generateHistoricalEvent($year) {
            // La chiave API di OpenAI
            $apiKey = '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'; // Sostituisci con la tua chiave API
        
            // Impostazioni per la richiesta API di OpenAI
            // Prompt rivisitato per cercare di generare eventi storici significativi
            $prompt = "Genera un evento storico significativo accaduto nell'anno $year, includendo una breve descrizione dettagliata.";
        
            // Configura la richiesta per OpenAI
            $data = [
                'model' => 'gpt-3.5-turbo', // Usa il modello GPT-4
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a helpful assistant.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 200,  // Limita la lunghezza della risposta
                'temperature' => 0.7, // Aumentiamo la temperatura per risposte più creative
            ];
        
            // Inizializza cURL
            $ch = curl_init();
        
            // Imposta le opzioni di cURL
            curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json",
                "Authorization: Bearer $apiKey"
            ]);
        
            // Esegui la richiesta e ottieni la risposta
            $response = curl_exec($ch);
        
            // Controlla se c'è stato un errore durante l'esecuzione di cURL
            if (curl_errno($ch)) {
                echo 'Error cURL: ' . curl_error($ch);
                curl_close($ch);
                return;
            }
        
            // Chiudi la connessione cURL
            curl_close($ch);
        
            // Decodifica la risposta JSON
            $responseData = json_decode($response, true);
        
            // Verifica se la risposta contiene un risultato
            if (isset($responseData['choices'][0]['message']['content'])) {
                $eventDescription = $responseData['choices'][0]['message']['content'];
        
                // Restituisce l'evento e la sua descrizione
                return [
                    'event_title' => "Evento storico nel $year",
                    'event_description' => $eventDescription
                ];
            } else {
                // Se non è possibile ottenere un evento, diagnosticare meglio l'errore
                if (isset($responseData['error'])) {
                    return [
                        'error' => 'Errore API OpenAI: ' . $responseData['error']['message']
                    ];
                } else {
                    return [
                        'error' => 'Impossibile ottenere un evento storico per l\'anno ' . $year . '. La risposta dell\'API sembra vuota o non valida.'
                    ];
                }
            }
        }
    }
?>