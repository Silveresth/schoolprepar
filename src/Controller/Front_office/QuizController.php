<?php

namespace App\Controller\Front_office;

use App\Entity\ResultatTest;
use App\Entity\Test;
use App\Form\TestType;
use App\Repository\ResultatTestRepository;
use App\Repository\TestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/quiz', name: 'front_quiz_')]
class QuizController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(TestRepository $repo): Response
    {
        return $this->render('front/quiz/index.html.twig', [
            'tests' => $repo->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'run', requirements: ['id' => '\\d+'])]
    #[IsGranted('ROLE_USER')]
    public function run(
        int $id,
        Request $request,
        TestRepository $testRepo,
        ResultatTestRepository $resultRepo,
        EntityManagerInterface $em
    ): Response {
        $test = $testRepo->find($id);
        if (!$test) {
            throw $this->createNotFoundException('Test introuvable.');
        }

        $user = $this->getUser();
        $existing = $resultRepo->findOneBy(['user' => $user, 'test' => $test]);

        $questions = $test->getQuestions()->toArray();

        if ($request->isMethod('POST')) {
            // POST : reponse_{questionId} = reponseId
            $totalPoints = 0;
            $maxPoints = 0;

            foreach ($questions as $question) {
                $qid = $question->getId();
                if (!$qid) {
                    continue;
                }

                $selectedReponseId = $request->request->get('reponse_' . $qid);

                // maxPoints = somme des points max possibles sur la question
                $questionMax = 0;
                foreach ($question->getReponses() as $r) {
                    $questionMax = max($questionMax, (int) $r->getPoints());
                }
                $maxPoints += $questionMax;

                if (!$selectedReponseId) {
                    continue;
                }

                // Trouver la réponse sélectionnée
                foreach ($question->getReponses() as $reponse) {
                    if ((string) $reponse->getId() === (string) $selectedReponseId) {
                        $totalPoints += (int) $reponse->getPoints();
                        break;
                    }
                }
            }

            // Calcul score 0..100 (évite division par zéro)
            $score = 0.0;
            if ($maxPoints > 0) {
                $score = (float) round(($totalPoints / $maxPoints) * 100, 2);
            }

            if ($existing) {
                $existing->setScore($score);
                $existing->setDate(new \DateTime());
            } else {
                $existing = new ResultatTest();
                $existing->setUser($user);
                $existing->setTest($test);
                $existing->setScore($score);
                $existing->setDate(new \DateTime());
                $em->persist($existing);
            }

            $em->flush();
            $this->addFlash('success', 'Résultat enregistré.');
            return $this->redirectToRoute('front_quiz_result', ['id' => $test->getId()]);
        }


        return $this->render('front/quiz/run.html.twig', [
            'test' => $test,
            'existingResult' => $existing,
        ]);
    }

    #[Route('/{id}/resultat', name: 'result', requirements: ['id' => '\\d+'])]
    #[IsGranted('ROLE_USER')]
    public function result(int $id, TestRepository $testRepo, ResultatTestRepository $resultRepo): Response
    {
        $test = $testRepo->find($id);
        if (!$test) {
            throw $this->createNotFoundException('Test introuvable.');
        }

        $user = $this->getUser();
        $result = $resultRepo->findOneBy(['user' => $user, 'test' => $test]);

        return $this->render('front/quiz/result.html.twig', [
            'test' => $test,
            'result' => $result,
        ]);
    }
}

