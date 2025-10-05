<?php
namespace App\Controller;

use App\Entity\Reports;
use App\Entity\Users;
use App\Repository\ReportsCountRepository;
use App\Repository\ReportsRepository;
use App\Repository\StopTimesRepository;
use App\Repository\TicketsRepository;
use App\Repository\TrainsRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        protected TrainsRepository $trainsRepository,
        protected TicketsRepository $ticketsRepository,
        protected UsersRepository $usersRepository,
        protected EntityManagerInterface $entityManager,
        protected ReportsCountRepository $reportsCountRepository
    ){

    }

    #[Route('/map', name: 'app_map')]
    public function getMap(Request $request): Response
    {
        $tripId = $request->query->get('tripId');

        $stops = $this->stopTimesRepository->findStopTimesByTripId($tripId);
        $reports = $this->reportsRepository->findReportsByTripId($tripId);
        $train = $this->trainsRepository->getOneByTripId($tripId);

        $map = (new Map('default'))
            ->center(new Point($stops[0]['stopLat'], $stops[0]['stopLon']))
            ->zoom(6);

        foreach ($stops as $stop) {
            $map->addMarker(new Marker(
                position: new Point($stop['stopLat'], $stop['stopLon']),
                title: $stop['stopName'],
                infoWindow: new InfoWindow(
                    headerContent: "{$stop['stopName']}",
                    content: "Czas przyjazdu: {$stop[0]->getArrivalTime()->format('H:m:s')} | Czas odjazdu: {$stop[0]->getDepartureTime()->format('H:m:s')}"
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
            'reports' => $reports,
            'train' => $train->toArray()
        ], 200);
    }

    #[Route('/report', name: 'app_report')]
    public function reportProblem(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $this->reportsRepository->addReport($data);

        if ($data['type'] == Reports::TYPE_TRAIN_DELAY) {

            $stops = $this->stopTimesRepository->findAloneStopTimesByTripId($data['tripId']);
            foreach ($stops as $stop) {
                $newArrivalTime = (clone $stop->getArrivalTime()->modify("+{$data['delayMinutes']} minutes"));
                $newDepartureTime = (clone $stop->getDepartureTime()->modify("+{$data['delayMinutes']} minutes"));
                $stop->setArrivalTime($newArrivalTime);
                $stop->setDepartureTime($newDepartureTime);
                $this->entityManager->flush();
            }

        }

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route('/delete-report', name: 'app_delete_report')]
    public function deleteReport(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $reportId = $request->query->get('reportId');

        $user = $this->usersRepository->findOneBy(['id' => $userId]);
        $report = $this->reportsRepository->findReportById($reportId);

        if ($user->getPrivileges() == Users::ROLE_ADMIN) {
            $this->reportsRepository->deleteReport($report);
            return new Response('Report deleted', Response::HTTP_OK);
        }

        if ($user->getId() == $report->getUserId()) {
            $this->reportsRepository->deleteReport($report);
            return new Response('Report deleted', Response::HTTP_OK);
        }

        return new Response('No privileges to do this action', Response::HTTP_FORBIDDEN);
    }

    #[Route('/update-report', name: 'app_update_report')]
    public function updateReport(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $report = $this->reportsRepository->findReportById($data['reportId']);

        $this->reportsRepository->updateReport($report, $data);

        return new Response('Report updated', Response::HTTP_OK);

    }

    #[Route('/disprove-report', name: 'app_disprove_report')]
    public function disproveReport(Request $request): Response
    {
        $reportId = $request->query->get('reportId');
        $report = $this->reportsRepository->findReportById($reportId);
        $this->reportsRepository->disproveReport($report);

        return new JsonResponse('Problem disproved', Response::HTTP_ACCEPTED);
    }

    #[Route('/confirm-report', name: 'app_confirm_report')]
    public function confirmReport(Request $request): Response
    {
        $reportId = $request->query->get('reportId');
        $report = $this->reportsRepository->findReportById($reportId);
        $this->reportsRepository->confirmReport($report);

        return new JsonResponse('Problem confirmed', Response::HTTP_ACCEPTED);
    }

    #[Route('/ticket', name: 'app_ticket')]
    public function getTripTicket(Request $request): Response
    {
        $userId = $request->query->get('userId');
        $ticketId = $request->query->get('ticketId');


        $ticket = $this->ticketsRepository->findOneBy(['userId' => $userId, 'ticketId' => $ticketId]);

        return new JsonResponse($ticket->toArray(), Response::HTTP_ACCEPTED);
    }

    #[Route('/report-get-counts', name: 'app_report_get_counts')]
    public function getReportCounts(Request $request): Response
    {
        $reportId = $request->query->get('reportId');
        $report = $this->reportsRepository->findReportById($reportId);

        $count = $this->reportsCountRepository->groupGetCount($report);
        $transformed = [];
        foreach ($count as $item) {
            $transformed[$item['isGood']] = $item['1'];
        }

        return new JsonResponse($transformed, Response::HTTP_ACCEPTED);
    }

    #[Route('/analyze-trip', name: 'app_analyze_trip')]
    public function analyzeTrip(Request $request): Response
    {
        $tripId = $request->query->get('tripId');
        $data = $this->reportsRepository->groupReportsByTypeLastWeek($tripId);

        $message = "Na podstawie ostatnich {$data[0]['count']} zgłoszeń z ostatniego tygodnia
        można stwierdzić, że potencjalne opóźnienia na trasie mogą wynosić około: " . round($data[0]['avgDelay'], 0, PHP_ROUND_HALF_UP) . " minut";

        return new JsonResponse([
            'data' => $data[0],
            'message' => $message
        ], Response::HTTP_ACCEPTED);
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);



    }
}
