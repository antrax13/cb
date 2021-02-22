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
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class FooterExtension extends AbstractExtension
{
    private $customProductInfoRepository;
    private $categoryRepository;

    public function __construct(CustomProductInfoRepository $customProductInfoRepository, CategoryRepository $categoryRepository)
    {
        $this->customProductInfoRepository = $customProductInfoRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_shop_categories', array($this, 'get_shop_categories')),
            new TwigFunction('get_custom_products', array($this, 'get_custom_products')),
        ];
    }

    public function get_shop_categories()
    {
        return $this->categoryRepository->findBy([
            'isActive' => true
        ]);
    }

    public function get_custom_products()
    {
        return $this->customProductInfoRepository->findBy([
            'isFeatured' => true
        ]);
    }
}