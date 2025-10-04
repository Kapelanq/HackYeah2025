<?php
namespace App\Controller;

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
    #[Route('/map', name: 'app_map')]
    public function __invoke(): Response
    {
        $map = (new Map('default'))
            ->center(new Point(49.985686, 20.056641))
            ->zoom(6)

            ->addMarker(new Marker(
                position: new Point(49.985686, 20.056641),
                title: 'Lyon',
                infoWindow: new InfoWindow(
                    content: '<p>Thank you <a href="https://github.com/Kocal">@Kocal</a> for this component!</p>',
                )
            ))

            ->options((new LeafletOptions())
                ->tileLayer(new TileLayer(
                    url: 'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
                    attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
                    options: ['maxZoom' => 19]
                ))
            );

        return new JsonResponse([
            'map' => $map,
        ]);
    }
}
