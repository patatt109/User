{extends 'user/auth_base.tpl'}

{block 'tabs'}
    {set $active = 'register'}
    {parent}
{/block}

{block 'form'}
    <form action="{url 'user:register'}" method="post">
        {raw $register_form->render()}
        <div class="button-line">
            <button type="submit" class="button upper expand black height bold">
                {t 'User.main' 'Register'}
            </button>
        </div>
    </form>
{/block}