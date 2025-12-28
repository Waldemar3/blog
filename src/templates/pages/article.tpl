{extends file="layouts/main.tpl"}

{block name="content"}
    <article class="article-detail">
        <header class="article-header">
            <h1 class="article-title">{$article.title}</h1>

            <div class="article-meta">
                <span class="article-date">
                    {$article.published_at|date_format:"%d.%m.%Y"}
                </span>
                <span class="article-views">
                    👁 {$article.views} просмотров
                </span>
            </div>

            {if $categories}
                <div class="article-categories">
                    {foreach from=$categories item=category name=cats}
                        <a href="/category/{$category.slug}" class="category-badge">{$category.name}</a>
                    {/foreach}
                </div>
            {/if}
        </header>

        {if $article.image}
            <div class="article-image">
                <img src="/uploads/{$article.image}" alt="{$article.title}">
            </div>
        {/if}

        {if $article.description}
            <div class="article-description">
                <p><strong>{$article.description}</strong></p>
            </div>
        {/if}

        <div class="article-content">
            {$article.content|nl2br}
        </div>
    </article>

    {if $similarArticles}
        <section class="similar-articles">
            <h2>Похожие статьи</h2>
            <div class="articles-grid">
                {foreach from=$similarArticles item=similarArticle}
                    {include file="partials/article-card.tpl" article=$similarArticle}
                {/foreach}
            </div>
        </section>
    {/if}

    <div class="back-link">
        <a href="javascript:history.back()" class="btn btn-secondary">← Назад</a>
    </div>
{/block}
