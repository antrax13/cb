<?php
/**
 * Created by PhpStorm.
 * User: peterkosak
 * Date: 09/04/2019
 * Time: 14:45
 */

namespace App\Service;


use App\Entity\StampQuote;
use App\Repository\StampQuoteRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CreateStamp
{
    const CUSTOM_CREATE_QUOTE_KEY = '_create_stamp.id';
    private $session;
    private $repository;

    public function __construct(SessionInterface $session, StampQuoteRepository $repository)
    {
        $this->session = $session;
        $this->repository = $repository;
    }

    public function createCustomStampSession(?string $id)
    {
        $this->session->set(self::CUSTOM_CREATE_QUOTE_KEY, $id);
    }

    public function getQuote()
    {
        $id = $this->session->get(self::CUSTOM_CREATE_QUOTE_KEY);

        return $id ? $this->repository->find($id) : null;
    }
}