{if $pagination->pages > 1}
    <nav class="pagination" aria-label="Страницы">
        {if $pagination->page > 1}
            <a class="pagination__link" href="?sort={$sort}&amp;page={$pagination->page - 1}">&larr;</a>
        {/if}
        {for $number = 1 to $pagination->pages}
            {if $number === $pagination->page}
                <span class="pagination__link pagination__link--active">{$number}</span>
            {else}
                <a class="pagination__link" href="?sort={$sort}&amp;page={$number}">{$number}</a>
            {/if}
        {/for}
        {if $pagination->page < $pagination->pages}
            <a class="pagination__link" href="?sort={$sort}&amp;page={$pagination->page + 1}">&rarr;</a>
        {/if}
    </nav>
{/if}
