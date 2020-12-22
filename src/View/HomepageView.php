<?php


namespace App\View;


use App\Entity\Post;
use App\Repository\PostRepository;
use App\Service\SecurityUtils;
use Granam\CzechVocative\CzechName;
use Knp\Component\Pager\PaginatorInterface;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

class HomepageView
{
    const LIMIT_PER_PAGE = 5;

    /**
     * @var PostRepository
     */
    private PostRepository $postRepository;

    /**
     * @var SecurityUtils
     */
    private SecurityUtils $securityUtils;

    /**
     * @var CacheManager
     */
    private CacheManager $imagineCacheManager;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @param PostRepository $postRepository
     * @param SecurityUtils $securityUtils
     * @param RouterInterface $router
     * @param CacheManager $imagineCacheManager
     */
    public function __construct(
        PostRepository $postRepository,
        SecurityUtils $securityUtils,
        RouterInterface $router,
        CacheManager $imagineCacheManager,
        PaginatorInterface $paginator
    ) {
        $this->postRepository = $postRepository;
        $this->securityUtils = $securityUtils;
        $this->router = $router;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->paginator = $paginator;
    }

    /**
     * Creates view parameters for the homepage
     * It queries the database based on the post filter, then it paginates the result and transforms
     * the Post entities for frontend
     * It also generates a static map url for users with disabled javascript
     *
     * @param bool $showFavorite
     * @param Request $request
     * @return array
     */
    public function create(Request $request, bool $showFavorite): array
    {
        if ($showFavorite) {
            $user = $this->securityUtils->getUser();
            $query = $this->postRepository->findLikedForUser($user);
        } else {
            $query = $this->postRepository->findAll();
        }

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('strana', 1),
            self::LIMIT_PER_PAGE
        );

        $posts = $this->createTransformedPosts($pagination->getItems());

        $parameters = [
            'posts' => $posts,
            'staticMapUrl' => http_build_query($this->createStaticMapUrl($posts)),
            'isFiltered' => $showFavorite,
            'paginator' => $pagination,
        ];

        if ($user = $this->securityUtils->getUser()) {
            $name = new CzechName();
            $vocative = $name->vocative($user->getName());

            $parameters['name'] = $vocative;
        }

        return $parameters;
    }


    /**
     * Transforms the Post entities to a simple array
     * It gets the uploaded photo thumbnail url and whether or not the current user liked the post
     *
     * @param iterable $posts
     * @return array
     */
    private function createTransformedPosts(iterable $posts): array
    {
        $user = $this->securityUtils->getUser();
        $data = [];

        //transform retrieved data to form readable by frontend
        foreach ($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'description' => $post->getDescription(),
                'latitude' => $post->getLatitude(),
                'longitude' => $post->getLongitude(),
                'photo' => $this->imagineCacheManager->getBrowserPath('uploads/photos/'.$post->getPhotoFilename(), 'my_thumb'),
                'isLiked' => $user ? boolval($post->getReactionForUser($user)) : null,
                'link' => $this->router->generate('post_detail', ['id' => $post->getId()])
            ];
        }

        return $data;
    }

    /**
     * @param array $posts
     * @return array
     */
    private function createStaticMapUrl(array $posts): array
    {
        return  [
            "size" => "950,860",
            "type" => "map",
            "imagetype" => "JPEG",
            "scalebar" => "false",
            "key" => "cxG9e8Lld7SnlIszpwyfbbEhDt4QNbYe",
            "pois" => $this->createPoisForStaticMap($posts),
        ];
    }

    /**
     * Generates a static map url for users with disabled javascript
     *
     * @param array $posts
     * @return string
     */
    private function createPoisForStaticMap(array $posts): string
    {
        $data = "";

        foreach($posts as $post) {
            $data .= "mcenter,{$post['latitude']},{$post['longitude']}|";
        }

        return $data;
    }
}