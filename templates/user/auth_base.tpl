{extends '_layouts/base.tpl'}

{block 'heading'}
{/block}

{block 'content-header'}
{/block}

{block 'content'}
    <div class="auth-block">
        {set $active = 'login'}
        {block 'tabs'}
            <ul class="lined-tabs">
                <li class="{if $active == 'login'}active{/if}">
                    <a href="{url 'user:login'}">
                        <span class="name">
                            {t 'User.main' 'Login'}
                        </span>
                    </a>
                </li>
                <li class="{if $active == 'register'}active{/if}">
                    <a href="{url 'user:register'}">
                        <span class="name">
                             {t 'User.main' 'Registration'}
                        </span>
                    </a>
                </li>
            </ul>
        {/block}

        <div class="form-wrapper">
            {block 'form'}

            {/block}
        </div>
    </div>
{/block}

