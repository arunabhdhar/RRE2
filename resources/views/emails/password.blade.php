<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Reset password</h2>

        <div>
            Click here to reset your password: 
            {{ url('password/reset/'.$token) }}<br/>

        </div>

    </body>
</html>