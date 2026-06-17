(() => {
  const root = document.querySelector('[data-shakass-customizer]');
  if (!root) {
    return;
  }

  const app = window.ShakassCustomizer || {};
  const cfg = app.config || {};
  const products = Array.isArray(cfg.products) ? cfg.products : [];
  const mockups = Array.isArray(cfg.mockups) ? cfg.mockups : [];
  const pricing = cfg.pricing || {};
  const settings = cfg.settings || {};

  const $ = (selector, scope = root) => scope.querySelector(selector);
  const $$ = (selector, scope = root) => [...scope.querySelectorAll(selector)];
  const clamp = (value, min, max) => Math.max(min, Math.min(max, value));
  const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (char) => ({
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;',
  }[char]));

  if (!window.fabric || !$('#shakass-canvas')) {
    root.classList.add('is-editor-unavailable');
    const empty = $('[data-empty-state]');
    if (empty) {
      empty.textContent = 'Editeur graphique indisponible. Verifiez le chargement de Fabric.js.';
    }
    return;
  }

  const canvas = new fabric.Canvas('shakass-canvas', {
    backgroundColor: 'transparent',
    preserveObjectStacking: true,
    selection: true,
  });

  fabric.Object.prototype.cornerColor = '#ffffff';
  fabric.Object.prototype.cornerStrokeColor = '#ff5a2c';
  fabric.Object.prototype.borderColor = '#ff5a2c';
  fabric.Object.prototype.cornerStyle = 'circle';
  fabric.Object.prototype.transparentCorners = false;
  fabric.Object.prototype.padding = 4;

  const state = {
    side: 'front',
    product: null,
    color: null,
    size: '',
    quantity: 25,
    price: 0,
    zoom: 1,
    align: 'center',
    sides: { front: null, back: null },
    history: { front: [], back: [] },
    future: { front: [], back: [] },
    restoring: false,
  };

  const currency = new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    maximumFractionDigits: 2,
  });

  const serializeCanvas = () => canvas.toJSON(['name', 'shakassType', 'qrUrl']);

  const storeCurrentSide = () => {
    state.sides[state.side] = serializeCanvas();
  };

  const objectsFromSavedSide = (side) => {
    const saved = side === state.side ? serializeCanvas() : state.sides[side];
    return Array.isArray(saved?.objects) ? saved.objects : [];
  };

  const allDesignObjects = () => [
    ...objectsFromSavedSide('front'),
    ...objectsFromSavedSide('back'),
  ];

  const captureHistory = () => {
    if (state.restoring) {
      return;
    }

    const snapshot = JSON.stringify(serializeCanvas());
    const stack = state.history[state.side];
    if (stack[stack.length - 1] === snapshot) {
      return;
    }

    stack.push(snapshot);
    if (stack.length > 50) {
      stack.shift();
    }
    state.future[state.side] = [];
    storeCurrentSide();
  };

  const loadSnapshot = (snapshot, callback = () => {}) => {
    state.restoring = true;
    canvas.loadFromJSON(snapshot || { objects: [] }, () => {
      canvas.renderAll();
      canvas.calcOffset();
      state.restoring = false;
      storeCurrentSide();
      updateFloatingToolbar();
      updateLayers();
      updateSummary();
      callback();
    });
  };

  const resetHistoryForSide = () => {
    state.history[state.side] = [JSON.stringify(serializeCanvas())];
    state.future[state.side] = [];
    storeCurrentSide();
  };

  const undo = () => {
    const stack = state.history[state.side];
    if (stack.length < 2) {
      return;
    }

    const current = stack.pop();
    state.future[state.side].push(current);
    loadSnapshot(stack[stack.length - 1]);
  };

  const redo = () => {
    const future = state.future[state.side];
    if (!future.length) {
      return;
    }

    const snapshot = future.pop();
    state.history[state.side].push(snapshot);
    loadSnapshot(snapshot);
  };

  const productBySlug = (slug) => products.find((product) => product.slug === slug) || products[0] || null;

  const mockupFor = () => {
    if (!state.product) {
      return mockups[0] || null;
    }

    const productMockups = mockups.filter((mockup) => mockup.product === state.product.slug);
    const colorHex = String(state.color?.hex || '').toLowerCase();
    return productMockups.find((mockup) => String(mockup.color || '').toLowerCase() === colorHex)
      || productMockups[0]
      || mockups[0]
      || null;
  };

  const activeZone = () => {
    const mockup = mockupFor();
    return (state.side === 'back' ? mockup?.back_zone : mockup?.front_zone) || { x: 25, y: 24, w: 50, h: 58 };
  };

  const applyPrintZone = () => {
    const zone = activeZone();
    const el = $('[data-print-zone]');
    if (!el) {
      return;
    }

    el.style.left = `${zone.x}%`;
    el.style.top = `${zone.y}%`;
    el.style.width = `${zone.w}%`;
    el.style.height = `${zone.h}%`;
    window.setTimeout(() => canvas.calcOffset(), 40);
  };

  const applyMockup = () => {
    const shirt = $('[data-shirt-mockup]');
    const thumb = $('[data-summary-thumb]');
    if (!shirt) {
      return;
    }

    const mockup = mockupFor();
    const image = state.side === 'back' ? mockup?.back_image : mockup?.front_image;
    const color = state.color?.hex || mockup?.color || '#08090d';
    shirt.style.setProperty('--shirt-color', color);
    if (thumb) {
      thumb.style.setProperty('--shirt-color', color);
    }

    if (image) {
      shirt.classList.add('has-mockup-image');
      shirt.style.setProperty('--mockup-image', `url("${String(image).replaceAll('"', '%22')}")`);
    } else {
      shirt.classList.remove('has-mockup-image');
      shirt.style.removeProperty('--mockup-image');
    }

    applyPrintZone();
  };

  const setPanel = (tool) => {
    $$('[data-panel]').forEach((panel) => {
      panel.classList.toggle('is-active', panel.dataset.panel === tool);
    });
    $$('[data-tool]').forEach((button) => {
      button.classList.toggle('is-active', button.dataset.tool === tool);
    });

    const context = $('[data-context-panel]');
    if (context && window.matchMedia('(max-width: 1050px)').matches) {
      context.classList.add('is-open');
    }
  };

  const closePanel = () => {
    $('[data-context-panel]')?.classList.remove('is-open');
  };

  const renderProductDescription = () => {
    const target = $('[data-product-description]');
    if (!target || !state.product) {
      return;
    }

    target.textContent = '';
    const title = document.createElement('strong');
    title.textContent = `${state.product.type || 'Textile'} premium`;
    const body = document.createElement('span');
    body.textContent = [state.product.description, state.product.material, state.product.weight, state.product.fit]
      .filter(Boolean)
      .join(' · ');
    target.append(title, body);
  };

  const updateProjectLine = () => {
    const compact = $('[data-summary-compact]');
    if (compact && state.product) {
      compact.textContent = `${state.product.name} · ${state.quantity} pcs`;
    }
  };

  const calculatePrice = () => {
    const base = Number(pricing.base?.[state.product?.slug] || 0);
    const objects = allDesignObjects();
    const additions = objects.reduce((total, object) => {
      const type = object.shakassType || object.type;
      if (type === 'text' || type === 'textbox') {
        return total + Number(pricing.text || 0);
      }
      if (type === 'qr') {
        return total + Number(pricing.qr || 0);
      }
      return total + Number(pricing.image || 0);
    }, 0);

    const quantity = Math.max(1, Number(state.quantity || 1));
    const discounts = pricing.discounts || {};
    const discount = quantity >= 50
      ? discounts['50+']
      : quantity >= 25
        ? discounts['25-49']
        : quantity >= 10
          ? discounts['10-24']
          : discounts['1-9'];

    state.price = ((base + additions) * quantity) * (1 - Number(discount || 0) / 100);
  };

  const updateSummary = () => {
    if (!state.product) {
      return;
    }

    calculatePrice();
    $('[data-summary-product]').textContent = state.product.name;
    $('[data-summary-meta]').textContent = [state.product.weight, state.product.fit].filter(Boolean).join(' · ');
    $('[data-summary-color]').textContent = state.color?.name || '';
    $('[data-summary-size]').textContent = state.size || '';
    $('[data-summary-quantity]').textContent = `${state.quantity} pièces`;
    $('[data-summary-side]').textContent = state.side === 'front' ? 'Face' : 'Dos';
    $('[data-summary-price]').textContent = state.price > 0 ? currency.format(state.price) : 'Sur devis';
    $('[data-color-name]').textContent = state.color?.name || '';
    $('[data-layer-count]').textContent = `${canvas.getObjects().length} calque${canvas.getObjects().length > 1 ? 's' : ''}`;
    renderProductDescription();
    updateProjectLine();
    applyMockup();
    updateEmptyState();
  };

  const fillProductSelect = () => {
    const select = $('[data-product-select]');
    if (!select) {
      return;
    }

    select.textContent = '';
    products.forEach((product) => {
      const option = document.createElement('option');
      option.value = product.slug;
      option.textContent = product.name;
      select.append(option);
    });

    const defaultSlug = settings.default_product || products.find((product) => product.default)?.slug;
    state.product = productBySlug(defaultSlug);
    if (state.product) {
      select.value = state.product.slug;
    }
  };

  const fillSizes = () => {
    const select = $('[data-size-select]');
    if (!select || !state.product) {
      return;
    }

    const previous = state.size;
    select.textContent = '';
    (state.product.sizes || []).forEach((size) => {
      const option = document.createElement('option');
      option.value = size;
      option.textContent = size;
      select.append(option);
    });
    state.size = (state.product.sizes || []).includes(previous) ? previous : (state.product.sizes || [])[0] || '';
    select.value = state.size;
  };

  const fillSwatches = () => {
    const target = $('[data-swatches]');
    if (!target || !state.product) {
      return;
    }

    const previous = state.color?.hex;
    target.textContent = '';
    const colors = state.product.colors || [];
    state.color = colors.find((color) => color.hex === previous) || colors[0] || null;

    colors.forEach((color) => {
      const button = document.createElement('button');
      button.type = 'button';
      button.dataset.color = color.hex;
      button.dataset.name = color.name;
      button.style.backgroundColor = color.hex;
      button.setAttribute('aria-label', color.name);
      button.classList.toggle('is-active', state.color?.hex === color.hex);
      target.append(button);
    });
  };

  const markActiveSwatch = () => {
    $$('[data-color]').forEach((button) => {
      button.classList.toggle('is-active', button.dataset.color === state.color?.hex);
    });
  };

  const refreshProduct = () => {
    state.product = productBySlug($('[data-product-select]')?.value);
    fillSizes();
    fillSwatches();
    updateSummary();
  };

  const objectLabel = (object) => object?.name || (object?.type === 'textbox' ? 'Texte' : 'Element');
  const objectIcon = (object) => {
    const type = object?.shakassType || object?.type;
    if (type === 'text' || type === 'textbox') {
      return 'T';
    }
    if (type === 'qr') {
      return '▦';
    }
    return '◆';
  };

  const updateLayers = () => {
    const target = $('[data-layers]');
    if (!target) {
      return;
    }

    const objects = canvas.getObjects();
    if (!objects.length) {
      target.innerHTML = '<p class="sc-muted">Aucun calque sur cette face.</p>';
      updateSummary();
      return;
    }

    target.innerHTML = objects
      .map((object, index) => ({ object, index }))
      .reverse()
      .map(({ object, index }) => `
        <div class="sc-layer ${object === canvas.getActiveObject() ? 'is-active' : ''}">
          <button type="button" data-layer-select="${index}" class="sc-layer-main">
            <span>${objectIcon(object)}</span>
            <strong>${escapeHtml(objectLabel(object))}</strong>
          </button>
          <button type="button" data-layer-up="${index}" title="Monter">↑</button>
          <button type="button" data-layer-down="${index}" title="Descendre">↓</button>
          <button type="button" data-layer-toggle="${index}" title="${object.visible === false ? 'Afficher' : 'Masquer'}">${object.visible === false ? '○' : '●'}</button>
          <button type="button" data-layer-copy="${index}" title="Dupliquer">⧉</button>
          <button type="button" data-layer-delete="${index}" title="Supprimer">×</button>
        </div>
      `)
      .join('');

    updateSummary();
  };

  const updateEmptyState = () => {
    const empty = $('[data-empty-state]');
    if (empty) {
      empty.classList.toggle('is-hidden', canvas.getObjects().length > 0);
    }
  };

  const updateFloatingToolbar = () => {
    $('[data-floating-toolbar]')?.classList.toggle('is-visible', !!canvas.getActiveObject());
    updateLayers();
  };

  const activeTextbox = () => {
    const object = canvas.getActiveObject();
    return object?.type === 'textbox' ? object : null;
  };

  const syncTextControls = () => {
    const textbox = activeTextbox();
    if (!textbox) {
      return;
    }

    $('[data-text-input]').value = textbox.text || '';
    $('[data-font-size]').value = Math.round(textbox.fontSize || 42);
    $('[data-text-color]').value = textbox.fill || '#ffffff';
    state.align = textbox.textAlign || 'center';
    $$('[data-text-align]').forEach((button) => {
      button.classList.toggle('is-active', button.dataset.textAlign === state.align);
    });
  };

  const commonObjectOptions = (name, type) => ({
    name,
    shakassType: type,
    originX: 'left',
    originY: 'top',
    borderScaleFactor: 1.4,
  });

  const addText = () => {
    const text = $('[data-text-input]')?.value || 'Votre texte';
    const fontSize = Number($('[data-font-size]')?.value || 42);
    const fill = $('[data-text-color]')?.value || '#ffffff';

    const object = new fabric.Textbox(text, {
      ...commonObjectOptions('Texte', 'text'),
      left: 70,
      top: 105,
      width: 220,
      fontSize,
      fill,
      fontFamily: 'Inter, Arial, sans-serif',
      fontWeight: 800,
      textAlign: state.align,
    });
    canvas.add(object);
    canvas.setActiveObject(object);
    canvas.requestRenderAll();
    captureHistory();
    updateFloatingToolbar();
  };

  const applyText = () => {
    const object = activeTextbox();
    if (!object) {
      return;
    }

    object.set({
      text: $('[data-text-input]')?.value || object.text,
      fontSize: Number($('[data-font-size]')?.value || object.fontSize),
      fill: $('[data-text-color]')?.value || object.fill,
      textAlign: state.align,
    });
    canvas.requestRenderAll();
    captureHistory();
    updateLayers();
  };

  const deleteObject = (object) => {
    if (!object) {
      return;
    }

    if (object.type === 'activeSelection') {
      object.getObjects().forEach((item) => canvas.remove(item));
      canvas.discardActiveObject();
    } else {
      canvas.remove(object);
    }
    canvas.requestRenderAll();
    captureHistory();
    updateFloatingToolbar();
  };

  const duplicateObject = (object) => {
    if (!object) {
      return;
    }

    object.clone((clone) => {
      clone.set({
        left: (object.left || 0) + 18,
        top: (object.top || 0) + 18,
        name: `${objectLabel(object)} copie`,
      });
      canvas.add(clone);
      canvas.setActiveObject(clone);
      canvas.requestRenderAll();
      captureHistory();
      updateFloatingToolbar();
    }, ['name', 'shakassType', 'qrUrl']);
  };

  const addLogo = (label) => {
    const object = new fabric.Textbox(label, {
      ...commonObjectOptions('Logo', 'image'),
      left: 95,
      top: 130,
      width: 170,
      fontSize: label.length > 3 ? 38 : 68,
      fill: settings.accent || '#ff5a2c',
      fontFamily: 'Inter, Arial, sans-serif',
      fontWeight: 900,
      textAlign: 'center',
    });
    canvas.add(object);
    canvas.setActiveObject(object);
    canvas.requestRenderAll();
    captureHistory();
    updateFloatingToolbar();
  };

  const qrSeed = (value) => {
    let hash = 0;
    for (let index = 0; index < value.length; index += 1) {
      hash = ((hash << 5) - hash) + value.charCodeAt(index);
      hash |= 0;
    }
    return Math.abs(hash);
  };

  const qrRects = (url, size = 120) => {
    const cell = size / 11;
    const seed = qrSeed(url || 'shakass-communication');
    const rects = [new fabric.Rect({ left: 0, top: 0, width: size, height: size, fill: '#ffffff', rx: 7, ry: 7 })];
    const finder = [
      [1, 1], [7, 1], [1, 7],
    ];

    finder.forEach(([x, y]) => {
      rects.push(new fabric.Rect({ left: x * cell, top: y * cell, width: cell * 3, height: cell * 3, fill: '#111318' }));
      rects.push(new fabric.Rect({ left: (x + 0.75) * cell, top: (y + 0.75) * cell, width: cell * 1.5, height: cell * 1.5, fill: '#ffffff' }));
      rects.push(new fabric.Rect({ left: (x + 1.15) * cell, top: (y + 1.15) * cell, width: cell * 0.7, height: cell * 0.7, fill: '#111318' }));
    });

    for (let y = 1; y < 10; y += 1) {
      for (let x = 1; x < 10; x += 1) {
        const inFinder = finder.some(([fx, fy]) => x >= fx && x <= fx + 2 && y >= fy && y <= fy + 2);
        if (inFinder) {
          continue;
        }
        const bit = ((seed + x * 17 + y * 31 + x * y) % 5) < 2;
        if (bit) {
          rects.push(new fabric.Rect({
            left: x * cell,
            top: y * cell,
            width: cell * 0.82,
            height: cell * 0.82,
            fill: '#111318',
          }));
        }
      }
    }

    return rects;
  };

  const renderQrPreview = () => {
    const target = $('[data-qr-preview]');
    if (!target) {
      return;
    }
    const url = $('[data-qr-url]')?.value || 'https://shakass-communication.fr';
    const seed = qrSeed(url);
    target.innerHTML = Array.from({ length: 121 }, (_, index) => {
      const x = index % 11;
      const y = Math.floor(index / 11);
      const on = x === 0 || y === 0 || x === 10 || y === 10 ? false : ((seed + x * 17 + y * 31 + x * y) % 5) < 2;
      return `<span class="${on ? 'is-on' : ''}"></span>`;
    }).join('');
  };

  const addQr = () => {
    const url = $('[data-qr-url]')?.value || 'https://shakass-communication.fr';
    const group = new fabric.Group(qrRects(url), {
      ...commonObjectOptions('QR Code', 'qr'),
      left: 120,
      top: 155,
      qrUrl: url,
    });
    canvas.add(group);
    canvas.setActiveObject(group);
    canvas.requestRenderAll();
    captureHistory();
    updateFloatingToolbar();
    renderQrPreview();
  };

  const addImageFile = (file) => {
    if (!file || !/^image\/(png|jpeg)$/.test(file.type)) {
      return;
    }

    $('[data-upload-list]').innerHTML = `<span>${escapeHtml(file.name)}</span>`;
    const reader = new FileReader();
    reader.onload = (event) => {
      fabric.Image.fromURL(event.target.result, (image) => {
        image.scaleToWidth(Math.min(190, canvas.getWidth() * 0.62));
        image.set({
          ...commonObjectOptions(file.name, 'image'),
          left: 85,
          top: 120,
        });
        canvas.add(image);
        canvas.setActiveObject(image);
        canvas.requestRenderAll();
        captureHistory();
        updateFloatingToolbar();
      });
    };
    reader.readAsDataURL(file);
  };

  const selectLayer = (index) => {
    const object = canvas.getObjects()[index];
    if (!object) {
      return;
    }
    canvas.setActiveObject(object);
    canvas.requestRenderAll();
    syncTextControls();
    updateFloatingToolbar();
  };

  const moveLayer = (index, direction) => {
    const object = canvas.getObjects()[index];
    if (!object) {
      return;
    }
    if (direction === 'up') {
      canvas.bringForward(object);
    } else {
      canvas.sendBackwards(object);
    }
    canvas.requestRenderAll();
    captureHistory();
    updateLayers();
  };

  const toggleLayer = (index) => {
    const object = canvas.getObjects()[index];
    if (!object) {
      return;
    }
    object.visible = object.visible === false;
    canvas.requestRenderAll();
    captureHistory();
    updateLayers();
  };

  const switchSide = (side) => {
    if (!['front', 'back'].includes(side) || side === state.side) {
      return;
    }

    storeCurrentSide();
    state.side = side;
    $$('[data-side]').forEach((button) => {
      button.classList.toggle('is-active', button.dataset.side === side);
    });

    const saved = state.sides[side] || { objects: [] };
    loadSnapshot(saved, () => {
      if (!state.history[side].length) {
        resetHistoryForSide();
      }
      applyMockup();
    });
  };

  const saveDraft = () => {
    storeCurrentSide();
    const draft = {
      product: state.product?.slug,
      color: state.color?.hex,
      size: state.size,
      quantity: state.quantity,
      side: state.side,
      sides: state.sides,
    };
    try {
      window.localStorage.setItem('shakass_customizer_draft', JSON.stringify(draft));
      $('[data-summary-compact]').textContent = 'Brouillon enregistré';
    } catch (error) {
      $('[data-summary-compact]').textContent = 'Brouillon non disponible';
    }
  };

  const loadDraft = () => {
    let raw = '';
    try {
      raw = window.localStorage.getItem('shakass_customizer_draft');
    } catch (error) {
      raw = '';
    }
    if (!raw) {
      return false;
    }

    try {
      const draft = JSON.parse(raw);
      const product = productBySlug(draft.product);
      if (!product) {
        return false;
      }
      state.product = product;
      $('[data-product-select]').value = product.slug;
      fillSizes();
      fillSwatches();
      state.color = (state.product.colors || []).find((color) => color.hex === draft.color) || state.color;
      markActiveSwatch();
      state.size = draft.size || state.size;
      state.quantity = Math.max(1, Number(draft.quantity || state.quantity));
      $('[data-size-select]').value = state.size;
      $('[data-quantity]').value = state.quantity;
      state.sides = draft.sides || state.sides;
      state.side = draft.side === 'back' ? 'back' : 'front';
      $$('[data-side]').forEach((button) => button.classList.toggle('is-active', button.dataset.side === state.side));
      loadSnapshot(state.sides[state.side] || { objects: [] }, resetHistoryForSide);
      return true;
    } catch (error) {
      return false;
    }
  };

  const resetDesign = () => {
    if (!window.confirm('Réinitialiser la configuration en cours ?')) {
      return;
    }

    canvas.clear();
    state.sides = { front: { objects: [] }, back: { objects: [] } };
    state.history = { front: [], back: [] };
    state.future = { front: [], back: [] };
    resetHistoryForSide();
    updateFloatingToolbar();
    updateSummary();
  };

  const setZoom = (direction) => {
    state.zoom = clamp(state.zoom + (direction === 'in' ? 0.08 : -0.08), 0.72, 1.24);
    $('[data-stage-inner]').style.transform = `scale(${state.zoom})`;
    $('[data-zoom-label]').textContent = `${Math.round(state.zoom * 100)}%`;
    window.setTimeout(() => canvas.calcOffset(), 80);
  };

  root.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    const swatch = event.target.closest('[data-color]');

    if (swatch) {
      $$('[data-color]').forEach((item) => item.classList.remove('is-active'));
      swatch.classList.add('is-active');
      state.color = { name: swatch.dataset.name, hex: swatch.dataset.color };
      updateSummary();
      return;
    }

    if (!button) {
      return;
    }

    if (button.dataset.tool) {
      setPanel(button.dataset.tool);
    }
    if (button.dataset.closePanel !== undefined) {
      closePanel();
    }
    if (button.dataset.addText !== undefined) {
      addText();
    }
    if (button.dataset.applyText !== undefined) {
      applyText();
    }
    if (button.dataset.textAlign) {
      state.align = button.dataset.textAlign;
      $$('[data-text-align]').forEach((item) => item.classList.toggle('is-active', item === button));
      applyText();
    }
    if (button.dataset.deleteActive !== undefined) {
      deleteObject(canvas.getActiveObject());
    }
    if (button.dataset.duplicateActive !== undefined) {
      duplicateObject(canvas.getActiveObject());
    }
    if (button.dataset.addLogo) {
      addLogo(button.dataset.addLogo);
    }
    if (button.dataset.generateQr !== undefined) {
      renderQrPreview();
    }
    if (button.dataset.addQr !== undefined) {
      addQr();
    }
    if (button.dataset.pickFile !== undefined) {
      $('[data-file-input]')?.click();
    }
    if (button.dataset.openRequest !== undefined) {
      setPanel('request');
    }
    if (button.dataset.side) {
      switchSide(button.dataset.side);
    }
    if (button.dataset.zoom) {
      setZoom(button.dataset.zoom);
    }
    if (button.dataset.action === 'undo') {
      undo();
    }
    if (button.dataset.action === 'redo') {
      redo();
    }
    if (button.dataset.action === 'save') {
      saveDraft();
    }
    if (button.dataset.action === 'reset') {
      resetDesign();
    }
    if (button.dataset.layerSelect !== undefined) {
      selectLayer(Number(button.dataset.layerSelect));
    }
    if (button.dataset.layerUp !== undefined) {
      moveLayer(Number(button.dataset.layerUp), 'up');
    }
    if (button.dataset.layerDown !== undefined) {
      moveLayer(Number(button.dataset.layerDown), 'down');
    }
    if (button.dataset.layerToggle !== undefined) {
      toggleLayer(Number(button.dataset.layerToggle));
    }
    if (button.dataset.layerCopy !== undefined) {
      duplicateObject(canvas.getObjects()[Number(button.dataset.layerCopy)]);
    }
    if (button.dataset.layerDelete !== undefined) {
      deleteObject(canvas.getObjects()[Number(button.dataset.layerDelete)]);
    }
  });

  root.addEventListener('change', (event) => {
    if (event.target.matches('[data-product-select]')) {
      refreshProduct();
    }
    if (event.target.matches('[data-size-select]')) {
      state.size = event.target.value;
      updateSummary();
    }
    if (event.target.matches('[data-quantity]')) {
      state.quantity = Math.max(1, Number(event.target.value || 1));
      event.target.value = state.quantity;
      updateSummary();
    }
    if (event.target.matches('[data-file-input]')) {
      addImageFile(event.target.files?.[0]);
      event.target.value = '';
    }
  });

  root.addEventListener('input', (event) => {
    if (event.target.matches('[data-quantity]')) {
      state.quantity = Math.max(1, Number(event.target.value || 1));
      updateSummary();
    }
    if (event.target.matches('[data-qr-url]')) {
      renderQrPreview();
    }
  });

  $('[data-request-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    storeCurrentSide();

    const status = $('[data-request-status]');
    if (status) {
      status.textContent = 'Envoi en cours...';
    }

    const formData = Object.fromEntries(new FormData(event.target).entries());
    const payload = {
      ...formData,
      product: state.product?.name || '',
      color: state.color?.name || '',
      size: state.size,
      side: state.side,
      quantity: state.quantity,
      estimated_price: state.price,
      configuration: {
        product: state.product?.slug,
        color: state.color,
        size: state.size,
        quantity: state.quantity,
        front: state.sides.front,
        back: state.sides.back,
      },
      preview: canvas.toDataURL({ format: 'png', quality: 0.72 }),
    };

    try {
      const response = await fetch(app.requestUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-WP-Nonce': app.nonce,
        },
        body: JSON.stringify(payload),
      });
      const json = await response.json().catch(() => ({}));
      if (status) {
        status.textContent = response.ok ? `Demande sauvegardée (${json.reference}).` : (json.message || 'Erreur d’envoi.');
      }
    } catch (error) {
      if (status) {
        status.textContent = 'Erreur réseau pendant l’envoi.';
      }
    }
  });

  canvas.on('selection:created', () => {
    syncTextControls();
    updateFloatingToolbar();
  });
  canvas.on('selection:updated', () => {
    syncTextControls();
    updateFloatingToolbar();
  });
  canvas.on('selection:cleared', updateFloatingToolbar);
  canvas.on('object:modified', () => {
    captureHistory();
    updateLayers();
  });
  canvas.on('object:added', () => {
    if (!state.restoring) {
      storeCurrentSide();
      updateLayers();
    }
  });
  canvas.on('object:removed', () => {
    if (!state.restoring) {
      storeCurrentSide();
      updateLayers();
    }
  });

  window.addEventListener('resize', () => {
    applyPrintZone();
    canvas.calcOffset();
  });

  fillProductSelect();
  refreshProduct();
  renderQrPreview();
  canvas.clear();
  resetHistoryForSide();
  if (!loadDraft()) {
    updateSummary();
  }
})();
