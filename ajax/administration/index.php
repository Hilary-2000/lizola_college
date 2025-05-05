<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Email</title>
</head>
<body>
    <form action="send.php" method="post">
        Email <input type="email" name="email" value="hilaryme45@gmail.com" id="email"><br>
        Subject <input type="text" name="subject" value="Verification Code" id="subject"><br>
        Message <input type="text" name="message" value="Verification Code is 1920. Expires in 5 minutes" id="message"><br>
        <button type="submit" name="send">Send Email</button>

    </form>
</body>
</html>