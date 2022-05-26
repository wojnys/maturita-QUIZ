<h3>Přihlášení</h3>
<div class="register-form">
<form action="<?= base_url('UserLoginController/login'); ?>" method = "post">
    <label for = 'e'>Email</label>
    <input type = "email" name = "email" id  ='e'/>
    
    <label for = 'p'>Heslo</label>
    <input type = "password" name = "password" id = 'p'/>

    <input type = "submit" name = "submit" value = "Přihlášení"/>
</form>
</div>