function openModal(id) {
  const modal = document.getElementById(id);
  if (modal) modal.style.display = 'flex';
}

function openSignInModal() { openModal('signin-modal'); }
function openRegisterModal() { openModal('register-modal'); }

function closeSignInModal() { hideModalAndClearHash(document.getElementById('signin-modal')); }
function closeRegisterModal() { hideModalAndClearHash(document.getElementById('register-modal')); }

function hideModalAndClearHash(modal) {
  if (!modal) return;
  modal.style.display = 'none';
  try {
    if (window.location.hash === '#' + modal.id) {
      history.replaceState(null, '', window.location.pathname + window.location.search);
    }
  } catch (err) {
    // ignore
  }
}

document.addEventListener('DOMContentLoaded', function() {
  // Close buttons
  document.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      const modal = this.closest('.modal-overlay');
      hideModalAndClearHash(modal);
    });
  });

  // Close modal when clicking outside the modal box
  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
      if (e.target === this) hideModalAndClearHash(this);
    });
  });

  // Intercept anchor links to modal hashes so opening is always handled by JS
  document.addEventListener('click', function(e) {
    const a = e.target.closest('a[href^="#"]');
    if (!a) return;
    const href = a.getAttribute('href');
    if (!href || !href.startsWith('#')) return;
    const id = href.slice(1);
    const modal = document.getElementById(id);
    if (modal && modal.classList.contains('modal-overlay')) {
      e.preventDefault();
      modal.style.display = 'flex';
    }
  });
});