<template>
  <div id="app">
    <!-- Шапка с навигацией - показываем только если авторизован -->
    <header v-if="isAuthenticated" class="header">
      <div class="container">
        <nav class="nav">
          <div class="nav-links">
            <router-link 
              to="/settings" 
              class="nav-link" 
              :class="{ active: $route.path === '/settings' }"
            >
              ⚙️ Настройки
            </router-link>
            <router-link 
              to="/reviews" 
              class="nav-link" 
              :class="{ active: $route.path === '/reviews' }"
            >
              ⭐ Отзывы
            </router-link>
          </div>
          <button @click="logout" class="logout-button">
            🚪 Выйти
          </button>
        </nav>
      </div>
    </header>

    <!-- Основной контент -->
    <main class="main-content">
      <div class="container">
        <router-view />
      </div>
    </main>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      isAuthenticated: false
    }
  },
  mounted() {
    this.checkAuth();
    // Следим за изменениями маршрута
    this.$router.afterEach(() => {
      this.checkAuth();
    });
  },
  methods: {
    checkAuth() {
      this.isAuthenticated = !!localStorage.getItem('token');
    },
    async logout() {
      try {
        await axios.post('/api/logout');
      } catch (error) {
        console.error('Ошибка при выходе:', error);
      } finally {
        localStorage.removeItem('token');
        this.isAuthenticated = false;
        this.$router.push('/login');
      }
    }
  }
}
</script>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  min-height: 100vh;
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 20px;
}

.header {
  background: rgba(255, 255, 255, 0.95);
  backdrop-filter: blur(10px);
  box-shadow: 0 2px 20px rgba(0,0,0,0.1);
  position: sticky;
  top: 0;
  z-index: 100;
}

.nav {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 0;
}

.nav-links {
  display: flex;
  gap: 1rem;
}

.nav-link {
  text-decoration: none;
  color: #666;
  padding: 0.5rem 1.5rem;
  border-radius: 25px;
  font-weight: 500;
  transition: all 0.3s;
  border: 1px solid transparent;
}

.nav-link:hover {
  background: #667eea;
  color: white;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.nav-link.active {
  background: #667eea;
  color: white;
  box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
}

.logout-button {
  background: #ff6b6b;
  color: white;
  border: none;
  padding: 0.5rem 2rem;
  border-radius: 25px;
  font-size: 1rem;
  cursor: pointer;
  transition: all 0.3s;
  border: 1px solid transparent;
  font-weight: 500;
}

.logout-button:hover {
  background: #ff5252;
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(255, 107, 107, 0.3);
}

.main-content {
  padding: 2rem 0;
}

@media (max-width: 768px) {
  .nav {
    flex-direction: column;
    gap: 1rem;
    padding: 1rem;
  }
  
  .nav-links {
    width: 100%;
    justify-content: center;
  }
  
  .logout-button {
    width: 100%;
  }
}
</style>