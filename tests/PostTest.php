<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Post;
use App\Stats;

class PostTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        
        $post = new Post();
        $post->title = 'My breakfast cereal';
        $post->image_path = 'test.jpg';
        $expect = 'http://localhost/uploads/test.jpg';
        $this->assertEquals($expect, $post->getURL());
        
        $r = $post->getArray();
        $this->assertArrayHasKey('title', $r);
        $this->assertArrayHasKey('image_path', $r);
        $this->assertEquals($post->title, $r['title']);
        $this->assertEquals($expect, $r['image_path']);
        
        $stats = Stats::getViews();
        $this->assertGreaterThanOrEqual(0, $stats->views);
        $this->assertEquals(1, $stats->id);

        $stats2 = Stats::increaseViews();
        $this->assertGreaterThanOrEqual(1, $stats2->views);
        $this->assertEquals(1, $stats2->id);
        
        
        
        /*
         *     public static function increaseViews() {
        $stats = self::find(1);
        if (empty($stats)) {
            $stats = new Stats();
            $stats->id = 1;
            $stats->views = 1;
        } else {
            $stats->views++;
        }
        if ($stats->save()) {
            return $stats;
        }
        abort(500, 'Failed to update views');
    }
         */
        
    }

}
