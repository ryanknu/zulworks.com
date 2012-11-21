<div style="margin-left:80px;margin-right:80px;">
{foreach from=$blogs item=blog}
    <h2>{$blog->title}</h2>
    <p><i>({$blog->date})</i></p>
    <div>{$blog->text}</div>
{/foreach}
</div>
