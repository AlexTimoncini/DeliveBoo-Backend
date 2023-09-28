<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #01975c;
            color: white;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        .content {
            background-color: #f4b807;
            padding: 20px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            font-size: 18px;
        }
        .message {
            margin-top: 20px;
            font-size: 24px;
        }
        a.button {
            background-color: #01975c;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .button:hover {
            color: black;
            background-color: #f4b807;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DeliveBoo</h1>
    </div>
    <div class="content">
        <p>Hi I'm {{ $lead->name }},</p>
        <p>I'm writing from DeliveBoo App with this mail: {{ $lead->email }} in order to say this:</p>
        
        <div class="message">
            <p> ~{{ $lead->message }}~</p>
        </div>
        <p><a href="http://localhost:3000/" class="button">Are you hungry?</a></p>
        
        <p>
        Boo Gonna Conquer the world!<br/>
        Boo greetings,<br/>
        Deliveboo Team
        </p>
    </div>
</body>
</html>
