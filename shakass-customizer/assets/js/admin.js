(() => {
  const root = document.querySelector('.sc-admin');
  if (!root) {
    return;
  }

  const nextIndex = () => `new_${Date.now()}_${Math.floor(Math.random() * 1000)}`;

  const refreshColorTitle = () => {
    root.querySelectorAll('input[type="color"]').forEach((input) => {
      input.title = input.value;
    });
  };

  const addRow = (type) => {
    const template = root.querySelector(`template[data-repeat-template="${type}"]`);
    const list = root.querySelector(`[data-repeat-list="${type}"]`);
    if (!template || !list) {
      return;
    }

    const holder = document.createElement('div');
    holder.innerHTML = template.innerHTML.replaceAll('__INDEX__', nextIndex());
    const row = holder.querySelector('[data-repeat-item]');
    if (!row) {
      return;
    }

    row.dataset.new = '1';
    list.appendChild(row);
    refreshColorTitle();
    row.scrollIntoView({ behavior: 'smooth', block: 'center' });
  };

  const removeRow = (button) => {
    const row = button.closest('[data-repeat-item]');
    if (!row) {
      return;
    }

    if (row.dataset.new === '1') {
      row.remove();
      return;
    }

    const flag = row.querySelector('[data-delete-flag]');
    if (flag) {
      flag.value = '1';
    }

    row.querySelectorAll('input, select, textarea, button').forEach((field) => {
      if (field === flag) {
        return;
      }
      field.disabled = true;
      field.required = false;
    });
    row.classList.add('is-deleted');
  };

  const pickMedia = (button) => {
    const field = button.closest('.sc-media-field')?.querySelector('[data-media-input]');
    if (!field || !window.wp?.media) {
      return;
    }

    const frame = window.wp.media({
      title: 'Choisir un mockup',
      button: { text: 'Utiliser cette image' },
      multiple: false,
      library: { type: 'image' },
    });

    frame.on('select', () => {
      const attachment = frame.state().get('selection').first()?.toJSON();
      if (attachment?.url) {
        field.value = attachment.url;
        field.dispatchEvent(new Event('change', { bubbles: true }));
      }
    });

    frame.open();
  };

  root.addEventListener('click', (event) => {
    const addButton = event.target.closest('[data-add-row]');
    if (addButton) {
      event.preventDefault();
      addRow(addButton.dataset.addRow);
      return;
    }

    const removeButton = event.target.closest('[data-remove-row]');
    if (removeButton) {
      event.preventDefault();
      removeRow(removeButton);
      return;
    }

    const mediaButton = event.target.closest('[data-media-pick]');
    if (mediaButton) {
      event.preventDefault();
      pickMedia(mediaButton);
    }
  });

  root.addEventListener('input', (event) => {
    if (event.target.matches('input[type="color"]')) {
      event.target.title = event.target.value;
    }

    if (event.target.matches('[name$="[name]"]')) {
      const item = event.target.closest('[data-repeat-item]');
      const title = item?.querySelector('.sc-card-title h2');
      if (title) {
        title.textContent = event.target.value || 'Nouvel élément';
      }
    }
  });

  refreshColorTitle();
})();
