<?php


namespace App\View;


use App\Entity\Post;
use App\Service\SecurityUtils;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class PostDetailView
{
    /**
     * @var CacheManager
     */
    private CacheManager $imagineCacheManager;
    /**
     * @var SecurityUtils
     */
    private SecurityUtils $securityUtils;

    private string $publicDirectory;

    /**
     * PostDetailView constructor
     *
     * @param SecurityUtils $securityUtils
     * @param CacheManager $imagineCacheManager
     * @param string $publicDirectory
     */
    public function __construct(
        SecurityUtils $securityUtils,
        CacheManager $imagineCacheManager,
        string $publicDirectory
    ) {
        $this->imagineCacheManager = $imagineCacheManager;
        $this->securityUtils = $securityUtils;
        $this->publicDirectory = $publicDirectory;
    }

    /**
     * Creates view parameters for the the post detail page
     *
     * @param $post
     * @return array[]
     */
    public function create($post): array
    {
        return [
            'post' => $this->createTransformedPost($post),
            'staticMapUrl' => http_build_query($this->createStaticMapUrl($post)),
        ];
    }

    /**
     * Transforms the Post entities to a simple array
     * It gets the uploaded photo url and whether or not the current user liked the post
     *
     * @param Post $post
     * @return array
     */
    private function createTransformedPost(Post $post): array
    {
        $user = $this->securityUtils->getUser();

        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'latitude' => $post->getLatitude(),
            'longitude' => $post->getLongitude(),
            'photo' => $this->publicDirectory.'/uploads/photos/'.$post->getPhotoFilename(),
            'isLiked' => $user ? boolval($post->getReactionForUser($user)) : null,
        ];
    }

    /**
     * @param Post $post
     * @return array
     */
    private function createStaticMapUrl(Post $post): array
    {
        return  [
            "size" => "650,350",
            "type" => "map",
            "imagetype" => "JPEG",
            "scalebar" => "false",
            "key" => "cxG9e8Lld7SnlIszpwyfbbEhDt4QNbYe",
            "pois" => "mcenter,{$post->getLatitude()},{$post->getLongitude()}",
        ];
    }
}