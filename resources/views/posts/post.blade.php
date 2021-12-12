<div class="blog-post">
    <h2 class="blog-post-title">
    </h2>
    <p class="blog-post-meta">
        {{ $post->user->name }}
        {{ $post->created_at->toFormattedDateString() }}
    </p>
    {{ $post->body }}
</div><!-- /.blog-post -->


