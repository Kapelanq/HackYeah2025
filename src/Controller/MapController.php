<?php
namespace App\Controller;

use App\Repository\StopTimesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Map\Bridge\Leaflet\LeafletOptions;
use Symfony\UX\Map\Bridge\Leaflet\Option\TileLayer;
use Symfony\UX\Map\InfoWindow;
use Symfony\UX\Map\Map;
use Symfony\UX\Map\Marker;
use Symfony\UX\Map\Point;

class MapController extends AbstractController
{
    public function __construct(
        public StopTimesRepository $repository,
    ){

    }
    #[Route('/map', name: 'app_map')]
    public function getMap(): Response
    {
        $stops = $this->repository->findStopTimesByTripId('2024_2025_1285959');

        $map = (new Map('default'))
            ->center(new Point($stops[0]['stopLat'], $stops[0]['stopLon']))
            ->zoom(6);

        foreach ($stops as $stop) {
            $map->addMarker(new Marker(
                position: new Point($stop['stopLat'], $stop['stopLon']),
                title: $stop['stopName'],
                infoWindow: new InfoWindow(
                    content: "{$stop['stopName']}"
                )
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
            'map' => $map->toArray()
        ]);
    }
}
