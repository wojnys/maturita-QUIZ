<style>
.register-form{
    display:flex;
    width:100%;
    justify-content: center;
}

form {
    display:flex;
    flex-direction: column;
}
.register-form form input{
    width:50vw;
    border-radius: 4px;
    border:1px solid black;
}
input[type="submit"] {
    background-color: green;
    border: none;
    padding:5px;
    margin:10px 0px;
    color:white;
}

</style>


<h3>Registrace</h3>
<div class="register-form">
    <form action = "<?=base_url('/UserRegisterController/registration'); ?>" method = "POST">
    
    <label for = 'nam'>Jméno</label>
    <input type = "text" name = "name" id = 'nam' value="<?= set_value('name');?>">
    <?php
     $validation =  \Config\Services::validation();
    if($validation->getError('name')): ?>
        <div class="alert alert-danger"><?= $validation->getError('name'); ?></h1></div>
    <?php endif ?>

    <label for = 'e'>Email</label>
    <input type = "email" name = "email" id  ='e' value="<?= set_value('email');?>"/>
    <?php
     $validation =  \Config\Services::validation();
    if($validation->getError('email')): ?>
        <div class="alert alert-danger"><?= $validation->getError('email'); ?></h1></div>
    <?php endif ?>

    <label for = 'p'>Heslo</label>
    <input type = "password" name = "password" id = 'p' />
    <?php
     $validation =  \Config\Services::validation();
    if($validation->getError('password')): ?>
        <div class="alert alert-danger"><?= $validation->getError('password'); ?></h1></div>
    <?php endif ?>

    <label for = 'pc'>Heslo znovu</label>
    <input type = "confPass" name = "confPass" id = 'pc'/>
    <?php
     $validation =  \Config\Services::validation();
    if($validation->getError('confPass')): ?>
        <div class="alert alert-danger"><?= $validation->getError('confPass'); ?></h1></div>
    <?php endif ?>
    <input type = "submit" name = "submit" value = "Registrace"/>
    </form>
</div>