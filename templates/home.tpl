{extends file='layout.tpl'}

{block name=title}Главная{/block}

{block name=content}
    {foreach $categories as $category}
        <section class="section">
            <div class="section__head">
                <h2 class="section__title">{$category.name}</h2>
                <a class="button" href="/category/{$category.slug}">Все статьи</a>
            </div>
            <div class="posts-grid">
                {foreach $category.posts as $post}
                    {include file='partials/post_card.tpl' post=$post}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p class="empty">Статей пока нет. Заполните базу командой: php bin/seed.php</p>
    {/foreach}
{/block}
