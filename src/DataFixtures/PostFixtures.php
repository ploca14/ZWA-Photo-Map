<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Service\UploadHelper;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class PostFixtures extends Fixture
{
    private const POSTS = [
        [
            "title" => "Grand Place",
            "description" => "Zde můžete pořídit snímek s širokoúhlým objektivem, aby se budova vešla celá do záběru, nebo využít teleobjektivu a zachytit detaily věže.",
            "latitude" => 50.84678,
            "longitude" => 4.35294,
            "photoFilename" => "IMG_6083.jpg"
        ],
        [
            "title" => "Arc de Triomphe",
            "description" => "Pro toto místo je obecně dobré mít širokoúhlý objektiv a pořídit snímek s fotoaparátem opravdu blízko k zemi, čímž se zvětší vizuální velikost oblouku.",
            "latitude" => 48.8742817,
            "longitude" => 2.2940412,
            "photoFilename" => "IMG_6260.jpg"
        ],
        [
            "title" => "Eiffelova vež z Trocadera",
            "description" => "Jděte tam brzy. Velmi rušné a někdy bláznivě přeplněné. Pokud je to možné, použijte stativ. Dávejte pozor na lidi, kteří se mohou motat okolo vašeho fotoaparátu, aby pořídili své snímky.",
            "latitude" => 48.86208,
            "longitude" => 2.28869,
            "photoFilename" => "IMG_6312.jpg"
        ],
        [
            "title" => "Eiffelova vež z Port Debilly",
            "description" => "Hledejte pěkný záběr mezi stromy a pokuste se dostat nějaké lodě do popředí.",
            "latitude" => 48.85943,
            "longitude" => 2.29054,
            "photoFilename" => "IMG_6332.jpg"
        ],
        [
            "title" => "Notre Dame z lodi",
            "description" => "Notre Dam se dá krásně zachytit i z řeky.",
            "latitude" => 48.85332,
            "longitude" => 2.34698,
            "photoFilename" => "IMG_6394.jpg"
        ],
        [
            "title" => "Sacre Coeur",
            "description" => "Krásné místo s výhledem na Paříž. Tím, že je budova na vrcholu kopce, lze ji zachytit s nic nerušenou oblohou například při západu slunce.",
            "latitude" => 48.88452,
            "longitude" => 2.34344,
            "photoFilename" => "IMG_6420.jpg"
        ],
        [
            "title" => "Alpe d'Huez",
            "description" => "Nádherné západy a východy slunce ve Francouzkých Alpách.",
            "latitude" => 45.085657,
            "longitude" => 6.092838,
            "photoFilename" => "IMG_6799.jpg"
        ],
        [
            "title" => "Verdon Gorge",
            "description" => "Zde můžete pořídit spoustu dobrých snímků. Kaňon je celý osvětlen pouze slunečním světlem kolem poledne. Ale to nejlepší světlo nastává až večer.",
            "latitude" => 43.78098,
            "longitude" => 6.39044,
            "photoFilename" => "IMG_6851.jpg"
        ],
        [
            "title" => "Collégiale Saint Paul",
            "description" => "Malé náměstíčko v kouzelném historickém městečku. Choďte s hlavou nahoru, je zde spousta krásných věžiček.",
            "latitude" => 43.69699,
            "longitude" => 7.12208,
            "photoFilename" => "IMG_7064.jpg"
        ],
        [
            "title" => "Saint-Paul-de-Vence",
            "description" => "Zde můžete vyfotit spoustu krásných uliček v tomto malebném historickém městečku.",
            "latitude" => 43.69682,
            "longitude" => 7.122501,
            "photoFilename" => "IMG_7083.jpg"
        ],
        [
            "title" => "Dvě věže",
            "description" => "Hned dvě šikmé věže vedle sebe. Bohužel je zde spousta trolejí, takže je třeba najít takové místo, kde lze obě věže vyfotit, aniž by byly překryté trolejemi.",
            "latitude" => 44.494561,
            "longitude" => 11.346591,
            "photoFilename" => "IMG_7246.jpg"
        ],
        [
            "title" => "Most vzdechů",
            "description" => "Nejlepší čas na focení je brzy ráno, protože přes den je toto místo přeplněné turisty.",
            "latitude" => 45.433714,
            "longitude" => 12.340900,
            "photoFilename" => "IMG_7328.jpg"
        ],
    ];

    /**
     * @var UploadHelper
     */
    private UploadHelper $uploadHelper;

    /**
     * PostFixtures constructor
     *
     * @param UploadHelper $uploadHelper
     */
    public function __construct(UploadHelper $uploadHelper) {
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Generates dummy posts in the database
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::POSTS as $preparedPost) {
            $photoFilename = $this->fakeUploadPhoto($preparedPost['photoFilename']);

            $post = new Post();
            $post->setTitle($preparedPost['title']);
            $post->setDescription($preparedPost['description']);
            $post->setLatitude($preparedPost['latitude']);
            $post->setLongitude($preparedPost['longitude']);
            $post->setPhotoFilename($photoFilename);

            $manager->persist($post);
        }

        $manager->flush();
    }

    /**
     * Fakes uploading of a photo
     *
     * @param string $photoFilename
     * @return string
     */
    private function fakeUploadPhoto(string $photoFilename): string
    {
        $fs = new Filesystem();
        $targetPath = sys_get_temp_dir().'/'.$photoFilename;
        $fs->copy(__DIR__.'/photos/'.$photoFilename, $targetPath, true);

        return $this->uploadHelper
            ->uploadPostPhoto(new File($targetPath));
    }
}
