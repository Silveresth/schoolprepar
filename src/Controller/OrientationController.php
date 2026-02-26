<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrientationController extends AbstractController
{

    public function quiz()
    {
        return $this->render('orientation/quiz.html.twig');
    }
}

?>