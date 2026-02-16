<template>
  <div class="login-container">
    <h1>Вход в систему</h1>
    <form @submit.prevent="handleSubmit">
      <div class="form-group">
        <label for="email">Email:</label>
        <input 
          type="email" 
          id="email" 
          v-model="email" 
          required
          placeholder="test@example.com"
        >
      </div>
      
      <div class="form-group">
        <label for="password">Пароль:</label>
        <input 
          type="password" 
          id="password" 
          v-model="password" 
          required
          placeholder="password123"
        >
      </div>
      
      <button type="submit">Войти</button>
    </form>
    
    <p class="hint">
      Тестовые данные: test@example.com / password123
    </p>
  </div>
</template>

<script>
export default {
  name: 'LoginPage',
  data() {
    return {
      email: '',
      password: ''
    };
  },
	methods: {
	  async handleSubmit() {
		try {
		  // Отправляем данные на сервер
		  const response = await axios.post('/api/login', {
			email: this.email,
			password: this.password
		  });
		  
		  // Сохраняем токен
		  localStorage.setItem('token', response.data.token);
		  
		  // Перенаправляем на страницу настроек
		  this.$router.push('/settings');
		  
		} catch (error) {
		  // Показываем ошибку
		  alert('Ошибка входа: ' + (error.response?.data?.message || 'Неверный логин или пароль'));
		}
	  }
	}
};
</script>

<style scoped>
.login-container {
  max-width: 400px;
  margin: 50px auto;
  padding: 30px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.form-group {
  margin-bottom: 20px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 4px;
  font-size: 16px;
}

button {
  width: 100%;
  padding: 12px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
}

button:hover {
  background-color: #45a049;
}

.hint {
  margin-top: 20px;
  text-align: center;
  color: #666;
  font-size: 14px;
}
</style>