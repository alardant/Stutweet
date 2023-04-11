<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;
use Symfony\Component\Routing\Annotation\Route;

class LuckyController extends AbstractController
{
    /**
     * @Route("lucky", name="app_lucky_number")
     */
    public function number(): HttpFoundationResponse
    {
        $number = random_int(0, 100);

        return new HttpFoundationResponse("<html><h1>$number</h1></html>");
    }

    #[Route('/random', name: 'app_random_number')]
    public function random(): HttpFoundationResponse
    {
        $number = random_int(0, 100);

        return $this->render(
            'number.html.twig',
            ['number' => $number]
        );
    }
}
