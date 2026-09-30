<article class="card">
    {if $post.image}
        <a class="card__image" href="/post/{$post.slug}">
            <img src="{$post.image}" alt="{$post.title}" loading="lazy">
        </a>
    {/if}
    <div class="card__body">
        <h3 class="card__title"><a href="/post/{$post.slug}">{$post.title}</a></h3>
        <div class="card__meta">
            <time datetime="{$post.published_at|date_format:'%Y-%m-%d'}">{$post.published_at|date_format:'%d.%m.%Y'}</time>
            <span>Просмотры: {$post.views}</span>
        </div>
        <p class="card__description">{$post.description}</p>
        <a class="card__more" href="/post/{$post.slug}">Читать далее</a>
    </div>
</article>
