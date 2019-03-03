<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 03/03/2019
 * Time: 13:35
 */

namespace App\Twig;


use App\Repository\CategoryRepository;
use App\Repository\CustomProductInfoRepository;

class FooterExtension extends \Twig_Extension
{
    private $customProductInfoRepository;
    private $categoryRepository;

    public function __construct(CustomProductInfoRepository $customProductInfoRepository, CategoryRepository $categoryRepository)
    {

        $this->customProductInfoRepository = $customProductInfoRepository;
        $this->categoryRepository = $categoryRepository;
    }
}