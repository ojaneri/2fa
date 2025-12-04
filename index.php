<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gerador 2FA - KAP</title>
  <script src="https://unpkg.com/jsqr@1.4.0/dist/jsQR.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/intro.js@7.2.0/minified/introjs.min.css">
  <script src="https://unpkg.com/intro.js@7.2.0/minified/intro.min.js"></script>
  <style>
    :root { --bg:#0f172a; --fg:#e5e7eb; --muted:#94a3b8; --card:#111827; --accent:#4f46e5; --ok:#22c55e; }
    body {margin:0; background:linear-gradient(160deg,#0f172a 0%,#111827 100%); color:var(--fg); font:16px/1.45 system-ui, -apple-system, Segoe UI, Roboto, Ubuntu, Cantarell, Noto Sans, Arial, "Apple Color Emoji","Segoe UI Emoji"}
    .wrap {max-width:920px; margin:40px auto; padding:0 16px; position:relative}
    .card {background:rgba(17,24,39,.8); border:1px solid rgba(255,255,255,.06); border-radius:16px; padding:20px; box-shadow:0 10px 30px rgba(0,0,0,.35)}
    h1 {font-size:clamp(22px,4vw,32px); margin:0 0 6px 0}
    .muted {color:var(--muted)}
    label {display:block; margin:.6rem 0 .2rem; color:#cbd5e1}
    input, select {width:100%; padding:12px 14px; border-radius:12px; border:1px solid rgba(255,255,255,.1); background:#0b1220; color:var(--fg); outline:none}
    input:focus, select:focus {border-color:rgba(79,70,229,.55); box-shadow:0 0 0 3px rgba(79,70,229,.25)}
    .row {display:grid; grid-template-columns:1fr 1fr; gap:12px}
    .btns {display:flex; gap:10px; flex-wrap:wrap; margin-top:12px}
    button {cursor:pointer; border:0; padding:10px 14px; border-radius:12px; color:white; background:var(--accent)}
    button.secondary{background:#334155}
    button.ghost{background:transparent; border:1px solid rgba(255,255,255,.12)}
    .panel {display:grid; grid-template-columns:1fr auto; gap:16px; align-items:center; margin-top:14px}
    .token {font-variant-numeric:tabular-nums; font-size:clamp(36px,7vw,64px); letter-spacing:6px; font-weight:800}
    .chip {font-size:12px; padding:6px 10px; background:#0b1220; border:1px solid rgba(255,255,255,.1); border-radius:999px}
    .grid {display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-top:10px}
    .progress {height:10px; width:100%; background:#0b1220; border-radius:999px; overflow:hidden; border:1px solid rgba(255,255,255,.08)}
    .progress>i {display:block; height:100%; width:0%; background:linear-gradient(90deg,var(--ok),#10b981)}
    .hint {font-size:12px; color:#a3a3a3}
    .footer {margin-top:18px; font-size:13px; color:#a3a3a3}
    .ok {color:#34d399}
    .err {color:#fca5a5}
    details {margin-top:18px}
    .tabs {display:flex; margin-bottom:20px; border-bottom:1px solid rgba(255,255,255,.1)}
    .tab {cursor:pointer; padding:10px 20px; background:transparent; border:0; color:var(--muted); border-bottom:2px solid transparent}
    .tab.active {color:var(--fg); border-bottom-color:var(--accent)}
    .tab-content {display:none}
    .tab-content.active {display:block}
    .modal {display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.5); z-index:1000; align-items:center; justify-content:center}
    .modal.show {display:flex}
    .modal-content {background:var(--card); padding:20px; border-radius:16px; max-width:500px; width:90%; text-align:center}
    .modal-content p {margin:10px 0}
    .modal-content button {margin:5px}
    @media (max-width:720px){ .row, .grid {grid-template-columns:1fr} }
    .help-btn {position:absolute; top:0; right:0; background:var(--accent); color:white; border:0; padding:8px 12px; border-radius:8px; cursor:pointer}
    .introjs-tooltiptext { color: #333 !important; }
  </style>
</head>
<body>
  <div class="wrap">
    <button class="help-btn" id="helpBtn">Ajuda</button>
    <div class="tabs">
      <button class="tab active" data-tab="generate">Gerar 2FA</button>
      <button class="tab" data-tab="read">Ler QR Code</button>
      <button class="tab" data-tab="about">Saiba mais</button>
    </div>
    <div id="generate" class="tab-content active card">
      <h1>Gerador 2FA</h1>
      <p class="muted">Cole o <b>secret Base32</b> do seu 2FA para gerar o token atual. Tudo roda no seu navegador, sem enviar nada para o servidor.</p>

      <label for="secret">Secret (Base32)</label>
      <input id="secret" placeholder="Ex.: JBSWY3DPEHPK3PXP" spellcheck="false" autocomplete="one-time-code" />

      <details>
        <summary>Avançado</summary>
        <div class="row" style="margin-top:12px">
          <div>
            <label for="digits">Dígitos</label>
            <select id="digits">
              <option value="6" selected>6</option>
              <option value="7">7</option>
              <option value="8">8</option>
            </select>
          </div>
          <div>
            <label for="period">Período (s)</label>
            <select id="period">
              <option value="30" selected>30</option>
              <option value="15">15</option>
              <option value="60">60</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div>
            <label for="algo">Algoritmo</label>
            <select id="algo">
              <option value="SHA-1" selected>SHA‑1 (padrão)</option>
              <option value="SHA-256">SHA‑256</option>
              <option value="SHA-512">SHA‑512</option>
            </select>
          </div>
          <div>
            <label for="label">Rótulo local (opcional)</label>
            <input id="label" placeholder="ex.: Conta Google" />
          </div>
        </div>
      </details>

      <div class="btns">
        <button id="btnRun">Gerar agora</button>
        <button class="secondary" id="btnAuto">Auto‑atualizar</button>
        <button class="ghost" id="btnCopy">Copiar código</button>
        <button class="ghost" id="btnCopyUrl">Copiar URL</button>
        <span class="chip" id="status">parado</span>
      </div>

      <div class="panel">
        <div class="token" id="token">000000</div>
        <div>
          <div class="progress"><i id="bar"></i></div>
          <div class="hint" id="timeleft">restante: 30s</div>
        </div>
      </div>

      <div class="grid">
        <div class="card" style="padding:12px">
          <div class="hint">Unix time</div>
          <div id="now"></div>
        </div>
        <div class="card" style="padding:12px">
          <div class="hint">Counter</div>
          <div id="counter"></div>
        </div>
        <div class="card" style="padding:12px">
          <div class="hint">Próxima rotação</div>
          <div id="next"></div>
        </div>
      </div>

      <p class="footer">Compatível com RFC 6238 (TOTP). Algoritmo e período configuráveis.</p>

      <p class="footer"><a href="https://kaponline.com.br/lp1/" target="_blank">Conheça o KAP - Laboratório de Provas Digitais para Advogados e Peritos</a></p>
      <p class="footer">Créditos: <a href="https://instagram.com/peritosegurancadainformacao" target="_blank">Osvaldo Janeri Filho</a></p>
    </div>

    <div id="read" class="tab-content card">
      <h1>Ler QR Code <span class="muted">(client-side)</span></h1>
      <p class="muted">Selecione uma imagem contendo um QR Code de 2FA para extrair o secret. Tudo roda no seu navegador.</p>

      <input type="file" id="fileInput" accept="image/*" />
      <button id="btnRead">Ler QR Code</button>

      <div id="result" class="result" style="display:none;">
        <div id="secretDisplay"></div>
        <div style="margin-top:10px;">
          <button id="btnCopySecret">Copiar Secret</button>
          <button id="btnViewCode">Ver Código</button>
        </div>
      </div>
    </div>

    <div id="about" class="tab-content card">
      <h1>Saiba mais sobre 2FA</h1>
      <p class="muted">A partir de 03/11/2025, os tribunais brasileiros estão exigindo autenticação de dois fatores (2FA) para acesso aos sistemas judiciais. Isso aumenta a segurança e protege suas informações pessoais e profissionais.</p>

      <h2>O que é Autenticação 2FA?</h2>
      <p>A autenticação de dois fatores é uma camada extra de segurança que exige duas formas de verificação antes de conceder acesso a uma conta. Geralmente, combina algo que você sabe (como uma senha) com algo que você possui (como um código gerado por um aplicativo no seu celular).</p>

      <h2>Por que usar este gerador?</h2>
      <p>Em vez de depender exclusivamente de um aparelho celular para gerar códigos 2FA, você pode usar gratuitamente este serviço para criar e gerenciar suas chaves de acesso. Isso oferece flexibilidade e conveniência, especialmente quando você precisa acessar sistemas em diferentes dispositivos.</p>

      <h2>Vantagens e Segurança</h2>
      <ul>
        <li><strong>Gratuito:</strong> Sem custos para gerar e usar códigos 2FA.</li>
        <li><strong>Offline:</strong> Funciona sem conexão com a internet após o carregamento inicial.</li>
        <li><strong>Seguro:</strong> Nada é salvo ou enviado para o servidor. Tudo roda localmente no seu navegador.</li>
        <li><strong>Compatível:</strong> Suporta os padrões RFC 6238 (TOTP) e algoritmos configuráveis.</li>
        <li><strong>Flexível:</strong> Permite personalizar dígitos, período e algoritmo conforme necessário.</li>
      </ul>

      <p>Este gerador é ideal para advogados, peritos e profissionais que precisam acessar sistemas judiciais com segurança adicional, atendendo às novas exigências dos tribunais.</p>
    </div>

    <div id="copyModal" class="modal">
      <div class="modal-content">
        <h3>URL Copiada!</h3>
        <p id="modalUrl"></p>
        <p>Salve nos bookmarks para gerar rapidamente o 2FA.</p>
        <button id="btnCloseModal">Fechar</button>
      </div>
    </div>
  </div>

<script>
// ========= Helpers =========
const b32Alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
function base32Decode(input){
  const clean = (input||'').toUpperCase().replace(/[^A-Z2-7]/g,'');
  let bits = 0, value = 0, out = [];
  for (let i=0;i<clean.length;i++){
    const idx = b32Alphabet.indexOf(clean[i]);
    if (idx < 0) continue;
    value = (value << 5) | idx; bits += 5;
    if (bits >= 8){ bits -= 8; out.push((value >>> bits) & 0xff); }
  }
  return new Uint8Array(out);
}

function toBigEndian8(n){
  // 64-bit counter em 8 bytes big‑endian
  const bytes = new Uint8Array(8);
  const hi = Math.floor(n / 0x100000000);
  const lo = n >>> 0;
  bytes[0]=(hi>>>24)&255; bytes[1]=(hi>>>16)&255; bytes[2]=(hi>>>8)&255; bytes[3]=hi&255;
  bytes[4]=(lo>>>24)&255; bytes[5]=(lo>>>16)&255; bytes[6]=(lo>>>8)&255; bytes[7]=lo&255;
  return bytes;
}

async function hmac(keyBytes, msgBytes, algo){
  const algoName = { 'SHA-1':'SHA-1','SHA-256':'SHA-256','SHA-512':'SHA-512' }[algo] || 'SHA-1';
  const cryptoKey = await crypto.subtle.importKey('raw', keyBytes, {name:'HMAC', hash: algoName}, false, ['sign']);
  const sig = await crypto.subtle.sign('HMAC', cryptoKey, msgBytes);
  return new Uint8Array(sig);
}

function truncateRFC6238(hmacBytes){
  const offset = hmacBytes[hmacBytes.length-1] & 0x0f;
  const p = (hmacBytes[offset] & 0x7f) << 24 |
            (hmacBytes[offset+1] & 0xff) << 16 |
            (hmacBytes[offset+2] & 0xff) << 8 |
            (hmacBytes[offset+3] & 0xff);
  return p >>> 0; // unsigned
}

async function totp({secretB32, period=30, digits=6, algo='SHA-1', t=Date.now()}){
  const key = base32Decode(secretB32);
  if (!key.length) throw new Error('Secret inválido ou vazio');
  const counter = Math.floor((t/1000) / period);
  const h = await hmac(key, toBigEndian8(counter), algo);
  const codeInt = truncateRFC6238(h) % (10 ** digits);
  return { token: String(codeInt).padStart(digits,'0'), counter };
}

// ========= UI =========
const el = id => document.getElementById(id);
const secret = el('secret');
const digits = el('digits');
const period = el('period');
const algo   = el('algo');
const label  = el('label');
const token  = el('token');
const status = el('status');
const bar    = el('bar');
const tleft  = el('timeleft');
const now    = el('now');
const counter= el('counter');
const next   = el('next');
let timer = null;

// Tab switching
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', () => {
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    tab.classList.add('active');
    document.getElementById(tab.dataset.tab).classList.add('active');
  });
});

function getParam(name){
  const params = new URLSearchParams(location.search);
  const v = params.get(name);
  return v ? decodeURIComponent(v) : '';
}

async function renderOnce(){
  try{
    const { token:code, counter:ctr } = await totp({
      secretB32: secret.value.trim(),
      period: parseInt(period.value,10),
      digits: parseInt(digits.value,10),
      algo: algo.value,
      t: Date.now()
    });
    token.textContent = code;
    token.classList.remove('err'); token.classList.add('ok');
    counter.textContent = ctr.toString();
  }catch(e){
    token.textContent = 'SECRET?';
    token.classList.remove('ok'); token.classList.add('err');
  }
}

let lastSec = -1;
function tick(){
  const per = parseInt(period.value,10);
  const nowMs = Date.now();
  const sec = Math.floor(nowMs/1000);
  const rem = per - (sec % per);
  const pct = Math.round(((per - rem) / per) * 100);
  bar.style.width = pct + '%';
  tleft.textContent = `restante: ${rem}s`;
  now.textContent = `${sec} s`;
  next.textContent = `+${rem}s`;
  if (sec !== lastSec && rem === per){ // virou a janela
    renderOnce();
  }
  lastSec = sec;
}

function start(){
  if (timer) return; // já rodando
  status.textContent = 'auto';
  renderOnce();
  timer = setInterval(tick, 200);
}

function stop(){
  if (!timer) return;
  clearInterval(timer); timer=null;
  status.textContent = 'parado';
}

el('btnRun').addEventListener('click', () => { renderOnce(); tick(); });

document.getElementById('btnAuto').addEventListener('click', (ev)=>{
  if (timer){ stop(); ev.target.textContent = 'Auto‑atualizar'; }
  else { start(); ev.target.textContent = 'Parar'; }
});

document.getElementById('btnCopy').addEventListener('click', async () => {
  try{ await navigator.clipboard.writeText(token.textContent.trim()); status.textContent='copiado'; setTimeout(()=>status.textContent='auto', 1000);}catch(e){ status.textContent='falhou'; }
});

document.getElementById('btnCopyUrl').addEventListener('click', async () => {
  const sec = secret.value.trim();
  if (!sec) return;
  const url = window.location.origin + window.location.pathname + '?q=' + encodeURIComponent(sec);
  try {
    await navigator.clipboard.writeText(url);
    document.getElementById('modalUrl').textContent = url;
    document.getElementById('copyModal').classList.add('show');
  } catch (e) {
    alert('Falha ao copiar URL.');
  }
});

document.getElementById('btnCloseModal').addEventListener('click', () => {
  document.getElementById('copyModal').classList.remove('show');
});

// Persistência local simples (apenas no browser)
(function hydrate(){
  try{
    // 1) URL param tem prioridade
    const q = getParam('q');
    if (q){ secret.value = q; }

    // 2) Restaura preferências
    const saved = JSON.parse(localStorage.getItem('mvp_totp')||'{}');
    if (!q && saved.secret) secret.value = saved.secret; // não sobrescreve o que veio por URL
    if (saved.digits) digits.value = saved.digits;
    if (saved.period) period.value = saved.period;
    if (saved.algo)   algo.value   = saved.algo;
    if (saved.label)  label.value  = saved.label;
  }catch{ /* ignore */ }
})();

function persist(){
  const data = { secret: secret.value, digits: digits.value, period: period.value, algo: algo.value, label: label.value };
  try{ localStorage.setItem('mvp_totp', JSON.stringify(data)); }catch{ /* ignore */ }
}
['secret','digits','period','algo','label'].forEach(id=>document.getElementById(id).addEventListener('input', persist));

// QR Code reading functionality
const fileInput = document.getElementById('fileInput');
const btnRead = document.getElementById('btnRead');
const resultDiv = document.getElementById('result');

let currentSecret = '';

btnRead.addEventListener('click', async () => {
  const file = fileInput.files[0];
  if (!file) {
    showResult('Selecione uma imagem primeiro.', 'err');
    return;
  }

  const img = new Image();
  img.onload = () => {
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = img.width;
    canvas.height = img.height;
    ctx.drawImage(img, 0, 0);

    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
    const code = jsQR(imageData.data, imageData.width, imageData.height);

    if (code) {
      const secret = extractSecret(code.data);
      if (secret) {
        currentSecret = secret;
        showResult('Secret: ' + secret, 'ok');
      } else {
        showResult('QR Code não contém um secret válido de 2FA.', 'err');
      }
    } else {
      showResult('QR Code não encontrado na imagem.', 'err');
    }
  };
  img.src = URL.createObjectURL(file);
});

function extractSecret(qrData) {
  // Assume formato otpauth://totp/...secret=...
  const url = new URL(qrData);
  if (url.protocol === 'otpauth:' && url.searchParams.has('secret')) {
    return url.searchParams.get('secret');
  }
  return null;
}

function showResult(text, type) {
  const secretDisplay = document.getElementById('secretDisplay');
  secretDisplay.textContent = text;
  resultDiv.className = 'result ' + type;
  resultDiv.style.display = 'block';
}

document.getElementById('btnCopySecret').addEventListener('click', async () => {
  if (currentSecret) {
    try {
      await navigator.clipboard.writeText(currentSecret);
      alert('Secret copiado para a área de transferência!');
    } catch (e) {
      alert('Falha ao copiar.');
    }
  }
});

document.getElementById('btnViewCode').addEventListener('click', () => {
  if (currentSecret) {
    // Switch to generate tab and set secret
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelector('.tab[data-tab="generate"]').classList.add('active');
    document.getElementById('generate').classList.add('active');
    secret.value = currentSecret;
    renderOnce();
  }
});

// inicial: auto‑atualizar LIGADO por padrão
start();

// Tour guiado
const intro = introJs();
intro.setOptions({
  steps: [
    {
      element: document.querySelector('.tab[data-tab="read"]'),
      intro: "Clique na aba 'Ler QR Code' para começar a ler um QR Code de 2FA."
    },
    {
      element: document.getElementById('fileInput'),
      intro: "Selecione uma imagem que contenha o QR Code do seu 2FA."
    },
    {
      element: document.getElementById('btnRead'),
      intro: "Clique em 'Ler QR Code' para extrair o secret da imagem."
    },
    {
      element: document.getElementById('result'),
      intro: "O secret será exibido aqui. Em seguida, clique em 'Ver Código'."
    },
    {
      element: document.getElementById('btnViewCode'),
      intro: "Clique para alternar para a aba de geração e visualizar o código."
    },
    {
      element: document.getElementById('token'),
      intro: "Aqui você verá o token 2FA gerado automaticamente."
    },
    {
      element: document.getElementById('btnCopyUrl'),
      intro: "Clique para copiar a URL com o secret, facilitando o acesso futuro."
    }
  ],
  showProgress: true,
  showBullets: false
});
document.getElementById('helpBtn').addEventListener('click', () => {
  intro.start();
});
</script>
</body>
</html>
