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
      $currentMonthCategoriesPlannedVsReal = $logRepository->currentMonthCategoriesPlannedVsReal();
      $currentMonthCategoriesCostsPercentage = $logRepository->currentMonthCategoriesCostsPercentage();
      $currentMonthItemsPerCategoryPlannedVsReal = $logRepository->currentMonthItemsPerCategoryPlannedVsReal();
      $currentYearIncomeVsCosts = $logRepository->currentYearIncomeVsCosts();
      $currentYearCategoriesCosts = $logRepository->currentYearCategoriesCosts();
     
      return $this->render('dashboard/index.html.twig', [
          'currentMonthCategoriesPlannedVsReal' => $currentMonthCategoriesPlannedVsReal,
          'currentMonthCategoriesCostsPercentage' => $currentMonthCategoriesCostsPercentage,
          'currentMonthItemsPerCategoryPlannedVsReal' => $currentMonthItemsPerCategoryPlannedVsReal,
          'currentYearIncomeVsCosts' => $currentYearIncomeVsCosts,
          'currentYearCategoriesCosts' => $currentYearCategoriesCosts,
          // 'globalTotalPerMonth' => $globalTotalPerMonth
      ]);
    }
}
