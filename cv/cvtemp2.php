<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Modern CV Builder</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>  
:root {
  --primary: #2563eb;
  --primary-dark: #1d4ed8;
  --dark-bg: #0f172a;
  --text-main: #334155;
  --text-muted: #64748b;
  --border: #e2e8f0;
  --page-bg: #f8fafc;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  background: var(--page-bg);
  color: var(--text-main);
}

.app {
  display: flex;
  min-height: 100vh;
}

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
  color: var(--dark-bg);
}

.appsub {
  margin: 0 0 20px;
  font-size: 13px;
  color: var(--text-muted);
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
  color: var(--dark-bg);
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
  color: var(--text-muted);
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
  color: var(--text-main);
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
  box-shadow: 0 0 0 3px rgba(37, 99, 235, .15);
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
  transition: background 0.2s;
}

.btnprimary { background: var(--primary); color: #fff; }
.btnprimary:hover { background: var(--primary-dark); }
.btnghost { background: #f1f5f9; color: var(--text-main); }
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

/* --- NEW RESUME STYLING --- */
.pagewrap {
  background: #fff;
  box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
}

#resume {
  width: 794px;
  min-height: 1123px;
  background: #fff;
  color: #334155;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
  display: flex;
  flex-direction: column;
}

/* Resume Header */
.cv-header {
  background: var(--dark-bg);
  color: #fff;
  padding: 36px 40px;
  display: flex;
  align-items: center;
  gap: 28px;
}

.cv-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px solid var(--primary);
  object-fit: cover;
  background: #334155;
}

.cv-avatar-placeholder {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px solid var(--primary);
  background: #1e293b;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  font-size: 12px;
  text-align: center;
}

.cv-header-info { flex: 1; }

.cv-name {
  margin: 0 0 4px;
  font-size: 26px;
  font-weight: 800;
  color: #fff;
  letter-spacing: -0.02em;
}

.cv-title {
  margin: 0 0 14px;
  font-size: 14px;
  color: #93c5fd;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.cv-contact-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  font-size: 12px;
  color: #cbd5e1;
}

.cv-contact-item {
  display: flex;
  align-items: center;
  gap: 6px;
}

.cv-contact-item i { color: var(--primary); }

/* Resume Body */
.cv-body {
  display: flex;
  flex: 1;
}

.cv-main {
  flex: 1;
  padding: 32px 36px;
}

.cv-sidebar {
  width: 260px;
  background: #f8fafc;
  border-left: 1px solid var(--border);
  padding: 32px 24px;
}

.cv-section-title {
  font-size: 13px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--dark-bg);
  border-bottom: 2px solid var(--primary);
  padding-bottom: 6px;
  margin: 0 0 16px;
}

.cv-profile {
  font-size: 12.5px;
  line-height: 1.6;
  color: #475569;
  margin-bottom: 24px;
}

.cv-item { margin-bottom: 18px; }

.cv-item-title {
  font-size: 14px;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 2px;
}

.cv-item-sub {
  font-size: 12px;
  font-weight: 600;
  color: var(--primary);
  margin: 0 0 6px;
}

.cv-item-desc {
  font-size: 12px;
  line-height: 1.5;
  color: #475569;
  margin: 0 0 6px;
}

.cv-tags {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-top: 6px;
}

.cv-tag {
  background: #e0e7ff;
  color: #3730a3;
  font-size: 10.5px;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 4px;
}

.cv-sidebar-block { margin-bottom: 24px; }

.cv-list {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 12px;
}

.cv-list li {
  margin-bottom: 8px;
  color: #334155;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cv-list li i {
  color: var(--primary);
  font-size: 10px;
}

.cv-quote {
  margin-top: auto;
  padding: 16px;
  background: #f1f5f9;
  border-left: 3px solid var(--primary);
  font-style: italic;
  font-size: 11.5px;
  color: #475569;
}

.emptyhint {
  color: #94a3b8;
  font-style: italic;
  font-size: 12px;
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
    <p class="appsub">Fill in your details to auto-generate a modern CV layout.</p>

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
          <div class="frow"><label>Full Name</label><input type="text" id="fullName" placeholder="John Doe"></div>
          <div class="frow"><label>Title / Role</label><input type="text" id="roleTitle" placeholder="Software Engineer"></div>
          <div class="frow"><label>Summary</label><textarea id="profileText" rows="4" placeholder="Brief professional overview..."></textarea></div>
          <div class="frow"><label>Closing Quote</label><input type="text" id="quoteText" placeholder="Inspirational quote..."></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-address-book"></i> Contact Details</summary>
        <div class="fbody">
          <div class="frow"><label>Phone</label><input type="tel" id="cPhone" placeholder="+1 234 567 890"></div>
          <div class="frow"><label>Email</label><input type="email" id="cEmail" placeholder="john@example.com"></div>
          <div class="frow"><label>Location</label><input type="text" id="cLocation" placeholder="New York, USA"></div>
          <div class="frow"><label>LinkedIn</label><input type="text" id="cLinkedin" placeholder="linkedin.com/in/username"></div>
          <div class="frow"><label>GitHub</label><input type="text" id="cGithub" placeholder="github.com/username"></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-graduation-cap"></i> Education</summary>
        <div class="fbody" id="eduContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addEducation()"><i class="fa-solid fa-plus"></i> Add Education</button>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-code"></i> Technical Skills</summary>
        <div class="fbody">
          <div class="frow"><label>Languages (one per line)</label><textarea id="skillLangs" rows="4" placeholder="JavaScript&#10;Python&#10;HTML/CSS"></textarea></div>
          <div class="frow"><label>Tools & Frameworks (one per line)</label><textarea id="skillTools" rows="4" placeholder="React&#10;Node.js&#10;Git"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-people-group"></i> Soft Skills</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="softSkills" rows="4" placeholder="Leadership&#10;Communication"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-language"></i> Languages</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="spokenLangs" rows="3" placeholder="English (Native)&#10;Spanish (Fluent)"></textarea></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-briefcase"></i> Projects</summary>
        <div class="fbody" id="projContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addProject()"><i class="fa-solid fa-plus"></i> Add Project</button>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-certificate"></i> Certifications</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="certs" rows="4" placeholder="AWS Certified Developer&#10;Scrum Master"></textarea></div>
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
        <!-- Header -->
        <div class="cv-header">
          <div id="pvPhotoWrap">
            <img id="pvPhoto" class="cv-avatar" src="" style="display:none;">
            <div id="pvPhotoPlaceholder" class="cv-avatar-placeholder">Photo</div>
          </div>
          <div class="cv-header-info">
            <h1 class="cv-name" id="pvName">John Doe</h1>
            <div class="cv-title" id="pvRole">Software Engineer</div>
            <div class="cv-contact-bar" id="pvContact"></div>
          </div>
        </div>

        <!-- Body -->
        <div class="cv-body">
          <!-- Main Content Area -->
          <div class="cv-main">
            <div class="cv-section-title">Profile</div>
            <p class="cv-profile" id="pvProfile"></p>

            <div class="cv-section-title">Key Projects</div>
            <div id="pvProjects"></div>

            <div class="cv-section-title">Education</div>
            <div id="pvEducation"></div>

            <div id="pvQuoteWrap" class="cv-quote" style="display:none;">
              <span id="pvQuote"></span>
            </div>
          </div>

          <!-- Sidebar Area -->
          <div class="cv-sidebar">
            <div class="cv-sidebar-block">
              <div class="cv-section-title">Languages</div>
              <ul class="cv-list" id="pvSkillLangs"></ul>
            </div>

            <div class="cv-sidebar-block">
              <div class="cv-section-title">Frameworks & Tools</div>
              <ul class="cv-list" id="pvSkillTools"></ul>
            </div>

            <div class="cv-sidebar-block">
              <div class="cv-section-title">Soft Skills</div>
              <ul class="cv-list" id="pvSoft"></ul>
            </div>

            <div class="cv-sidebar-block">
              <div class="cv-section-title">Spoken Languages</div>
              <ul class="cv-list" id="pvSpoken"></ul>
            </div>

            <div class="cv-sidebar-block">
              <div class="cv-section-title">Certifications</div>
              <ul class="cv-list" id="pvCerts"></ul>
            </div>
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
    <div class="frow"><label>Technologies (comma separated)</label><input type="text" class="proj-tech-in" value="${data.tech||''}"></div>
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
  document.getElementById('pvProfile').textContent = val('profileText') || 'Write a brief professional summary to introduce yourself...';
  
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
    `<div class="cv-contact-item"><i class="${r.icon}"></i><span>${escapeHtml(r.v)}</span></div>`
  ).join('') || '<span class="emptyhint">Add contact details</span>';

  const formatList = (text) => linesToArray(text).map(s => `<li><i class="fa-solid fa-circle"></i>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';

  document.getElementById('pvSkillLangs').innerHTML = formatList(val('skillLangs'));
  document.getElementById('pvSkillTools').innerHTML = formatList(val('skillTools'));
  document.getElementById('pvSoft').innerHTML = formatList(val('softSkills'));
  document.getElementById('pvSpoken').innerHTML = formatList(val('spokenLangs'));
  document.getElementById('pvCerts').innerHTML = formatList(val('certs'));

  // Render Education
  const eduCards = document.querySelectorAll('#eduContainer .entrycard');
  let eduHtml = '';
  eduCards.forEach(card => {
    const degree = card.querySelector('.edu-degree-in').value;
    const org = card.querySelector('.edu-org-in').value;
    const year = card.querySelector('.edu-year-in').value;
    if (!degree && !org) return;
    eduHtml += `
    <div class="cv-item">
      <div class="cv-item-title">${escapeHtml(degree)}</div>
      <div class="cv-item-sub">${escapeHtml(org)} ${year ? `• ${escapeHtml(year)}` : ''}</div>
    </div>`;
  });
  document.getElementById('pvEducation').innerHTML = eduHtml || '<p class="emptyhint">Add education details</p>';

  // Render Projects
  const projCards = document.querySelectorAll('#projContainer .entrycard');
  let projHtml = '';
  projCards.forEach(card => {
    const title = card.querySelector('.proj-title-in').value;
    const tech = card.querySelector('.proj-tech-in').value;
    const desc = card.querySelector('.proj-desc-in').value;
    if (!title && !desc) return;
    
    const techBadges = tech ? tech.split(',').map(t => `<span class="cv-tag">${escapeHtml(t.trim())}</span>`).join('') : '';

    projHtml += `
    <div class="cv-item">
      <div class="cv-item-title">${escapeHtml(title)}</div>
      <p class="cv-item-desc">${escapeHtml(desc)}</p>
      ${techBadges ? `<div class="cv-tags">${techBadges}</div>` : ''}
    </div>`;
  });
  document.getElementById('pvProjects').innerHTML = projHtml || '<p class="emptyhint">Add project details</p>';
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
  addEducation();
  addProject();
  bindLiveUpdate();
  renderPreview();
});
</script>
</body>
</html>