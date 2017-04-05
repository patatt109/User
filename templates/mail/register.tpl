{extends 'mail/base.tpl'}

{block 'content'}
    <h1>Ваш пароль</h1>
    <br/>
    E-mail для входа: {$email} <br/>
    Ваш пароль: {$password} <br/>
    Вход в личный кабинет: <a href="{$hostInfo}{url route="user:login"}">Войти</a>
{/block}