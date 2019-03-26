<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 26/03/2019
 * Time: 13:24
 */

namespace App\Service;


use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Session\Session;

class ShoppingCart
{
    const CART_PRODUCTS_KEY = '_shopping_cart.products';

    private $session;
    private $manager;

    private $products;

    public function __construct(Session $session, ObjectManager $manager)
    {
        $this->session = $session;
        $this->manager = $manager;
    }

    public function addProduct(Product $product)
    {
        $products = $this->getProducts();
        if(!in_array($product, $products)){
            $products[] = $product;
        }
        $this->updateProducts($products);
    }

    public function getProducts()
    {
        if($this->products === null){
            $productRepo = $this->manager->getRepository(ProductRepository::class);
            $ids = $this->session->get(self::CART_PRODUCTS_KEY, []);
            $products = [];
            foreach($ids as $id){
                $product = $productRepo->find($id);
                // if product becomes deleted
                if($product) {
                    $products[] = $product;
                }
            }
            $this->products = $products;

        }
        return $this->products;
    }

    public function getTotal()
    {
        $total = 0;
        /** @var Product $product */
        foreach($this->getProducts() as $product){
            $total += $product->getPrice();
        }
        return $total;
    }

    public function emptyCart()
    {
        $this->updateProducts([]);
    }

    public function updateProducts(array $products)
    {
        $this->products = $products;
        $ids = array_map(function(Product $item){
            return $item->getId();
        }, $products);
        $this->session->set(self::CART_PRODUCTS_KEY, $ids);
    }


}