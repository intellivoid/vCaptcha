<?php

    require 'ppm';
    require 'net.intellivoid.vcaptcha';

    $vcaptcha = new \vCaptcha\vCaptcha();
    $instance = $vcaptcha->getCaptchaInstanceManager()->getInstance('33da77bc-13d8-474e-b293-5ed550a7b7b4');

    if($instance->CaptchaType !== \vCaptcha\Abstracts\CaptchaType::None)
    {
        print("Captcha instance not supported");
        exit();
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vcaptcha_challenge_field']))
    {
        try
        {
            $captcha = $vcaptcha->getCaptchaManager()->getCaptcha($_POST['vcaptcha_challenge_field']);
        }
        catch(\vCaptcha\Exceptions\CaptchaNotFoundException $e)
        {
            print("Captcha not found");
            exit();
        }
        catch(Exception $e)
        {
            print("Error: " . $e->getMessage());
            exit();
        }
    }

    $captcha = $vcaptcha->getCaptchaManager()->createCaptcha($instance);

?>
<!DOCTYPE html>
<html>
    <head>
        <title>Captcha None</title>
    </head>
    <body>
        <h2>Captcha None</h2>

        <form action="captcha_none.php" method="POST">
            <label for="fname">First name:</label>
            <br/>
            <input type="text" id="fname" name="fname" value="John">
            <br/>

            <label for="lname">Last name:</label>
            <br/>
            <input type="text" id="lname" name="lname" value="Doe">
            <br/><br/>

            <input type="hidden" id="vcaptcha_challenge_field" name="vcaptcha_challenge_field" value="<?PHP print($captcha->ID) ?>">
            <input type="submit" value="Submit">
        </form>

    </body>
</html>

