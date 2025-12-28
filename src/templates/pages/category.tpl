{extends file="layouts/main.tpl"}

{block name="content"}
    <div class="category-page">
        <div class="category-header">
            <h1 class="page-title">{$category.name}</h1>
            {if $category.description}
                <p class="category-description">{$category.description}</p>
            {/if}
        </div>

        <div class="sorting">
            <label>Сортировка:</label>
            <a href="/category/{$category.slug}?sort=published_at{if $currentPage > 1}&page={$currentPage}{/if}"
               class="sort-link {if $sortBy == 'published_at'}active{/if}">
                По дате
            </a>
            <a href="/category/{$category.slug}?sort=views{if $currentPage > 1}&page={$currentPage}{/if}"
               class="sort-link {if $sortBy == 'views'}active{/if}">
                По просмотрам
            </a>
        </div>

        {if $articles}
            <div class="articles-grid">
                {foreach from=$articles item=article}
                    {include file="partials/article-card.tpl" article=$article}
                {/foreach}
            </div>

            {if $totalPages > 1}
                <div class="pagination">
                    {if $currentPage > 1}
                        <a href="/category/{$category.slug}?page={$currentPage - 1}&sort={$sortBy}" class="pagination-link">← Предыдущая</a>
                    {/if}

                    {for $page=1 to $totalPages}
                        {if $page == $currentPage}
                            <span class="pagination-link active">{$page}</span>
                        {else}
                            <a href="/category/{$category.slug}?page={$page}&sort={$sortBy}" class="pagination-link">{$page}</a>
                        {/if}
                    {/for}

                    {if $currentPage < $totalPages}
                        <a href="/category/{$category.slug}?page={$currentPage + 1}&sort={$sortBy}" class="pagination-link">Следующая →</a>
                    {/if}
                </div>
            {/if}
        {else}
            <div class="empty-state">
                <p>В этой категории пока нет статей.</p>
            </div>
        {/if}
    </div>
{/block}
