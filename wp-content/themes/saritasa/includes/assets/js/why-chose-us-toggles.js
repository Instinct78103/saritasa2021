document.addEventListener('click', e => {
  if (e.target.matches('.vc_toggle h4')) {
    for (let i = e.target; i && i !== this; i = i.parentNode) {
      if (i.matches('.vc_toggle')) {
        i.classList.toggle('open');
        break;
      }
    }
  }
});