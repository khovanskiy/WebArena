<?

class Post {

    //private static $cache = array();

    private $post_id;
    public $type;
    public $content_type;
    public $title;
    public $content_id;
    public $thumbnail_url;

    public function __construct() {

    }

    public function getPostId() {
        return $this->post_id;
    }

    /*public static function get($post_id) {
        if (isset(self::$cache[$post_id])) {
            $post = self::$cache[$post_id];
        } else {
            $post = new Post();
            self::$cache[$post_id] = $post;
        }
        $post->post_id = $post_id;
        return $post;
    }*/
} 