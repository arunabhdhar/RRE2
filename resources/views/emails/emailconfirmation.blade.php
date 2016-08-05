<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <h2>Verify Your Email Address</h2>

        <div>
            Thanks for creating an account with the Flashfind.
            Please follow the link below to verify your email address: 
            {{ url('user/emailconfirmation/'.$cust_url }}<br/>

        </div>

    </body>
</html>