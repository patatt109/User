{extends 'mail/base.tpl'}

{block 'content'}
    <h1>Восстановление пароля</h1>
    <br/>
    Ваш новый пароль: {$password} <br/>
    Вход в личный кабинет: <a href="{$hostInfo}{url route="user:login"}">Войти</a>
{/block}