{extends file='layout.tpl'}

{block name=title}{$category.name}{/block}

{block name=content}
    <div class="page-head">
        <h1 class="page-head__title">{$category.name}</h1>
        {if $category.description}
            <p class="page-head__lead">{$category.description}</p>
        {/if}
    </div>

    <div class="sort">
        <span class="sort__label">Сортировка:</span>
        {foreach $sorts as $value => $label}
            <a class="sort__link{if $value === $sort} sort__link--active{/if}" href="?sort={$value}">{$label}</a>
        {/foreach}
    </div>

    {if $posts}
        <div class="posts-grid">
            {foreach $posts as $post}
                {include file='partials/post_card.tpl' post=$post}
            {/foreach}
        </div>
        {include file='partials/pagination.tpl'}
    {else}
        <p class="empty">В этой категории пока нет статей.</p>
    {/if}
{/block}
