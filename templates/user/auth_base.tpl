{extends 'base.tpl'}

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
                            Вход
                        </span>
                    </a>
                </li>
                <li class="{if $active == 'register'}active{/if}">
                    <a href="{url 'user:register'}">
                        <span class="name">
                            Регистрация
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

