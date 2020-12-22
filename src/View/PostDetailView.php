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

    /**
     * PostDetailView constructor
     *
     * @param SecurityUtils $securityUtils
     * @param CacheManager $imagineCacheManager
     */
    public function __construct(SecurityUtils $securityUtils,CacheManager $imagineCacheManager) {
        $this->imagineCacheManager = $imagineCacheManager;
        $this->securityUtils = $securityUtils;
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
            'photo' => '/uploads/photos/'.$post->getPhotoFilename(),
            'isLiked' => $user ? boolval($post->getReactionForUser($user)) : null,
        ];
    }
}