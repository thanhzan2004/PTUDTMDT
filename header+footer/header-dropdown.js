document.querySelectorAll('.dropdown').forEach(dropdown => {
    dropdown.addEventListener('mouseenter', () => {
      const content = dropdown.querySelector('.dropdown-content');
      content.style.display = 'block';
    });
  
    dropdown.addEventListener('mouseleave', () => {
      const content = dropdown.querySelector('.dropdown-content');
      content.style.display = 'none';
    });
  });
  