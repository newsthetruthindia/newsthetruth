<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\User;

class SyncArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ntt:sync-articles {--date= : The date to sync (YYYY-MM-DD)} {--days=1 : Number of days to look back}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate articles from Live site with DYNAMIC reporter resolution and character cleanup';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->option('date') ?? Carbon::today()->toDateString();
        $days = (int) $this->option('days');
        $startDate = Carbon::parse($date)->subDays($days - 1)->toDateString();
        
        $this->info("Starting DYNAMIC Sync for range: $startDate to $date");

        // 1. Extract Data
        $url = "https://newsthetruth.com/backup_direct.php?start=$startDate&end=$date";
        $this->info("Fetching data from: $url");
        
        $response = Http::timeout(120)->get($url);
        
        if ($response->failed()) {
            $this->error("Failed to fetch data from live site.");
            return 1;
        }

        $sql = $response->body();
        
        // 2. Local File for Import
        Storage::disk('local')->put('temp_sync.sql', $sql);

        // 3. Raw SQL Import
        try {
            DB::unprepared($sql);
            $this->info("SQL Import completed successfully.");
        } catch (\Exception $e) {
            $this->error("SQL Error (possibly due to duplicate keys): " . $e->getMessage());
        }

        // 4. Post-Import Sanitization & Dynamic Attribution
        $this->sanitizeAndAttribute($startDate);

        // 5. Media Restoration
        $this->restoreMedia();

        $this->info("=== SYNC AUTOMATION FINISHED ===");
        return 0;
    }

    /**
     * Apply byte-level cleaning and DYNAMIC reporter fixing
     */
    protected function sanitizeAndAttribute($startDate)
    {
        $this->info("Applying sanitization and dynamic attribution...");

        $replacements = [
            "\xE2\x80\x9D\xC2\x9D" => "\xE2\x80\x9D", 
            "\xE2\x80\x9C\xC2\x9D" => "\xE2\x80\x9C", 
            "\xE2\x80\x99\xC2\x9D" => "\xE2\x80\x99", 
            "\xEF\xBF\xBD"         => "'",             
            "\xC2\x9D"             => "",              
            "\xC2\x80"             => "",              
        ];

        // Load users for mapping
        $users = User::all();
        $userMap = [];
        foreach ($users as $u) {
            $fullName = strtolower(trim($u->firstname . ' ' . $u->lastname));
            $userMap[$fullName] = $u->id;
        }

        $aliasMap = [
            'ntt desk' => 0,
            'ntt staff' => 0,
            'staff reporter' => 0,
            'live update' => 0,
            'titas' => $userMap['titas mukherjee'] ?? 9,
            'sonakshi ghosh' => $userMap['soonakshi ghosh'] ?? 391,
        ];

        $posts = Post::where('created_at', '>=', $startDate)->get();

        foreach ($posts as $p) {
            // 1. Cleanup encoding
            $p->title = str_replace(array_keys($replacements), array_values($replacements), $p->title);
            $p->description  = str_replace(array_keys($replacements), array_values($replacements), $p->description);
            $p->title = str_replace(["”’", "””", "’¦"], ["”", "”", "’"], $p->title);

            // 2. Resolve Reporter
            $reporterName = null;

            // Check Metas
            foreach ($p->metas as $m) {
                if (in_array(strtolower($m->key), ['credit', 'source', 'author', 'writer', 'reporter'])) {
                    if (!empty(trim($m->description)) && strtolower(trim($m->description)) !== 'ntt desk') {
                        $reporterName = trim($m->description);
                        break;
                    }
                }
            }

            // Check Title Suffix
            if (!$reporterName) {
                if (preg_match('/\|\s*([A-Za-z\s]{5,40})$/i', $p->title, $matches)) {
                    $reporterName = trim($matches[1]);
                }
            }

            // Check Body Signature
            if (!$reporterName) {
                $body = strip_tags($p->description);
                if (preg_match('/By\s*-\s*([A-Za-z\s]{5,30})[\r\n\s]/i', substr($body, 0, 500), $matches)) {
                    $reporterName = trim($matches[1]);
                } elseif (preg_match('/By\s*-\s*([A-Za-z\s]{5,30})[\r\n\s]/i', substr($body, -500), $matches)) {
                    $reporterName = trim($matches[1]);
                }
            }

            if (!$reporterName) $reporterName = $p->reporter_name ?: "NTT Desk";

            // Clean name
            $cleanName = preg_replace('/^(By\s*-?\s*|Source\s*-?\s*|Credit\s*-?\s*)/i', '', $reporterName);
            $cleanName = ucwords(strtolower(trim($cleanName)));

            // Resolve ID
            $searchName = strtolower($cleanName);
            $newUserId = $aliasMap[$searchName] ?? ($userMap[$searchName] ?? 0);

            $p->reporter_name = $cleanName;
            $p->user_id = $newUserId;
            $p->save();
        }
    }

    /**
     * Download and fix broken image paths
     */
    protected function restoreMedia()
    {
        $this->info("Restoring missing media files...");
        
        $sourceBase = "https://newsthetruth.com/";
        $medias = DB::table('medias')->orderBy('id', 'desc')->limit(100)->get();

        foreach ($medias as $media) {
            $cleanUrl = str_replace("public/", "", $media->url);
            $fullSourceUrl = $sourceBase . "public/" . $cleanUrl;
            
            if (!Storage::disk('public')->exists($cleanUrl)) {
                 $this->info("   - Checking: $cleanUrl");
                 $content = @file_get_contents($fullSourceUrl);
                 if ($content) {
                     Storage::disk('public')->put($cleanUrl, $content);
                     DB::table('medias')->where('id', $media->id)->update(['url' => $cleanUrl, 'path' => $cleanUrl]);
                 }
            }
        }
    }
}
