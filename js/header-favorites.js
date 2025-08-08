// Функция для обновления счетчика избранного в header
function updateFavoritesCount() {
   fetch('/api/favorites.php')
      .then(response => response.json())
      .then(data => {
         const favoritesCount = data.favorites ? data.favorites.length : 0;
         const countElement = document.querySelector('.favorites-count');
         
         if (favoritesCount > 0) {
            if (countElement) {
               countElement.textContent = favoritesCount;
            } else {
               // Создаем элемент счетчика, если его нет
               const favoritesLink = document.querySelector('.favorites a');
               if (favoritesLink) {
                  const newCountElement = document.createElement('span');
                  newCountElement.className = 'favorites-count';
                  newCountElement.textContent = favoritesCount;
                  favoritesLink.appendChild(newCountElement);
               }
            }
         } else {
            // Удаляем счетчик, если избранное пустое
            if (countElement) {
               countElement.remove();
            }
         }
      })
      .catch(error => {
      });
}

// Обновляем счетчик при загрузке страницы
document.addEventListener('DOMContentLoaded', function() {
   updateFavoritesCount();
});

// Экспортируем функцию для использования в других скриптах
window.updateFavoritesCount = updateFavoritesCount; 


