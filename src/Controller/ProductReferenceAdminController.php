<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 12/04/2019
 * Time: 14:18
 */

namespace App\Controller;


use App\Entity\CustomProductInfo;
use App\Entity\Product;
use App\Entity\ProductReference;
use App\Repository\ProductReferenceRepository;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductReferenceAdminController extends AbstractController
{
    /**
     * @Route("/admin/custom-product/{id}/references", name="admin_custom_product_add_reference", methods="POST")
     * @IsGranted("ROLE_ADMIN")
     */
    public function uploadCustomProductReference(CustomProductInfo $productInfo, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('reference');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload',
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        "image/*",
                    ],
                ])
            ]
        );

        if($violations->count() > 0){
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            return $this->json($violations, 400);
        }

        $fileSize = number_format($uploadedFile->getSize() / 1048576, 2) . 'MB';

        $filename = $uploaderHelper->uploadFile($uploadedFile, $this->getParameter('product_reference_dir'));

        $productReference = new ProductReference();
        $productReference->setCustomProduct($productInfo);
        $productReference->setFilename($filename);
        $productReference->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
        $productReference->setExtension($uploadedFile->getClientOriginalExtension());
        // octet-stream - no idea what filetype is that uploaded file in other words :)
        $productReference->setMimeType($uploadedFile->getClientMimeType() ?? 'application/octet-stream');
        $productReference->setFileSize($fileSize);

        $entityManager->persist($productReference);
        $entityManager->flush();

        return $this->json(
            $productReference,
            201,
            [],
            [
                'groups' => ['main'],
            ]
        );

    }


    /**
     * @Route("/admin/products/{id}/references", name="admin_shop_product_add_reference", methods="POST")
     * @IsGranted("ROLE_ADMIN")
     */
    public function uploadShopProductReference(Product $product, Request $request, UploaderHelper $uploaderHelper, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        /** @var UploadedFile $uploadedFile */
        $uploadedFile = $request->files->get('reference');

        $violations = $validator->validate(
            $uploadedFile,
            [
                new NotBlank([
                    'message' => 'Please select a file to upload',
                ]),
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        "image/*",
                    ],
                ])
            ]
        );

        if($violations->count() > 0){
            /** @var ConstraintViolation $violation */
            $violation = $violations[0];
            return $this->json($violations, 400);
        }

        $fileSize = number_format($uploadedFile->getSize() / 1048576, 2) . 'MB';

        $filename = $uploaderHelper->uploadFile($uploadedFile, $this->getParameter('product_reference_dir'));

        $productReference = new ProductReference();
        $productReference->setShopProduct($product);
        $productReference->setFilename($filename);
        $productReference->setOriginalFilename($uploadedFile->getClientOriginalName() ?? $filename);
        $productReference->setExtension($uploadedFile->getClientOriginalExtension());
        // octet-stream - no idea what filetype is that uploaded file in other words :)
        $productReference->setMimeType($uploadedFile->getClientMimeType() ?? 'application/octet-stream');
        $productReference->setFileSize($fileSize);

        $entityManager->persist($productReference);
        $entityManager->flush();

        return $this->json(
            $productReference,
            201,
            [],
            [
                'groups' => ['main'],
            ]
        );

    }




    /**
     * @Route("/admin/custom-products/{id}/references", name="admin_custom_product_list_references", methods="GET")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getCustomProductReferences(CustomProductInfo $productInfo)
    {
        return $this->json(
            $productInfo->getProductReferences(),
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/products/{id}/references", name="admin_shop_product_list_references", methods="GET")
     * @IsGranted("ROLE_ADMIN")
     */
    public function getShopProductReferences(Product $product)
    {
        return $this->json(
            $product->getProductReferences(),
            200,
            [],
            [
                'groups' => ['main']
            ]
        );
    }

    /**
     * @Route("/admin/custom-products/references/{id}", name="admin_custom_product_reference_delete", methods="DELETE")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteCustomProductReference(ProductReference $reference, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper)
    {
        // this should be transaction according to RYAN
        $entityManager->remove($reference);
        $entityManager->flush();

        $uploaderHelper->deleteFile($this->getParameter('product_reference_dir').'/'.$reference->getFilename());

        return new Response(null, 204);
    }

    /**
     * @Route("/admin/custom-products/references/{id}", name="admin_custom_product_reference_edit", methods="PUT")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editCustomProductReference(ProductReference $reference, EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        $serializer->deserialize(
            $request->getContent(),
            ProductReference::class,
            'json',
            [
                'object_to_populate' => $reference,
                'groups' => ['input']
            ]
        );

        $violations = $validator->validate($reference);

        if($violations->count() > 0){
            return $this->json($violations, 400);
        }

        $entityManager->persist($reference);
        $entityManager->flush();

        return $this->json(
            $reference,
            200,
            [],
            [
                'groups' => ['main'],
            ]
        );

    }

    /**
     * @Route("/admin/custom-products/{id}/references/reorder", methods="POST", name="admin_custom_product_reference_reorder")
     * @IsGranted("ROLE_ADMIN")
     */
    public function reorderReferences(CustomProductInfo $productInfo, Request $request, EntityManagerInterface $entityManager)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if($orderedIds === false){
            return $this->json(['details' => 'Invalid Body'], 400);
        }

        // from (position) => id to (id) -> position
        $orderedIds = array_flip($orderedIds);
        foreach($productInfo->getProductReferences() as $reference){
            $reference->setPosition($orderedIds[$reference->getId()]);
        }
        $entityManager->flush();

        return $this->json($productInfo->getProductReferences(), 200, [], ['groups' => ['main']]);

    }


    /**
     * @Route("/admin/products/{id}/references/reorder", methods="POST", name="admin_shop_product_reference_reorder")
     * @IsGranted("ROLE_ADMIN")
     */
    public function reorderShopReferences(Product $product, Request $request, EntityManagerInterface $entityManager)
    {
        $orderedIds = json_decode($request->getContent(), true);

        if($orderedIds === false){
            return $this->json(['details' => 'Invalid Body'], 400);
        }

        // from (position) => id to (id) -> position
        $orderedIds = array_flip($orderedIds);
        foreach($product->getProductReferences() as $reference){
            $reference->setPosition($orderedIds[$reference->getId()]);
        }
        $entityManager->flush();

        return $this->json($product->getProductReferences(), 200, [], ['groups' => ['main']]);

    }
}