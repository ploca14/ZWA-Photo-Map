<?php


namespace App\View;


use App\Entity\Offer;
use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\SecurityUtils;
use Granam\CzechVocative\CzechName;

class HomepageView
{
    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var SecurityUtils
     */
    private SecurityUtils $securityUtils;

    /**
     * @param PostRepository $postRepository
     * @param SecurityUtils $securityUtils
     */
    public function __construct(
        PostRepository $postRepository,
        SecurityUtils $securityUtils
    ) {
        $this->postRepository = $postRepository;
        $this->securityUtils = $securityUtils;
    }

    /**
     * @return array
     */
    public function create() {
        $parameters = [
            'posts' => $this->transformPosts($this->postRepository->findAll()),
        ];

        if ($user = $this->securityUtils->getUser()) {
            $name = new CzechName();
            $vocative = $name->vocative($user->getName());

            $parameters['name'] = $vocative;
        }

        return $parameters;
    }


    /**
     * @param Post[] $posts
     * @return array
     */
    private function transformPosts(array $posts) {
        $user = $user = $this->securityUtils->getUser();
        $data = [];

        //transform retrieved data to form readable by frontend
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'latitude' => $post->getLatitude(),
                'longitude' => $post->getLongitude(),
                'photoFilename' => $post->getPhotoFilename(),
                'isLiked' => $post->getReactionForUser($user)
            ];
        }

        return $data;
    }
}