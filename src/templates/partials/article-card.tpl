<div class="article-card">
    {if $article.image}
        <div class="article-card-image">
            <a href="/article/{$article.slug}">
                <img src="/uploads/{$article.image}" alt="{$article.title}">
            </a>
        </div>
    {/if}

    <div class="article-card-content">
        <h3 class="article-card-title">
            <a href="/article/{$article.slug}">{$article.title}</a>
        </h3>

        {if $article.description}
            <p class="article-card-description">{$article.description|truncate:150}</p>
        {/if}

        <div class="article-card-meta">
            <span class="article-card-date">
                {$article.published_at|date_format:"%d.%m.%Y"}
            </span>
            <span class="article-card-views">
                👁 {$article.views}
            </span>
        </div>
    </div>
</div>
