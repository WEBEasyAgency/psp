/**
 * Усовершенствованный FAQ аккордеон с дополнительной функциональностью
 *
 * Функции:
 * - Закрытие других элементов при открытии одного
 * - Отслеживание аналитики (клики на FAQ)
 * - Управление состоянием через JavaScript
 * - Дополнительные анимации
 *
 * Использование:
 * 1. Импортируйте этот файл перед </body>:
 *    <script src="/faq-enhanced.js"></script>
 * 2. Используйте класс FaqAccordion:
 *    const faq = new FaqAccordion('.product-faq', { singleOpen: true });
 */

class FaqAccordion {
  constructor(selector = '.product-faq', options = {}) {
    this.container = document.querySelector(selector);
    this.options = {
      singleOpen: options.singleOpen !== false, // По умолчанию true
      animationDuration: options.animationDuration || 300,
      onToggle: options.onToggle || null,
      trackAnalytics: options.trackAnalytics !== false, // По умолчанию true
    };

    this.init();
  }

  init() {
    if (!this.container) {
      console.warn('FAQ контейнер не найден');
      return;
    }

    this.details = Array.from(this.container.querySelectorAll('details'));
    this.setupEventListeners();
  }

  setupEventListeners() {
    this.details.forEach((detail, index) => {
      detail.addEventListener('toggle', (e) => {
        this.handleToggle(e, index);
      });

      // Для отслеживания аналитики
      if (this.options.trackAnalytics) {
        detail.addEventListener('click', (e) => {
          if (e.target.closest('summary')) {
            this.trackAnalytic(index, detail.open);
          }
        });
      }
    });
  }

  handleToggle(event, index) {
    const detail = event.target;

    if (this.options.singleOpen && detail.open) {
      // Закрыть все остальные детали
      this.details.forEach((d, i) => {
        if (i !== index && d.open) {
          d.open = false;
        }
      });
    }

    // Callback функция
    if (this.options.onToggle) {
      this.options.onToggle({
        index,
        isOpen: detail.open,
        question: detail.querySelector('summary')?.textContent.trim(),
      });
    }
  }

  trackAnalytic(index, isOpen) {
    const data = {
      event: 'faq_toggle',
      faq_index: index,
      faq_state: isOpen ? 'opened' : 'closed',
      timestamp: new Date().toISOString(),
    };

    // Отправить в аналитику (если используется Gtag, Yandex.Metrica и т.д.)
    if (window.gtag) {
      window.gtag('event', 'faq_toggle', {
        faq_index: index,
        faq_state: isOpen ? 'opened' : 'closed',
      });
    }

    console.log('FAQ Analytics:', data);
  }

  // Методы для управления состоянием

  openAll() {
    this.details.forEach(d => d.open = true);
  }

  closeAll() {
    this.details.forEach(d => d.open = false);
  }

  toggle(index) {
    if (this.details[index]) {
      this.details[index].open = !this.details[index].open;
    }
  }

  open(index) {
    if (this.details[index]) {
      this.details[index].open = true;
    }
  }

  close(index) {
    if (this.details[index]) {
      this.details[index].open = false;
    }
  }

  getState() {
    return this.details.map((d, i) => ({
      index: i,
      isOpen: d.open,
      question: d.querySelector('summary')?.textContent.trim(),
    }));
  }
}

// Export для использования в модулях
if (typeof module !== 'undefined' && module.exports) {
  module.exports = FaqAccordion;
}

// Автоинициализация при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
  // Раскомментируйте для автоинициализации
  // new FaqAccordion('.product-faq', { singleOpen: true });
});
