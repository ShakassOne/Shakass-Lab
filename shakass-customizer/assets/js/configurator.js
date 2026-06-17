(() => {
  const root = document.querySelector('[data-shakass-customizer]');
  if (!root) return;

  const canvas = new fabric.Canvas('shakass-canvas', { backgroundColor: 'transparent', preserveObjectStacking: true });
  const panels = [...root.querySelectorAll('[data-panel]')];
  const toolButtons = [...root.querySelectorAll('[data-tool]')];
  const shirt = root.querySelector('[data-shirt-mockup]');
  const layersTargets = [root.querySelector('[data-layers]')].filter(Boolean);
  const sheet = root.querySelector('[data-mobile-sheet]');
  const sheetContent = root.querySelector('[data-sheet-content]');

  fabric.Object.prototype.cornerColor = '#ffffff';
  fabric.Object.prototype.cornerStrokeColor = '#ff5a2c';
  fabric.Object.prototype.borderColor = '#ff5a2c';
  fabric.Object.prototype.cornerStyle = 'circle';
  fabric.Object.prototype.cornerSize = 9;
  fabric.Object.prototype.padding = 4;
  fabric.Object.prototype.transparentCorners = false;

  const addText = (text, options = {}) => {
    const object = new fabric.Textbox(text || 'Votre texte', {
      left: 82, top: 110, width: 200, fontSize: Number(root.querySelector('[data-font-size]')?.value || 42),
      fontFamily: root.querySelector('[data-font]')?.value || 'Montserrat', fill: root.querySelector('[data-text-color]')?.value || '#fff',
      textAlign: root.querySelector('[data-align]')?.value || 'center', fontWeight: options.bold ? '700' : '600',
      shadow: options.shadow ? '0 0 14px rgba(255,90,44,.85)' : null, name: text || 'Texte'
    });
    canvas.add(object).setActiveObject(object); renderLayers();
  };

  const seed = () => {
    addText('SHAKASS', { shadow: true });
    const sub = new fabric.Textbox('COMMUNICATION', { left: 92, top: 168, width: 180, fontSize: 20, charSpacing: 180, fontFamily: 'Arial', fill: '#ffb199', textAlign: 'center', name: 'Communication' });
    const crown = new fabric.Text('♛', { left: 155, top: 62, fontSize: 36, fill: '#ff5a2c', name: 'Couronne' });
    canvas.add(sub, crown); renderLayers();
  };

  const openTool = (tool) => {
    panels.forEach(panel => panel.classList.toggle('is-active', panel.dataset.panel === tool));
    toolButtons.forEach(button => button.classList.toggle('is-active', button.dataset.tool === tool));
    if (window.matchMedia('(max-width: 1050px)').matches && sheet && sheetContent) {
      const panel = root.querySelector(`[data-panel="${tool}"]`);
      sheetContent.innerHTML = panel ? panel.innerHTML : '';
      sheet.classList.add('is-open');
    }
  };

  const renderLayers = () => {
    const objects = canvas.getObjects().slice().reverse();
    layersTargets.forEach(target => {
      target.innerHTML = objects.map((obj, index) => `<div class="sc-layer"><span class="sc-layer-icon">${obj.type === 'textbox' ? 'T' : obj.type === 'rect' ? '▦' : '◇'}</span><span>${obj.name || obj.type || 'Élément'}</span><button data-layer-select="${index}">Voir</button><button data-layer-copy="${index}">⧉</button><button data-layer-delete="${index}">×</button></div>`).join('');
    });
  };

  root.addEventListener('click', (event) => {
    const button = event.target.closest('button');
    if (!button) return;
    if (button.dataset.tool) openTool(button.dataset.tool);
    if (button.dataset.addText !== undefined) addText(root.querySelector('[data-text-input]')?.value || 'SHAKASS');
    if (button.dataset.applyText !== undefined && canvas.getActiveObject()?.type === 'textbox') canvas.getActiveObject().set('text', root.querySelector('[data-text-input]')?.value || 'Texte') && canvas.requestRenderAll();
    if (button.dataset.addLogo) { canvas.add(new fabric.Text(button.dataset.addLogo, { left: 145, top: 105, fontSize: 54, fill: '#ff5a2c', name: 'Logo' })); renderLayers(); }
    if (button.dataset.addQr !== undefined || button.dataset.generateQr !== undefined) { canvas.add(new fabric.Rect({ left: 135, top: 210, width: 86, height: 86, fill: '#fff', name: 'QR Code' })); renderLayers(); }
    if (button.dataset.openRequest !== undefined) openTool('request');
    if (button.dataset.closeSheet !== undefined) sheet?.classList.remove('is-open');
    if (button.dataset.pickFile !== undefined) root.querySelector('[data-file-input]')?.click();
    if (button.dataset.side) root.querySelectorAll('[data-side]').forEach(el => el.classList.toggle('is-active', el === button));
    if (button.dataset.layerSelect) { const obj = canvas.getObjects().slice().reverse()[button.dataset.layerSelect]; if (obj) canvas.setActiveObject(obj).requestRenderAll(); }
    if (button.dataset.layerDelete) { const obj = canvas.getObjects().slice().reverse()[button.dataset.layerDelete]; canvas.remove(obj); renderLayers(); }
    if (button.dataset.layerCopy) { const obj = canvas.getObjects().slice().reverse()[button.dataset.layerCopy]; obj?.clone(clone => { clone.set({ left: obj.left + 18, top: obj.top + 18, name: `${obj.name || 'Élément'} copie` }); canvas.add(clone).setActiveObject(clone); renderLayers(); }); }
    if (button.dataset.floatingDelete !== undefined && canvas.getActiveObject()) { canvas.remove(canvas.getActiveObject()); renderLayers(); }
    if (button.dataset.floatingCopy !== undefined && canvas.getActiveObject()) { const obj = canvas.getActiveObject(); obj.clone(clone => { clone.set({ left: obj.left + 18, top: obj.top + 18, name: `${obj.name || 'Élément'} copie` }); canvas.add(clone).setActiveObject(clone); renderLayers(); }); }
  });

  root.addEventListener('change', (event) => {
    const target = event.target;
    if (target.dataset.summary === 'product') root.querySelector('[data-summary-product]').textContent = target.value;
    if (target.dataset.summary === 'size') root.querySelector('[data-summary-size]').textContent = target.value;
    if (target.matches('[data-file-input]') && target.files[0]) root.querySelector('[data-upload-list]').innerHTML += `<span>${target.files[0].name}</span>`;
  });

  root.querySelectorAll('[data-shirt]').forEach((swatch) => swatch.addEventListener('click', () => {
    root.querySelectorAll('[data-shirt]').forEach(item => item.classList.remove('is-active'));
    swatch.classList.add('is-active'); shirt.style.setProperty('--shirt', swatch.dataset.shirt);
    const name = swatch.getAttribute('aria-label') || 'Couleur';
    root.querySelector('[data-color-name]').textContent = name; root.querySelector('[data-summary-color]').textContent = name;
  }));

  root.querySelector('[data-request-form]')?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const status = root.querySelector('[data-request-status]');
    status.textContent = 'Préparation de votre demande…';
    const data = Object.fromEntries(new FormData(event.target).entries());
    const response = await fetch(ShakassCustomizer.restUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-WP-Nonce': ShakassCustomizer.nonce }, body: JSON.stringify({ ...data, configuration: canvas.toJSON(['name']) }) });
    status.textContent = response.ok ? 'Demande prête à être transmise.' : 'Impossible d’envoyer la demande pour le moment.';
  });

  const toolbar = root.querySelector('[data-floating-toolbar]');
  const syncFloatingToolbar = () => toolbar?.classList.toggle('is-visible', Boolean(canvas.getActiveObject()));

  canvas.on('selection:created', syncFloatingToolbar);
  canvas.on('selection:updated', syncFloatingToolbar);
  canvas.on('selection:cleared', syncFloatingToolbar);
  canvas.on('object:added', renderLayers); canvas.on('object:removed', renderLayers); seed(); syncFloatingToolbar();
})();
