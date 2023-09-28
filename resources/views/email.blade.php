<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DeliveBoo Contacts</title>
    <style>
        /* Stili per l'intestazione */
        .mini-view-header {
            background-color: #01975c; /* Verde primario */
            color: white; /* Testo bianco */
            padding: 10px;
        }

        /* Stili per il contenuto */
        .mini-view-content {
            background-color: #f4b807; /* Giallo secondario */
            padding: 10px;
        }

        /* Stili per il testo del nome e del messaggio */
        .mini-view-name {
            font-size: 18px;
            font-weight: bold;
        }

        .mini-view-message {
            font-size: 16px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Contenitore principale -->
    <div class="mini-view-container">
        <!-- Logo sulla destra -->
        
        <!-- Intestazione della mini vista -->
        <div class="mini-view-header">
            DELIVEBOO MESSAGE
        </div>
    </div>

    <!-- Contenuto -->
    <div class="mini-view-content">
        <!-- Nome del mittente -->
        <div class="mini-view-name">
            User: {{ $lead->name }}
        </div>
        <div class="mini-view-name">
            Email: {{ $lead->email }}
        </div>

        <!-- Messaggio -->
        <div class="mini-view-message">
            Message: {{ $lead->message }}
        </div>
    </div>
</body>
</html>
