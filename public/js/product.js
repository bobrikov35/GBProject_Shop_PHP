'use strict';

function imgClick(obj) {
  const id = parseInt(obj.attributes['data-img'].value);

  const curImg = document.getElementById(`img-${id}`);
  let nextImg = document.getElementById(`img-${id + 1}`);

  if (!nextImg) {
    nextImg = document.getElementById('img-0');
    obj.attributes['data-img'].value = 0;

  } else {
    obj.attributes['data-img'].value = id + 1;
  }

  if (curImg.id !== nextImg.id) {
    curImg.style.display = 'none';

    nextImg.style.display = 'inline';

    if (obj.clientWidth - 6 * 2 < nextImg.style.width) {
      nextImg.style.width = `${obj.clientWidth - 6 * 2}px`;
      nextImg.style.height = 'auto';
    }
  }
}
