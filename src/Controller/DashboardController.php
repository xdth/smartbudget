<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Log;
use App\Repository\LogRepository;

class DashboardController extends AbstractController
{
    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function index(LogRepository $logRepository): Response
    {
      $logRepository = $this->getDoctrine()->getRepository(Log::class);

      // $globalTotalPerMonth = $logRepository->globalTotalPerMonth('credit');
      $graph1_currentMonthCategoriesPlannedVsReal = $logRepository->graph1_currentMonthCategoriesPlannedVsReal();
     
      return $this->render('dashboard/index.html.twig', [
          'graph1_currentMonthCategoriesPlannedVsReal' => $graph1_currentMonthCategoriesPlannedVsReal,
          // 'globalTotalPerMonth' => $globalTotalPerMonth
      ]);
    }
}
