$publishedPosts = Post::published()->get();
$draftPosts = Post::draft()->get();
$authorPosts = Post::byAuthor(1)->get();
$categoryPosts = Post::byCategory(3)->get();
$recentPosts = Post::published()->recent()->get();

---
$parentCategories = Category::parent()->get();
$childrenCategories = Category::childrenOf(1)->get();

---
$rootComments = Comment::root()->get();

---
$posts = Post::published()
             ->byCategory(2)
             ->recent()
             ->get();
