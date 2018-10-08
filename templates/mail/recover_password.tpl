{extends 'mail/base.tpl'}

{block 'content'}
    <h1>{t 'User.main' 'Recover password'}</h1>
    <br/>
    {t 'User.main' 'Your new password'}: {$password} <br/>
    {t 'User.main' 'Login to your account'}: <a href="{$hostInfo}{url route="user:login"}">{t 'User.main' 'Log in'}</a>
{/block}