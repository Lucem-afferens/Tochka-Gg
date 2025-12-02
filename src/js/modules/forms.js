/**
 * Forms Module
 * 
 * Обработка форм, валидация
 */

export function initForms() {
  const forms = document.querySelectorAll('form[data-tgg-form]');
  
  forms.forEach(form => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      const formData = new FormData(form);
      const submitButton = form.querySelector('button[type="submit"]');
      const originalText = submitButton?.textContent;
      
      // Валидация
      if (!validateForm(form)) {
        return;
      }
      
      // Показываем загрузку
      if (submitButton) {
        submitButton.disabled = true;
        submitButton.textContent = 'Отправка...';
      }
      
      try {
        // Отправка через AJAX (если настроено)
        const response = await fetch(form.action || window.location.href, {
          method: 'POST',
          body: formData
        });
        
        if (response.ok) {
          showFormMessage(form, 'Спасибо! Ваше сообщение отправлено.', 'success');
          form.reset();
        } else {
          throw new Error('Ошибка отправки');
        }
      } catch (error) {
        showFormMessage(form, 'Ошибка отправки. Попробуйте позже.', 'error');
      } finally {
        if (submitButton) {
          submitButton.disabled = false;
          submitButton.textContent = originalText;
        }
      }
    });
  });
}

function validateForm(form) {
  const requiredFields = form.querySelectorAll('[required]');
  let isValid = true;
  
  requiredFields.forEach(field => {
    if (!field.value.trim()) {
      isValid = false;
      field.classList.add('error');
      
      field.addEventListener('input', () => {
        field.classList.remove('error');
      }, { once: true });
    }
  });
  
  // Email валидация
  const emailFields = form.querySelectorAll('input[type="email"]');
  emailFields.forEach(field => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (field.value && !emailRegex.test(field.value)) {
      isValid = false;
      field.classList.add('error');
    }
  });
  
  return isValid;
}

function showFormMessage(form, message, type) {
  const existingMessage = form.querySelector('.tgg-form-message');
  if (existingMessage) {
    existingMessage.remove();
  }
  
  const messageEl = document.createElement('div');
  messageEl.className = `tgg-form-message tgg-form-message--${type}`;
  messageEl.textContent = message;
  
  form.insertBefore(messageEl, form.firstChild);
  
  setTimeout(() => {
    messageEl.remove();
  }, 5000);
}


