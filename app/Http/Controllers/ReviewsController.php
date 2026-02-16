<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\YandexMapsService;

class ReviewsController extends Controller
{
    protected $yandexMapsService;

    public function __construct(YandexMapsService $yandexMapsService)
    {
        $this->yandexMapsService = $yandexMapsService;
    }

    public function index(Request $request)
    {
        $settings = $request->user()->settings;
        
        if (!$settings || !$settings->yandex_maps_url) {
            return response()->json([
                'error' => 'Ссылка на Яндекс Карты не настроена'
            ], 400);
        }

        try {
            $reviews = $this->yandexMapsService->getReviews($settings->yandex_maps_url);
            return response()->json($reviews);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Ошибка получения отзывов: ' . $e->getMessage()
            ], 500);
        }
    }
}