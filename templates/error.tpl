{extends file='layout.tpl'}

{block name=title}Ошибка {$code}{/block}

{block name=content}
    <div class="error-page">
        <p class="error-page__code">{$code}</p>
        <p class="error-page__message">{$message}</p>
        <a class="button" href="/">На главную</a>
    </div>
{/block}
