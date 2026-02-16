<template>
  <div class="reviews-container">
    <div class="header">
      <h1>Отзывы о компании</h1>
    </div>
    
    <!-- Блок с рейтингом -->
    <div class="rating-card" v-if="totalReviews > 0">
      <div class="rating-main">
        <span class="rating-value">{{ rating }}</span>
        <div class="rating-stars">
          <span v-for="star in 5" :key="star" 
                class="star" 
                :class="{ filled: star <= Math.round(rating) }">
            ★
          </span>
        </div>
        <span class="total-reviews">{{ totalReviews }} отзывов</span>
      </div>
    </div>

    <!-- Список отзывов -->
    <div v-if="reviews.length > 0" class="reviews-list">
      <div v-for="(review, index) in reviews" :key="index" class="review-card">
        <div class="review-header">
          <div class="reviewer-info">
            <span class="reviewer-name">{{ review.author }}</span>
            <span class="review-date">{{ formatDate(review.date) }}</span>
          </div>
          <div class="review-stars">
            <span v-for="star in 5" :key="star" 
                  class="star small" 
                  :class="{ filled: star <= review.rating }">
              ★
            </span>
          </div>
        </div>
        <p class="review-text">{{ review.text }}</p>
      </div>
    </div>

    <!-- Заглушка, если нет отзывов -->
    <div v-else class="empty-state">
      <p>Пока нет отзывов</p>
    </div>
  </div>

</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      rating: 0,
      totalReviews: 0,
      reviews: []
    }
  },
  async mounted() {
    await this.loadReviews();
  },
  methods: {
    async loadReviews() {
      try {
        const response = await axios.get('/api/reviews');
        this.rating = response.data.rating || 0;
        this.totalReviews = response.data.total_reviews || 0;
        this.reviews = response.data.reviews || [];
      } catch (error) {
        console.error('Ошибка загрузки отзывов:', error);
        this.reviews = [];
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { day: 'numeric', month: 'long', year: 'numeric' };
      return new Date(dateString).toLocaleDateString('ru-RU', options);
    }
  }
}
</script>

<style scoped>
.reviews-container {
  max-width: 800px;
  margin: 0 auto;
  padding: 2rem;
}

.header {
  text-align: center;
  margin-bottom: 2rem;
}

.header h1 {
  color: #333;
  font-size: 2.5rem;
  margin: 0;
}

/* Карточка рейтинга */
.rating-card {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 20px;
  padding: 2rem;
  margin-bottom: 2rem;
  color: white;
  box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.rating-main {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.5rem;
}

.rating-value {
  font-size: 4rem;
  font-weight: bold;
  line-height: 1;
}

.rating-stars {
  display: flex;
  gap: 0.5rem;
  font-size: 2rem;
}

.star {
  color: rgba(255, 255, 255, 0.3);
  transition: color 0.3s;
}

.star.filled {
  color: #ffd700;
}

.star.small {
  font-size: 1.2rem;
}

.total-reviews {
  font-size: 1.1rem;
  opacity: 0.9;
}

/* Список отзывов */
.reviews-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.review-card {
  background: white;
  border-radius: 15px;
  padding: 1.5rem;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
  transition: transform 0.3s, box-shadow 0.3s;
  border: 1px solid #f0f0f0;
}

.review-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.review-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  padding-bottom: 0.5rem;
  border-bottom: 1px solid #f0f0f0;
}

.reviewer-info {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.reviewer-name {
  font-weight: bold;
  color: #333;
  font-size: 1.1rem;
}

.review-date {
  font-size: 0.9rem;
  color: #999;
}

.review-stars {
  display: flex;
  gap: 0.25rem;
}

.review-text {
  color: #666;
  line-height: 1.6;
  margin: 0;
  font-size: 1rem;
}

/* Пустое состояние */
.empty-state {
  text-align: center;
  padding: 3rem;
  background: #f9f9f9;
  border-radius: 15px;
  color: #999;
  font-size: 1.2rem;
}

@media (max-width: 600px) {
  .reviews-container {
    padding: 1rem;
  }
  
  .header h1 {
    font-size: 2rem;
  }
  
  .rating-value {
    font-size: 3rem;
  }
  
  .rating-stars {
    font-size: 1.5rem;
  }
  
  .review-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 0.5rem;
  }
}
</style>