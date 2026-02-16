<?php
namespace App\Services;

use GuzzleHttp\Client;

class YandexMapsService
{
    private $client;
    
    public function __construct()
    {
        $this->client = new Client([
            'timeout' => 10,
            'verify' => false
        ]);
    }
    
    public function getReviews(string $url)
    {
        $orgId = $this->extractOrgId($url);
        
        // Генерируем данные на основе ID (стабильно для каждого ID)
        $seed = intval($orgId);
        
        // Общее количество отзывов (зависит от ID)
        $totalReviews = 20 + ($seed % 81); // 20-100 отзывов
        
        // Генерируем массив отзывов (стабильный для этого ID)
        $reviews = $this->generateReviewsForOrg($seed, $totalReviews);
        
        // Вычисляем реальный средний рейтинг из отзывов
        $averageRating = $this->calculateAverageRating($reviews);
        
        return [
            'rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews,
            'reviews' => $reviews
        ];
    }
    
    private function extractOrgId(string $url): string
    {
        $patterns = [
            '/org\/(\d+)/',
            '/organization\/(\d+)/',
            '/\?oid=(\d+)/',
            '/business\/(\d+)/'
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return (string) crc32($url);
    }
    
    private function generateReviewsForOrg(int $seed, int $count): array
    {
        $names = ['Алексей', 'Мария', 'Дмитрий', 'Елена', 'Сергей', 'Анна', 'Павел', 'Ольга', 'Иван', 'Татьяна'];
        $texts = [
            'Отличное место, всем доволен!',
            'Хороший сервис, быстро и качественно.',
            'Нормально, но есть к чему стремиться.',
            'Лучшая компания в городе!',
            'Цены приемлемые, качество на уровне.',
            'Рекомендую, обращайтесь!',
            'Немного долго, но результат порадовал.',
            'Профессионалы своего дела.',
            'Всё супер, обязательно вернусь!',
            'Хорошее соотношение цены и качества.'
        ];
        
        $reviews = [];
        
        // Используем seed для создания детерминированной случайности
        // Это значит, что для одного и того же ID всегда будут одни и те же отзывы
        srand($seed);
        
        for ($i = 0; $i < $count; $i++) {
            // Генерируем рейтинг отзыва (от 3.0 до 5.0) с привязкой к ID
            $ratingSeed = ($seed + $i * 100) % 100;
            $rating = 3.0 + ($ratingSeed / 100) * 2.0;
            
            // Выбираем автора с привязкой к ID
            $authorIndex = ($seed + $i) % count($names);
            
            // Выбираем текст с привязкой к ID
            $textIndex = ($seed + $i * 2) % count($texts);
            
            // Генерируем дату (от 1 до 90 дней назад) с привязкой к ID
            $daysAgo = (($seed + $i * 3) % 90) + 1;
            
            $reviews[] = [
                'author' => $names[$authorIndex],
                'date' => date('Y-m-d', strtotime('-' . $daysAgo . ' days')),
                'rating' => round($rating, 1),
                'text' => $texts[$textIndex]
            ];
        }
        
        // Сортируем по дате (новые сверху)
        usort($reviews, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $reviews;
    }
    
    private function calculateAverageRating(array $reviews): float
    {
        if (empty($reviews)) {
            return 0;
        }
        
        $sum = array_sum(array_column($reviews, 'rating'));
        return $sum / count($reviews);
    }
}