<?

import("package/Post.php");

class OEmbedParser {

    public function __construct() {

    }

    public function parse($url) {
        if (preg_match("/youtube/", $url)) {
            return $this->parseYouTube($url);
        } else if (preg_match("/vimeo/", $url)) {
            return $this->parseVimeo($url);
        } else if (preg_match("/coub/", $url)) {
            return $this->parseCoub($url);
        } else {

        }
    }

    /**
     * @param string $url
     * @return Post
     */
    private function parseYouTube($url) {
        $connection = curl_init();
        parse_str(parse_url($url, PHP_URL_QUERY ), $url_vars);

        $video_id = $url_vars["v"];

        $url = sprintf("http://www.youtube.com/oembed?url=%s&format=json", urlencode($url));
        curl_setopt($connection,CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $json = curl_exec($connection);
        curl_close($connection);

        $data = json_decode($json, true);

        $post = new Post();
        $post->content_id = $video_id;
        $post->content_type = 0;
        $post->title = $data["title"];
        $post->thumbnail_url = $data["thumbnail_url"];

        return $post;
    }

    private function parseVimeo($url) {
        $connection = curl_init();

        $url = sprintf("http://vimeo.com/api/oembed.json?url=%s", urlencode($url));
        curl_setopt($connection,CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $json = curl_exec($connection);
        curl_close($connection);

        $data = json_decode($json, true);

        $post = new Post();
        $post->content_id = $data["video_id"];
        $post->content_type = 1;
        $post->title = $data["title"];
        $post->thumbnail_url = $data["thumbnail_url"];

        return $post;
    }

    private function parseCoub($url) {
        $connection = curl_init();
        $parts = mb_split("/", $url);
        $video_id = $parts[count($parts) - 1];

        $url = sprintf("http://coub.com/api/oembed.json?url=%s", urlencode($url));
        curl_setopt($connection,CURLOPT_URL, $url);
        curl_setopt($connection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connection, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $json = curl_exec($connection);
        curl_close($connection);

        $data = json_decode($json, true);

        $post = new Post();
        $post->content_id = $video_id;
        $post->content_type = 2;
        $post->title = $data["title"];
        $post->thumbnail_url = $data["thumbnail_url"];

        return $post;
    }
} 