cd /var/www/ntt && php -r '
require "vendor/autoload.php";
$app = require_once "bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Check the latest post
$post = App\Models\Post::find(4195);
echo "Post 4195 status: " . $post->status . "\n";
echo "Post 4195 published: " . $post->published . "\n";
echo "Post 4195 category_id: " . $post->category_id . "\n";
echo "Post 4195 user_id: " . $post->user_id . "\n";
echo "Post 4195 visibility: " . $post->visibility . "\n";

// Check categories attached
$cats = DB::table("post_categories")->where("post_id", 4195)->get();
echo "Post categories: " . json_encode($cats) . "\n";

// Now check what the API query returns
$latestIds = App\Models\Post::where("status", "published")->orderBy("id", "desc")->take(5)->pluck("id");
echo "Latest 5 published IDs: " . json_encode($latestIds) . "\n";
'
