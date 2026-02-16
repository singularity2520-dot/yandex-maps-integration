<template>
  <div class="settings-container">
    <div class="settings-card">
      <h1 class="page-title">⚙️ Настройки интеграции</h1>
      
      <form @submit.prevent="saveSettings" class="settings-form">
        <div class="form-group">
          <label for="yandexMapsUrl" class="form-label">
            Ссылка на карточку организации в Яндекс Картах
          </label>
          <input 
            id="yandexMapsUrl"
            v-model="yandexMapsUrl" 
            type="url" 
            class="form-input"
            placeholder="https://yandex.ru/maps/org/..."
            required
          />
          <p class="form-hint">
            Пример: https://yandex.ru/maps/org/123456789
          </p>
        </div>
        
        <button type="submit" class="submit-button" :disabled="saving">
          {{ saving ? 'Сохранение...' : '💾 Сохранить ссылку' }}
        </button>
      </form>

      <!-- Успешное сохранение -->
      <div v-if="saved" class="success-message">
        <p>✅ Настройки успешно сохранены!</p>
        <router-link to="/reviews" class="reviews-link">
          Перейти к просмотру отзывов →
        </router-link>
      </div>

      <!-- Показываем текущую ссылку, если она уже сохранена -->
      <div v-if="existingUrl && !saved" class="current-settings">
        <h3>Текущая ссылка:</h3>
        <p class="current-url">{{ existingUrl }}</p>
        <router-link to="/reviews" class="view-reviews-btn">
          👀 Посмотреть отзывы
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      yandexMapsUrl: '',
      existingUrl: null,
      saved: false,
      saving: false
    }
  },
  async mounted() {
    await this.loadSettings();
  },
  methods: {
    async loadSettings() {
      try {
        const response = await axios.get('/api/settings');
        if (response.data.yandex_maps_url) {
          this.existingUrl = response.data.yandex_maps_url;
          this.yandexMapsUrl = response.data.yandex_maps_url;
        }
      } catch (error) {
        console.log('Нет сохранённых настроек');
      }
    },
    async saveSettings() {
      this.saving = true;
      try {
        await axios.post('/api/settings', {
          yandex_maps_url: this.yandexMapsUrl
        });
        this.saved = true;
        this.existingUrl = this.yandexMapsUrl;
      } catch (error) {
        alert('Ошибка при сохранении: ' + (error.response?.data?.message || 'Неизвестная ошибка'));
      } finally {
        this.saving = false;
      }
    }
  }
}
</script>

<style scoped>
.settings-container {
  max-width: 600px;
  margin: 0 auto;
  padding: 20px;
}

.settings-card {
  background: white;
  border-radius: 30px;
  padding: 40px;
  box-shadow: 0 20px 60px rgba(0,0,0,0.1);
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.2);
}

.page-title {
  font-size: 2rem;
  color: #333;
  margin-bottom: 30px;
  text-align: center;
  font-weight: 600;
}

.settings-form {
  margin-bottom: 30px;
}

.form-group {
  margin-bottom: 25px;
}

.form-label {
  display: block;
  margin-bottom: 10px;
  font-weight: 500;
  color: #555;
  font-size: 1.1rem;
}

.form-input {
  width: 100%;
  padding: 15px 20px;
  border: 2px solid #e0e0e0;
  border-radius: 15px;
  font-size: 1rem;
  transition: all 0.3s;
  background: #f9f9f9;
}

.form-input:focus {
  outline: none;
  border-color: #667eea;
  background: white;
  box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
}

.form-hint {
  margin-top: 8px;
  font-size: 0.9rem;
  color: #999;
  padding-left: 5px;
}

.submit-button {
  width: 100%;
  padding: 15px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  border: none;
  border-radius: 15px;
  font-size: 1.1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
}

.submit-button:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
}

.submit-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.success-message {
  text-align: center;
  padding: 20px;
  background: #d4edda;
  border-radius: 15px;
  color: #155724;
  margin-top: 20px;
}

.reviews-link {
  display: inline-block;
  margin-top: 15px;
  padding: 12px 30px;
  background: #28a745;
  color: white;
  text-decoration: none;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s;
}

.reviews-link:hover {
  background: #218838;
  transform: translateX(5px);
}

.current-settings {
  text-align: center;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 15px;
  border: 2px dashed #dee2e6;
}

.current-settings h3 {
  color: #666;
  margin-bottom: 10px;
}

.current-url {
  background: white;
  padding: 10px;
  border-radius: 10px;
  word-break: break-all;
  color: #667eea;
  font-family: monospace;
  margin: 15px 0;
}

.view-reviews-btn {
  display: inline-block;
  padding: 10px 25px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  text-decoration: none;
  border-radius: 25px;
  transition: all 0.3s;
}

.view-reviews-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
}
</style>