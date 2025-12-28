{extends file="layouts/main.tpl"}

{block name="content"}
    <h1 class="page-title">Добро пожаловать в наш блог</h1>

    {if $categoriesWithArticles}
        {foreach from=$categoriesWithArticles item=item}
            <section class="category-section">
                <div class="category-header">
                    <h2 class="category-title">
                        <a href="/category/{$item.category.slug}">{$item.category.name}</a>
                    </h2>
                    <a href="/category/{$item.category.slug}" class="btn btn-primary">Все статьи</a>
                </div>

                {if $item.category.description}
                    <p class="category-description">{$item.category.description}</p>
                {/if}

                <div class="articles-grid">
                    {foreach from=$item.articles item=article}
                        {include file="partials/article-card.tpl" article=$article}
                    {/foreach}
                </div>
            </section>
        {/foreach}
    {else}
        <div class="empty-state">
            <p>Пока нет опубликованных статей.</p>
        </div>
    {/if}
{/block}
