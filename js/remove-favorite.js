      // Обработчик для кнопки удаления из избранного
      document.querySelectorAll('.btn-remove-favorite').forEach(button => {
         button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            const favoriteItem = this.closest('.favorite-item');

            fetch('/api/favorites.php', {
                  method: 'POST',
                  headers: {
                     'Content-Type': 'application/json',
                  },
                  body: JSON.stringify({
                     action: 'remove',
                     productId: productId
                  })
               })
               .then(response => response.json())
               .then(data => {
                  if (data.success) {
                     // Удаляем элемент из DOM
                     favoriteItem.remove();

                     // Проверяем, остались ли еще товары
                     const remainingItems = document.querySelectorAll('.favorite-item');
                     if (remainingItems.length === 0) {
                        location.reload(); // Перезагружаем страницу для показа пустого состояния
                     }

                     showNotification(data.message);

                     // Обновляем счетчик в header
                     if (typeof updateFavoritesCount === 'function') {
                        updateFavoritesCount();
                     }
                  } else {
                     showNotification(data.message, 'error');
                  }
               })
               .catch(error => {
                  console.error('Ошибка:', error);
                  showNotification('Произошла ошибка при удалении товара', 'error');
               });
         });
      });