<!-- resources/views/transcribe.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transcription Audio avec Microphone</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Enregistrement et transcription audio</h1>
    <button id="recordButton">Démarrer l'enregistrement</button>
    <button id="stopButton" disabled>Arrêter et Transcrire</button>
    <h2>Transcription :</h2>
    <p id="result">Aucune transcription pour le moment.</p>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        let mediaRecorder;
        let audioChunks = [];

        // Fonction pour démarrer l'enregistrement
        document.getElementById("recordButton").onclick = async function() {
            audioChunks = [];
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);

            mediaRecorder.ondataavailable = event => audioChunks.push(event.data);

            mediaRecorder.start();
            document.getElementById("recordButton").disabled = true;
            document.getElementById("stopButton").disabled = false;
        };

        // Fonction pour arrêter l'enregistrement et envoyer l'audio au serveur
        document.getElementById("stopButton").onclick = function() {
            mediaRecorder.stop();
            mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const formData = new FormData();
                formData.append('audio', audioBlob, 'recording.wav');

                $.ajax({
                    url: "{{ secure_url(route('transcribe.audio')) }}", 
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#result').text(response.transcription);
                    },
                    error: function() {
                        $('#result').text("Erreur lors de la transcription.");
                    }
                });


                document.getElementById("recordButton").disabled = false;
                document.getElementById("stopButton").disabled = true;
            };
        };
    </script>
</body>
</html>
