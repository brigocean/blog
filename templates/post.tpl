{extends file='layout.tpl'}

{block name=title}{$post.title}{/block}

{block name=content}
    <article class="post">
        <h1 class="post__title">{$post.title}</h1>
        <div class="post__meta">
            <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
            <span>Просмотры: {$post.views}</span>
        </div>
        {if $categories}
            <ul class="tags">
                {foreach $categories as $category}
                    <li><a class="tags__item" href="/category/{$category.slug}">{$category.name}</a></li>
                {/foreach}
            </ul>
        {/if}
        {if $post.image}
            <img class="post__image" src="{$post.image}" alt="{$post.title}">
        {/if}
        <p class="post__description">{$post.description}</p>
        <div class="post__content">
            {foreach $paragraphs as $paragraph}
                <p>{$paragraph}</p>
            {/foreach}
        </div>
    </article>

    {if $similar}
        <section class="section">
            <div class="section__head">
                <h2 class="section__title">Похожие статьи</h2>
            </div>
            <div class="posts-grid">
                {foreach $similar as $item}
                    {include file='partials/post_card.tpl' post=$item}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
