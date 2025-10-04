<?php
namespace App\Controller;

use App\Entity\Reports;
use App\Repository\ReportsRepository;
use App\Repository\StopTimesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;

class MapController extends AbstractController
{
    public function __construct(
        protected StopTimesRepository $stopTimesRepository,
        protected ReportsRepository $reportsRepository,
    ){

    }
    #[Route('/map', name: 'app_map')]
    public function getMap(Request $request): Response
    {
        $tripId = $request->query->get('tripId');

        $stops = $this->stopTimesRepository->findStopTimesByTripId($tripId);

        $reports = $this->reportsRepository->findReportsByTripId($tripId);

        $map = (new Map('default'))
            ->center(new Point($stops[0]['stopLat'], $stops[0]['stopLon']))
            ->zoom(6);

        foreach ($stops as $stop) {
            $map->addMarker(new Marker(
                position: new Point($stop['stopLat'], $stop['stopLon']),
                title: $stop['stopName'],
                infoWindow: new InfoWindow(
                    content: "{$stop['stopName']}"
                ),
                id: $stop[0]->getStopId(),
            ));
        }
//            ->options((new LeafletOptions())
//                ->tileLayer(new TileLayer(
//                    url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
//                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
//                    options: ['maxZoom' => 19]
//                ))
//            );

        return new JsonResponse([
            'map' => $map->toArray(),
            'reports' => $reports
        ], 200);
    }

    #[Route('/report-problem', name: 'app_report_problem')]
    public function reportProblem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->reportsRepository->addReport($data);

        return new JsonResponse([], Response::HTTP_CREATED);
    }

    public function confirmReport(Request $request): Response
    {
        $reportId = $request->query->get('reportId');
        $report = $this->reportsRepository->findReportById($reportId);

        $this->reportsRepository->confirmReport();

    }
}
