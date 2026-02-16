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
		
		// Генерируем общее количество отзывов (например, от 20 до 100)
		$totalReviews = 50 + (intval($orgId) % 51); // 50-100 отзывов
		
		// Рейтинг от 3.5 до 5.0
		$rating = 3.5 + ((intval($orgId) % 15) / 10);
		
		return [
			'rating' => round($rating, 1),
			'total_reviews' => $totalReviews,
			'reviews' => $this->generateReviews($orgId, $totalReviews)
		];
	}
    
    private function extractOrgId(string $url): string
    {
        // Пытаемся найти ID в разных форматах URL
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
        
        // Если не нашли, используем хеш URL
        return (string) crc32($url);
    }
    
    private function generateRating(string $orgId): float
    {
        // Генерируем рейтинг на основе ID (чтобы разные ссылки давали разные данные)
        $seed = intval($orgId) % 100;
        return round(3.5 + ($seed / 100) * 1.5, 1);
    }
    
    private function generateTotalReviews(string $orgId): int
    {
        $seed = intval($orgId) % 100;
        return 50 + $seed * 2;
    }
    
	private function generateReviews(string $orgId, int $totalReviews): array
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
		// Генерируем столько отзывов, сколько в totalReviews
		for ($i = 0; $i < $totalReviews; $i++) {
			$rating = 3 + (rand(0, 20) / 10); // 3.0 - 5.0
			$reviews[] = [
				'author' => $names[array_rand($names)],
				'date' => date('Y-m-d', strtotime('-' . rand(1, 60) . ' days')),
				'rating' => round($rating, 1),
				'text' => $texts[array_rand($texts)]
			];
		}
		
		// Сортируем по дате (новые сверху)
		usort($reviews, function($a, $b) {
			return strtotime($b['date']) - strtotime($a['date']);
		});
		
		return $reviews;
	}
}