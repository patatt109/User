{extends 'user/auth_base.tpl'}

{block 'tabs'}
    {set $active = 'login'}
    {parent}
{/block}

{block 'form'}
    <form action="{url 'user:login'}" method="post">
        {raw $login_form->render()}
        <div class="button-line">
            <button type="submit" class="button upper expand black height bold">
                {t 'User.main' 'Log in'}
            </button>
        </div>
    </form>
{/block}