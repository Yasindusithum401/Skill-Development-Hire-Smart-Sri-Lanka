<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Executive Modern CV Builder</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>
:root {
  --primary: #0d9488;
  --primary-dark: #0f766e;
  --primary-light: #ccfbf1;
  --slate-dark: #0f172a;
  --slate-muted: #475569;
  --bg-sidebar: #f1f5f9;
  --border: #e2e8f0;
  --page-bg: #f8fafc;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--page-bg);
  color: #334155;
}

.app {
  display: flex;
  min-height: 100vh;
}

/* FORM PANEL */
.formpanel {
  width: 440px;
  flex: 0 0 440px;
  height: 100vh;
  overflow-y: auto;
  position: sticky;
  top: 0;
  padding: 24px;
  background: #fff;
  border-right: 1px solid var(--border);
}

.previewpanel {
  flex: 1;
  height: 100vh;
  overflow-y: auto;
  padding: 32px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 16px;
}

.apptitle {
  margin: 0 0 4px;
  font-size: 20px;
  font-weight: 800;
  color: var(--slate-dark);
}

.appsub {
  margin: 0 0 20px;
  font-size: 13px;
  color: var(--slate-muted);
}

.fsection {
  margin-bottom: 14px;
  border: 1px solid var(--border);
  border-radius: 8px;
  overflow: hidden;
}

.fsection > summary {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 14px;
  background: #f8fafc;
  color: var(--slate-dark);
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  list-style: none;
}

.fsection > summary::-webkit-details-marker { display: none; }

.fsection > summary::after {
  content: '\f107';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  color: var(--primary);
  margin-left: auto;
}

.fsection[open] > summary::after { content: '\f106'; }

.fbody { padding: 14px; }
.frow { margin-bottom: 12px; }

.frow label {
  display: block;
  margin-bottom: 4px;
  font-size: 11px;
  font-weight: 700;
  color: var(--slate-muted);
  text-transform: uppercase;
  letter-spacing: .05em;
}

.frow input, .frow textarea {
  width: 100%;
  padding: 9px 10px;
  border: 1px solid var(--border);
  border-radius: 6px;
  font-family: inherit;
  font-size: 13.5px;
  color: #334155;
  background: #fff;
}

.frow textarea {
  min-height: 56px;
  line-height: 1.4;
  resize: vertical;
}

.frow input:focus, .frow textarea:focus {
  outline: none;
  border-color: var(--primary);
  box-shadow: 0 0 0 3px rgba(13, 148, 136, .15);
}

.twocol { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.photorow { display: flex; align-items: center; gap: 12px; }

.photopreviewmini {
  width: 56px;
  height: 56px;
  flex: 0 0 auto;
  border-radius: 8px;
  border: 2px solid var(--primary);
  background: #eee;
  object-fit: cover;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 9px 14px;
  border: none;
  border-radius: 6px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  transition: all 0.2s;
}

.btnprimary { background: var(--primary); color: #fff; }
.btnprimary:hover { background: var(--primary-dark); }
.btnghost { background: #f1f5f9; color: #334155; }
.btnghost:hover { background: #e2e8f0; }
.btndanger { background: #fef2f2; color: #ef4444; }
.btndanger:hover { background: #fee2e2; }
.btnblock { width: 100%; }
.btnsm { padding: 6px 10px; font-size: 12px; }

.entrycard {
  position: relative;
  margin-bottom: 10px;
  padding: 12px;
  border: 1px dashed var(--border);
  border-radius: 8px;
  background: #fafafa;
}

.entrycard .removeentry { position: absolute; top: 8px; right: 8px; }

.entrytitle {
  margin-bottom: 8px;
  font-size: 12px;
  font-weight: 800;
  color: var(--primary);
  text-transform: uppercase;
  letter-spacing: .03em;
}

.stickyactions {
  position: sticky;
  bottom: 0;
  margin-top: 6px;
  padding-top: 12px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: #fff;
  border-top: 1px solid var(--border);
}

/* --- EXECUTIVE CV STYLING --- */
.pagewrap {
  background: #fff;
  box-shadow: 0 12px 32px rgba(0, 0, 0, .08);
}

#resume {
  width: 794px;
  min-height: 1123px;
  background: #fff;
  color: #334155;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  display: flex;
}

/* Left Sidebar Layout */
.cv-sidebar {
  width: 270px;
  background: var(--bg-sidebar);
  border-right: 1px solid var(--border);
  padding: 36px 24px;
  display: flex;
  flex-direction: column;
}

.cv-avatar-wrap {
  text-align: center;
  margin-bottom: 24px;
}

.cv-avatar {
  width: 120px;
  height: 120px;
  border-radius: 12px;
  border: 3px solid #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  object-fit: cover;
  background: #cbd5e1;
  margin: 0 auto;
}

.cv-avatar-placeholder {
  width: 120px;
  height: 120px;
  border-radius: 12px;
  border: 3px solid #fff;
  box-shadow: 0 4px 12px rgba(0,0,0,0.1);
  background: #cbd5e1;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--slate-muted);
  font-size: 12px;
  margin: 0 auto;
}

.cv-side-block { margin-bottom: 24px; }

.cv-side-title {
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--slate-dark);
  border-bottom: 2px solid var(--primary);
  padding-bottom: 4px;
  margin: 0 0 12px;
}

.cv-contact-list {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 11.5px;
}

.cv-contact-item {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
  color: var(--slate-muted);
  word-break: break-all;
}

.cv-contact-item i {
  width: 16px;
  color: var(--primary);
  font-size: 12px;
  text-align: center;
}

.cv-badge-list {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.cv-badge {
  background: #fff;
  border: 1px solid var(--border);
  color: var(--slate-dark);
  font-size: 11px;
  font-weight: 600;
  padding: 4px 8px;
  border-radius: 4px;
}

.cv-simple-list {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 11.5px;
  color: var(--slate-muted);
}

.cv-simple-list li {
  position: relative;
  padding-left: 14px;
  margin-bottom: 6px;
}

.cv-simple-list li::before {
  content: "•";
  color: var(--primary);
  font-weight: bold;
  position: absolute;
  left: 0;
}

/* Right Main Area Layout */
.cv-main {
  flex: 1;
  padding: 40px 36px;
  display: flex;
  flex-direction: column;
}

.cv-header {
  border-bottom: 2px solid var(--border);
  padding-bottom: 20px;
  margin-bottom: 24px;
}

.cv-name {
  margin: 0 0 4px;
  font-size: 28px;
  font-weight: 800;
  color: var(--slate-dark);
  letter-spacing: -0.02em;
}

.cv-title {
  margin: 0;
  font-size: 15px;
  color: var(--primary);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.cv-section-header {
  display: flex;
  align-items: center;
  gap: 10px;
  margin: 0 0 16px;
}

.cv-section-icon {
  width: 26px;
  height: 26px;
  background: var(--primary-light);
  color: var(--primary);
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
}

.cv-main-title {
  font-size: 14px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--slate-dark);
  margin: 0;
}

.cv-profile {
  font-size: 12.5px;
  line-height: 1.6;
  color: var(--slate-muted);
  margin-bottom: 24px;
}

.cv-timeline {
  border-left: 2px solid var(--border);
  padding-left: 16px;
  margin-left: 6px;
  margin-bottom: 24px;
}

.cv-timeline-item {
  position: relative;
  margin-bottom: 18px;
}

.cv-timeline-item::before {
  content: "";
  position: absolute;
  left: -22px;
  top: 4px;
  width: 10px;
  height: 10px;
  border-radius: 50%;
  background: var(--primary);
  border: 2px solid #fff;
}

.cv-item-title {
  font-size: 13.5px;
  font-weight: 700;
  color: var(--slate-dark);
  margin: 0 0 2px;
}

.cv-item-sub {
  font-size: 11.5px;
  font-weight: 600;
  color: var(--primary);
  margin: 0 0 6px;
}

.cv-item-desc {
  font-size: 12px;
  line-height: 1.5;
  color: var(--slate-muted);
  margin: 0;
}

.cv-quote {
  margin-top: auto;
  padding: 14px 18px;
  background: var(--primary-light);
  border-radius: 8px;
  font-style: italic;
  font-size: 11.5px;
  color: var(--primary-dark);
}

.emptyhint {
  color: #94a3b8;
  font-style: italic;
  font-size: 11.5px;
}

@media (max-width: 1100px) {
  .app { flex-direction: column; }
  .formpanel { width: 100%; height: auto; position: relative; flex: none; }
  .previewpanel { height: auto; }
}
</style>
</head>
<body>
<div class="app">
  <!-- FORM PANEL -->
  <div class="formpanel">
    <p class="apptitle">Modern CV Builder</p>
    <p class="appsub">Fill in your details to live-update your executive layout.</p>

    <form id="cvForm" onsubmit="return false;">
      <details class="fsection" open>
        <summary><i class="fa-solid fa-id-badge"></i> Personal Details</summary>
        <div class="fbody">
          <div class="frow">
            <label>Photo</label>
            <div class="photorow">
              <img id="photoMini" class="photopreviewmini" src="" style="display:none;">
              <input type="file" id="photoInput" accept="image/*">
            </div>
          </div>
          <div class="frow"><label>Full Name</label><input type="text" id="fullName" placeholder="Enter your name "></div>
          <div class="frow"><label>Title / Role</label><input type="text" id="roleTitle" placeholder="e.g:-(Senior Software Engineer)"></div>
          <div class="frow"><label>Summary</label><textarea id="profileText" rows="4" placeholder="Professional profile description..."></textarea></div>
          <div class="frow"><label>Closing Quote</label><input type="text" id="quoteText" placeholder="Inspirational quote..."></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-address-book"></i> Contact Details</summary>
        <div class="fbody">
          <div class="frow"><label>Phone</label><input type="tel" id="cPhone" placeholder="+94 234 567 890"></div>
          <div class="frow"><label>Email</label><input type="email" id="cEmail" placeholder="user@example.com"></div>
          <div class="frow"><label>Location</label><input type="text" id="cLocation" placeholder="colombo/..."></div>
          <div class="frow"><label>LinkedIn</label><input type="text" id="cLinkedin" placeholder="linkedin.com/in/username"></div>
          <div class="frow"><label>GitHub</label><input type="text" id="cGithub" placeholder="github.com/username"></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-code"></i> Technical Skills</summary>
        <div class="fbody">
          <div class="frow"><label>Languages (one per line)</label><textarea id="skillLangs" rows="4" placeholder="JavaScript&#10;Python&#10;C++"></textarea></div>
          <div class="frow"><label>Tools & Frameworks (one per line)</label><textarea id="skillTools" rows="4" placeholder="React&#10;Node.js&#10;Docker"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-people-group"></i> Soft Skills</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="softSkills" rows="3" placeholder="Leadership&#10;Problem Solving"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-language"></i> Languages</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="spokenLangs" rows="3" placeholder="English (Native)&#10;German (Fluent)"></textarea></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-briefcase"></i> Projects</summary>
        <div class="fbody" id="projContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addProject()"><i class="fa-solid fa-plus"></i> Add Project</button>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-graduation-cap"></i> Education</summary>
        <div class="fbody" id="eduContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addEducation()"><i class="fa-solid fa-plus"></i> Add Education</button>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-certificate"></i> Certifications</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="certs" rows="3" placeholder="AWS Certified Architect&#10;Scrum Master"></textarea></div>
        </div>
      </details>
    </form>

    <div class="stickyactions">
      <button type="button" class="btn btnprimary btnblock" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download as PDF</button>
    </div>
  </div>

  <!-- PREVIEW PANEL -->
  <div class="previewpanel">
    <div class="pagewrap">
      <div id="resume">
        <!-- Sidebar -->
        <div class="cv-sidebar">
          <div class="cv-avatar-wrap">
            <img id="pvPhoto" class="cv-avatar" src="" style="display:none;">
            <div id="pvPhotoPlaceholder" class="cv-avatar-placeholder">Photo</div>
          </div>

          <div class="cv-side-block">
            <div class="cv-side-title">Contact</div>
            <ul class="cv-contact-list" id="pvContact"></ul>
          </div>

          <div class="cv-side-block">
            <div class="cv-side-title">Languages & Tools</div>
            <div class="cv-badge-list" id="pvTechSkills"></div>
          </div>

          <div class="cv-side-block">
            <div class="cv-side-title">Soft Skills</div>
            <ul class="cv-simple-list" id="pvSoft"></ul>
          </div>

          <div class="cv-side-block">
            <div class="cv-side-title">Spoken Languages</div>
            <ul class="cv-simple-list" id="pvSpoken"></ul>
          </div>

          <div class="cv-side-block">
            <div class="cv-side-title">Certifications</div>
            <ul class="cv-simple-list" id="pvCerts"></ul>
          </div>
        </div>

        <!-- Main Content -->
        <div class="cv-main">
          <div class="cv-header">
            <h1 class="cv-name" id="pvName">Your Name</h1>
            <div class="cv-title" id="pvRole">Professional Title</div>
          </div>

          <div class="cv-section-header">
            <div class="cv-section-icon"><i class="fa-solid fa-user"></i></div>
            <h2 class="cv-main-title">Profile</h2>
          </div>
          <p class="cv-profile" id="pvProfile"></p>

          <div class="cv-section-header">
            <div class="cv-section-icon"><i class="fa-solid fa-diagram-project"></i></div>
            <h2 class="cv-main-title">Projects</h2>
          </div>
          <div class="cv-timeline" id="pvProjects"></div>

          <div class="cv-section-header">
            <div class="cv-section-icon"><i class="fa-solid fa-graduation-cap"></i></div>
            <h2 class="cv-main-title">Education</h2>
          </div>
          <div class="cv-timeline" id="pvEducation"></div>

          <div id="pvQuoteWrap" class="cv-quote" style="display:none;">
            <span id="pvQuote"></span>
          </div>
        </div>
      </div>
    </div>

    <div style="margin-top:12px;">
      <button type="button" class="btn btnprimary" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download as PDF</button>
    </div>
  </div>
</div>

<script>
let eduCount = 0, projCount = 0;

function eduTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="edu-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('edu-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Education Entry</div>
    <div class="frow"><label>Degree / Qualification</label><input type="text" class="edu-degree-in" value="${data.degree||''}"></div>
    <div class="twocol">
      <div class="frow"><label>Institution</label><input type="text" class="edu-org-in" value="${data.org||''}"></div>
      <div class="frow"><label>Year</label><input type="text" class="edu-year-in" value="${data.year||''}"></div>
    </div>
  </div>`;
}

function addEducation(data) {
  eduCount++;
  document.getElementById('eduContainer').insertAdjacentHTML('beforeend', eduTemplate(eduCount, data));
  bindLiveUpdate();
  renderPreview();
}

function projTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="proj-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('proj-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Project Entry</div>
    <div class="frow"><label>Project Title</label><input type="text" class="proj-title-in" value="${data.title||''}"></div>
    <div class="frow"><label>Technologies</label><input type="text" class="proj-tech-in" value="${data.tech||''}"></div>
    <div class="frow"><label>Description</label><textarea class="proj-desc-in" rows="3">${data.desc||''}</textarea></div>
  </div>`;
}

function addProject(data) {
  projCount++;
  document.getElementById('projContainer').insertAdjacentHTML('beforeend', projTemplate(projCount, data));
  bindLiveUpdate();
  renderPreview();
}

function removeEntry(id) {
  const el = document.getElementById(id);
  if (el) { el.remove(); renderPreview(); }
}

function bindLiveUpdate() {
  document.querySelectorAll('#cvForm input, #cvForm textarea').forEach(el => {
    el.oninput = renderPreview;
  });
}

let photoDataUrl = '';
document.getElementById('photoInput').addEventListener('change', function(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(ev) {
    photoDataUrl = ev.target.result;
    document.getElementById('photoMini').src = photoDataUrl;
    document.getElementById('photoMini').style.display = 'block';
    renderPreview();
  };
  reader.readAsDataURL(file);
});

function linesToArray(text) {
  return (text || '').split('\n').map(s => s.trim()).filter(Boolean);
}

function escapeHtml(str) {
  const d = document.createElement('div');
  d.innerText = str;
  return d.innerHTML;
}

function renderPreview() {
  const val = id => document.getElementById(id).value;

  document.getElementById('pvName').textContent = val('fullName') || 'Your Name';
  document.getElementById('pvRole').textContent = val('roleTitle') || 'Professional Title';
  document.getElementById('pvProfile').textContent = val('profileText') || 'Add a clear summary outlining your experience, key achievements, and career goal...';
  
  const quote = val('quoteText');
  if (quote) {
    document.getElementById('pvQuoteWrap').style.display = 'block';
    document.getElementById('pvQuote').textContent = `"${quote}"`;
  } else {
    document.getElementById('pvQuoteWrap').style.display = 'none';
  }

  if (photoDataUrl) {
    document.getElementById('pvPhoto').src = photoDataUrl;
    document.getElementById('pvPhoto').style.display = 'block';
    document.getElementById('pvPhotoPlaceholder').style.display = 'none';
  } else {
    document.getElementById('pvPhoto').style.display = 'none';
    document.getElementById('pvPhotoPlaceholder').style.display = 'flex';
  }

  const contactRows = [
    { icon: 'fa-solid fa-phone', v: val('cPhone') },
    { icon: 'fa-solid fa-envelope', v: val('cEmail') },
    { icon: 'fa-solid fa-location-dot', v: val('cLocation') },
    { icon: 'fa-brands fa-linkedin', v: val('cLinkedin') },
    { icon: 'fa-brands fa-github', v: val('cGithub') },
  ].filter(r => r.v);

  document.getElementById('pvContact').innerHTML = contactRows.map(r =>
    `<li class="cv-contact-item"><i class="${r.icon}"></i><span>${escapeHtml(r.v)}</span></li>`
  ).join('') || '<li class="emptyhint">Add contact details</li>';

  const formatBadges = (langs, tools) => {
    const list = [...linesToArray(langs), ...linesToArray(tools)];
    return list.map(s => `<span class="cv-badge">${escapeHtml(s)}</span>`).join('') || '<span class="emptyhint">—</span>';
  };
  document.getElementById('pvTechSkills').innerHTML = formatBadges(val('skillLangs'), val('skillTools'));

  const formatSimpleList = (text) => linesToArray(text).map(s => `<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';

  document.getElementById('pvSoft').innerHTML = formatSimpleList(val('softSkills'));
  document.getElementById('pvSpoken').innerHTML = formatSimpleList(val('spokenLangs'));
  document.getElementById('pvCerts').innerHTML = formatSimpleList(val('certs'));

  // Render Projects
  const projCards = document.querySelectorAll('#projContainer .entrycard');
  let projHtml = '';
  projCards.forEach(card => {
    const title = card.querySelector('.proj-title-in').value;
    const tech = card.querySelector('.proj-tech-in').value;
    const desc = card.querySelector('.proj-desc-in').value;
    if (!title && !desc) return;

    projHtml += `
    <div class="cv-timeline-item">
      <div class="cv-item-title">${escapeHtml(title)}</div>
      ${tech ? `<div class="cv-item-sub">Technologies: ${escapeHtml(tech)}</div>` : ''}
      <p class="cv-item-desc">${escapeHtml(desc)}</p>
    </div>`;
  });
  document.getElementById('pvProjects').innerHTML = projHtml || '<p class="emptyhint">Add project details</p>';

  // Render Education
  const eduCards = document.querySelectorAll('#eduContainer .entrycard');
  let eduHtml = '';
  eduCards.forEach(card => {
    const degree = card.querySelector('.edu-degree-in').value;
    const org = card.querySelector('.edu-org-in').value;
    const year = card.querySelector('.edu-year-in').value;
    if (!degree && !org) return;
    eduHtml += `
    <div class="cv-timeline-item">
      <div class="cv-item-title">${escapeHtml(degree)}</div>
      <div class="cv-item-sub">${escapeHtml(org)} ${year ? `| ${escapeHtml(year)}` : ''}</div>
    </div>`;
  });
  document.getElementById('pvEducation').innerHTML = eduHtml || '<p class="emptyhint">Add education details</p>';
}

function downloadPDF() {
  const btns = document.querySelectorAll('.btnprimary');
  btns.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...'; });
  
  const node = document.getElementById('resume');
  html2canvas(node, { scale: 2, useCORS: true, backgroundColor: '#ffffff' }).then(canvas => {
    const imgData = canvas.toDataURL('image/jpeg', 0.98);
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pageW = 210, pageH = 297;
    const canvasRatio = canvas.width / canvas.height;
    let imgW = pageW;
    let imgH = imgW / canvasRatio;

    if (imgH > pageH) {
      imgH = pageH;
      imgW = imgH * canvasRatio;
    }

    const x = (pageW - imgW) / 2;
    pdf.addImage(imgData, 'JPEG', x, 0, imgW, imgH);

    const name = (document.getElementById('fullName').value || 'CV').trim().replace(/\s+/g, '_');
    pdf.save(`${name}_CV.pdf`);

    btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download as PDF'; });
  }).catch(err => {
    console.error(err);
    alert('Failed to generate PDF.');
    btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download as PDF'; });
  });
}

window.addEventListener('DOMContentLoaded', () => {
  addProject();
  addEducation();
  bindLiveUpdate();
  renderPreview();
});
</script>
</body>
</html>