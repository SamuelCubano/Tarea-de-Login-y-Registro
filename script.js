
  // CARGAR PREFERENCIAS AL INICIAR
  const savedTheme = localStorage.getItem('theme');
  const savedLang = localStorage.getItem('lang');

  if (savedTheme) {
    document.body.classList.add(savedTheme);
    if (savedTheme === 'dark-mode') {
      document.getElementById('theme-toggle').textContent = '☀️';
    } else {
      document.getElementById('theme-toggle').textContent = '🌙';
    }
  } else {
    document.body.classList.add('light-mode');
  }

  if (savedLang) {
    document.querySelectorAll('[data-es]').forEach(el => {
      el.textContent = el.dataset[savedLang];
    });
    document.getElementById('lang-toggle').dataset.lang = savedLang;
    if (savedLang === 'es') {
      document.getElementById('lang-toggle').textContent = '🇺🇸';
    } else {
      document.getElementById('lang-toggle').textContent = '🇪🇸';
    }
  }

  // 1. Funcionalidad del carrusel
  const carruseles = document.querySelectorAll('.carrusel');
  carruseles.forEach(carrusel => {
    const imagenes = carrusel.querySelector('.imagenes-carrusel');
    const prevButton = carrusel.querySelector('.prev-button');
    const nextButton = carrusel.querySelector('.next-button');
    const numImagenes = imagenes.children.length;
    let indiceActual = 0;

    function moverCarrusel() {
      imagenes.style.transform = `translateX(${-indiceActual * 100}%)`;
    }

    nextButton.addEventListener('click', () => {
      indiceActual++;
      if (indiceActual >= numImagenes) {
        indiceActual = 0;
      }
      moverCarrusel();
    });

    prevButton.addEventListener('click', () => {
      indiceActual--;
      if (indiceActual < 0) {
        indiceActual = numImagenes - 1;
      }
      moverCarrusel();
    });

    setInterval(() => {
      indiceActual++;
      if (indiceActual >= numImagenes) {
        indiceActual = 0;
      }
      moverCarrusel();
    }, 10000);
  });

  // 2. Modo Oscuro / Claro
  const themeToggle = document.getElementById('theme-toggle');
  const body = document.body;

  themeToggle.addEventListener('click', () => {
    body.classList.toggle('dark-mode');
    body.classList.toggle('light-mode');

    const currentTheme = body.classList.contains('dark-mode') ? 'dark-mode' : 'light-mode';
    localStorage.setItem('theme', currentTheme);

    if (currentTheme === 'dark-mode') {
      themeToggle.textContent = '☀️';
    } else {
      themeToggle.textContent = '🌙';
    }
  });

  // 3. Cambio de Idioma
  const langToggle = document.getElementById('lang-toggle');
  const elementosTraducibles = document.querySelectorAll('[data-es]');

  langToggle.addEventListener('click', () => {
    const currentLang = langToggle.dataset.lang;
    const newLang = currentLang === 'en' ? 'es' : 'en';

    elementosTraducibles.forEach(el => {
      el.textContent = el.dataset[newLang];
    });

    if (newLang === 'es') {
      langToggle.textContent = '🇺🇸';
    } else {
      langToggle.textContent = '🇪🇸';
    }
    langToggle.dataset.lang = newLang;
    localStorage.setItem('lang', newLang);
  });

  // 4. CÓDIGO PARA EL FORMULARIO DE CONTACTO
  const form = document.getElementById('contact-form');
  const nameInput = document.getElementById('nombre');

  if (nameInput) {
    nameInput.addEventListener('input', (event) => {
      event.target.value = event.target.value.replace(/\d/g, '');
    });
  }

  const nameError = document.getElementById('nombre-error');
  const emailInput = document.getElementById('email');
  const emailError = document.getElementById('email-error');
  const successMessage = document.getElementById('form-success');
  if (form) {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      let isValid = true;
      if (nameError) nameError.textContent = '';
      if (emailError) emailError.textContent = '';
      if (successMessage) successMessage.textContent = '';

      if (/\d/.test(nameInput.value)) {
        if (nameError) nameError.textContent = 'El nombre no puede contener números.';
        isValid = false;
      }


      if (isValid) {
        if (successMessage) successMessage.textContent = '¡Gracias por suscribirte!';
        form.reset();
      }
    });
  }



      // Scroll to top button visibility
const scrollToTopBtn = document.getElementById('scroll-to-top');

scroll.on('scroll', (obj) => {
    const scrollThreshold = 300;
    if (obj.scroll.y > scrollThreshold) {
        scrollToTopBtn.classList.add('show');
    } else {
        scrollToTopBtn.classList.remove('show');
    }
});