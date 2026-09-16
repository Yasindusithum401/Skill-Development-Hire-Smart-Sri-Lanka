<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comprehensive Executive CV Builder</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>
:root {
  --navy: #0f172a;
  --navy-light: #1e293b;
  --gold: #b45309;
  --gold-light: #fef3c7;
  --gold-border: #fde68a;
  --slate-dark: #334155;
  --slate-muted: #64748b;
  --bg-side: #f8fafc;
  --border: #e2e8f0;
  --page-bg: #f1f5f9;
}

* { box-sizing: border-box; }

body {
  margin: 0;
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  background: var(--page-bg);
  color: var(--slate-dark);
}

.app {
  display: flex;
  min-height: 100vh;
}

/* FORM PANEL */
.formpanel {
  width: 460px;
  flex: 0 0 460px;
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
  color: var(--navy);
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
  color: var(--navy);
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
  color: var(--gold);
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
  color: var(--slate-dark);
  background: #fff;
}

.frow textarea {
  min-height: 56px;
  line-height: 1.4;
  resize: vertical;
}

.frow input:focus, .frow textarea:focus {
  outline: none;
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(180, 83, 9, .15);
}

.twocol { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.photorow { display: flex; align-items: center; gap: 12px; }

.photopreviewmini {
  width: 56px;
  height: 56px;
  flex: 0 0 auto;
  border-radius: 8px;
  border: 2px solid var(--gold);
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

.btnprimary { background: var(--navy); color: #fff; }
.btnprimary:hover { background: var(--navy-light); }
.btnghost { background: #f1f5f9; color: var(--slate-dark); }
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
  color: var(--gold);
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

/* --- EXECUTIVE MANAGEMENT CV STYLING --- */
.pagewrap {
  background: #fff;
  box-shadow: 0 12px 32px rgba(0, 0, 0, .12);
}

#resume {
  width: 794px;
  min-height: 1123px;
  background: #fff;
  color: var(--slate-dark);
  font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
  display: flex;
  flex-direction: column;
}

/* Top Banner Header */
.cv-top-banner {
  background: var(--navy);
  color: #fff;
  padding: 32px 36px;
  display: flex;
  align-items: center;
  gap: 24px;
  border-bottom: 4px solid var(--gold);
}

.cv-avatar-wrap { flex: 0 0 auto; }

.cv-avatar {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px solid var(--gold);
  object-fit: cover;
  background: #cbd5e1;
}

.cv-avatar-placeholder {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  border: 3px solid var(--gold);
  background: var(--navy-light);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  font-size: 12px;
}

.cv-banner-info { flex: 1; }

.cv-name {
  margin: 0 0 4px;
  font-size: 28px;
  font-weight: 800;
  color: #fff;
  letter-spacing: -0.01em;
}

.cv-title {
  margin: 0 0 12px;
  font-size: 14px;
  color: var(--gold-light);
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
}

.cv-banner-contact {
  display: flex;
  flex-wrap: wrap;
  gap: 12px 16px;
  font-size: 11px;
  color: #cbd5e1;
}

.cv-banner-contact div {
  display: flex;
  align-items: center;
  gap: 6px;
}

.cv-banner-contact i {
  color: var(--gold-border);
}

/* Two Column Body Layout */
.cv-body {
  display: flex;
  flex: 1;
}

.cv-sidebar {
  width: 250px;
  background: var(--bg-side);
  border-right: 1px solid var(--border);
  padding: 28px 20px;
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.cv-main {
  flex: 1;
  padding: 28px 28px;
  display: flex;
  flex-direction: column;
}

/* KPI Highlights */
.cv-kpi-bar {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 12px;
  margin-bottom: 24px;
}

.cv-kpi-box {
  background: var(--gold-light);
  border: 1px solid var(--gold-border);
  border-radius: 6px;
  padding: 10px;
  text-align: center;
}

.cv-kpi-val {
  font-size: 15px;
  font-weight: 800;
  color: var(--gold);
}

.cv-kpi-lbl {
  font-size: 10px;
  text-transform: uppercase;
  font-weight: 700;
  color: var(--slate-dark);
}

/* Section Titles */
.cv-side-title {
  font-size: 12px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--navy);
  border-bottom: 2px solid var(--gold);
  padding-bottom: 4px;
  margin: 0 0 10px;
}

.cv-main-title {
  font-size: 13.5px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--navy);
  border-bottom: 2px solid var(--navy);
  padding-bottom: 4px;
  margin: 0 0 14px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.cv-main-title i { color: var(--gold); }

/* Sidebar Lists */
.cv-simple-list {
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 11.5px;
  color: var(--slate-dark);
}

.cv-simple-list li {
  position: relative;
  padding-left: 12px;
  margin-bottom: 6px;
}

.cv-simple-list li::before {
  content: "■";
  color: var(--gold);
  font-size: 8px;
  position: absolute;
  left: 0;
  top: 2px;
}

.cv-badge-list {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
}

.cv-badge {
  background: #fff;
  border: 1px solid var(--border);
  color: var(--navy);
  font-size: 10.5px;
  font-weight: 700;
  padding: 4px 8px;
  border-radius: 4px;
}

/* Main Timeline Elements */
.cv-profile-text {
  font-size: 12.5px;
  line-height: 1.6;
  color: var(--slate-dark);
  margin-bottom: 20px;
}

.cv-block { margin-bottom: 22px; }

.cv-item {
  margin-bottom: 14px;
  padding-left: 12px;
  border-left: 2px solid var(--gold);
}

.cv-item-header {
  display: flex;
  justify-content: space-between;
  align-items: baseline;
}

.cv-item-title {
  font-size: 13.5px;
  font-weight: 800;
  color: var(--navy);
}

.cv-item-sub {
  font-size: 11.5px;
  font-weight: 700;
  color: var(--gold);
}

.cv-item-date {
  font-size: 11px;
  font-weight: 600;
  color: var(--slate-muted);
}

.cv-item-desc {
  font-size: 12px;
  line-height: 1.5;
  color: var(--slate-dark);
  margin: 4px 0 0;
}

.emptyhint {
  color: #94a3b8;
  font-style: italic;
  font-size: 11px;
}

.ref-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.ref-card {
  font-size: 11.5px;
  line-height: 1.4;
  background: var(--bg-side);
  padding: 8px;
  border-radius: 4px;
  border: 1px solid var(--border);
}

.ref-name { font-weight: 800; color: var(--navy); }
.ref-title { font-weight: 600; color: var(--gold); }

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
    <p class="apptitle">Executive CV Builder</p>
    <p class="appsub">Fill in your details to create a full professional resume.</p>

    <form id="cvForm" onsubmit="return false;">
      <!-- 1. Executive Profile -->
      <details class="fsection" open>
        <summary><i class="fa-solid fa-user-tie"></i> Executive Profile</summary>
        <div class="fbody">
          <div class="frow">
            <label>Photo</label>
            <div class="photorow">
              <img id="photoMini" class="photopreviewmini" src="" style="display:none;">
              <input type="file" id="photoInput" accept="image/*">
            </div>
          </div>
          <div class="frow"><label>Full Name</label><input type="text" id="fullName" value="" placeholder="e.g. USER NAME "></div>
          <div class="frow"><label>Target Role / Title</label><input type="text" id="roleTitle" value="" placeholder="e.g. Operations Manager"></div>
          <div class="frow"><label>Executive Summary</label><textarea id="profileText" rows="4" placeholder="Brief professional summary..."></textarea></div>
        </div>
      </details>

      <!-- 2. Contact Information -->
      <details class="fsection" open>
        <summary><i class="fa-solid fa-address-book"></i> Contact Information</summary>
        <div class="fbody">
          <div class="frow"><label>Phone</label><input type="tel" id="cPhone" value="" placeholder="+94 7X XXX XXXX"></div>
          <div class="frow"><label>Email</label><input type="email" id="cEmail" value="" placeholder="name@example.com"></div>
          <div class="frow"><label>Location</label><input type="text" id="cLocation" value="" placeholder="Colombo, Sri Lanka"></div>
          <div class="frow"><label>LinkedIn</label><input type="text" id="cLinkedin" value="" placeholder="linkedin.com/in/username"></div>
          <div class="frow"><label>Portfolio / Website</label><input type="text" id="cWebsite" value="" placeholder="github.com or website.com"></div>
        </div>
      </details>

      <!-- 3. Key Metrics / Highlights -->
      <details class="fsection">
        <summary><i class="fa-solid fa-chart-line"></i> Strategic Impact Highlights</summary>
        <div class="fbody">
          <div class="frow"><label>Metric 1 (Value | Label)</label><input type="text" id="kpi1" value="" placeholder="10+ | Projects Led"></div>
          <div class="frow"><label>Metric 2 (Value | Label)</label><input type="text" id="kpi2" value="" placeholder="$1M+ | Revenue Generated"></div>
          <div class="frow"><label>Metric 3 (Value | Label)</label><input type="text" id="kpi3" value="" placeholder="25% | Efficiency Gain"></div>
        </div>
      </details>

      <!-- 4. Work Experience -->
      <details class="fsection" open>
        <summary><i class="fa-solid fa-briefcase"></i> Work Experience</summary>
        <div class="fbody" id="expContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addExperience()"><i class="fa-solid fa-plus"></i> Add Position</button>
        </div>
      </details>

      <!-- 5. Education -->
      <details class="fsection" open>
        <summary><i class="fa-solid fa-graduation-cap"></i> Education & Academic Qualifications</summary>
        <div class="fbody" id="eduContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addEducation()"><i class="fa-solid fa-plus"></i> Add Degree / Qualification</button>
        </div>
      </details>

      <!-- 6. Key Projects / Initiatives -->
      <details class="fsection">
        <summary><i class="fa-solid fa-diagram-project"></i> Key Projects & Initiatives</summary>
        <div class="fbody" id="projContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addProject()"><i class="fa-solid fa-plus"></i> Add Project</button>
        </div>
      </details>

      <!-- 7. Core Competencies & Skills -->
      <details class="fsection">
        <summary><i class="fa-solid fa-list-check"></i> Skills & Competencies</summary>
        <div class="fbody">
          <div class="frow"><label>Technical / Core Skills (one per line)</label><textarea id="mgtSkills" rows="4" placeholder="Strategic Planning&#10;Process Optimization&#10;Financial Analysis"></textarea></div>
          <div class="frow"><label>Leadership / Soft Skills (one per line)</label><textarea id="softSkills" rows="3" placeholder="Team Leadership&#10;Negotiation&#10;Conflict Resolution"></textarea></div>
        </div>
      </details>

      <!-- 8. Certifications -->
      <details class="fsection">
        <summary><i class="fa-solid fa-certificate"></i> Certifications & Training</summary>
        <div class="fbody">
          <div class="frow"><label>Certifications (one per line)</label><textarea id="certs" rows="3" placeholder="PMP - Project Management Professional&#10;Lean Six Sigma Green Belt"></textarea></div>
        </div>
      </details>

      <!-- 9. Languages -->
      <details class="fsection">
        <summary><i class="fa-solid fa-language"></i> Languages Spoken</summary>
        <div class="fbody">
          <div class="frow"><label>Languages (one per line)</label><textarea id="languages" rows="2" placeholder="English (Fluent)&#10;Sinhala (Native)"></textarea></div>
        </div>
      </details>

      <!-- 10. References -->
      <details class="fsection">
        <summary><i class="fa-solid fa-user-group"></i> Non-Related Referees</summary>
        <div class="fbody" id="refContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addReferee()"><i class="fa-solid fa-plus"></i> Add Referee</button>
        </div>
      </details>

      <!-- 11. Custom Additional Section -->
      <details class="fsection">
        <summary><i class="fa-solid fa-folder-plus"></i> Add Custom Section</summary>
        <div class="fbody">
          <div class="frow"><label>Section Title</label><input type="text" id="customTitle" value="" placeholder="e.g. Publications / Honors / Hobbies"></div>
          <div class="frow"><label>Section Content (one per line or paragraph)</label><textarea id="customContent" rows="3" placeholder="Details..."></textarea></div>
        </div>
      </details>
    </form>

    <div class="stickyactions">
      <button type="button" class="btn btnprimary btnblock" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download Management CV</button>
    </div>
  </div>

  <!-- PREVIEW PANEL -->
  <div class="previewpanel">
    <div class="pagewrap">
      <div id="resume">
        <!-- Header Banner -->
        <div class="cv-top-banner">
          <div class="cv-avatar-wrap">
            <img id="pvPhoto" class="cv-avatar" src="" style="display:none;">
            <div id="pvPhotoPlaceholder" class="cv-avatar-placeholder">Photo</div>
          </div>
          <div class="cv-banner-info">
            <h1 class="cv-name" id="pvName">Your Name</h1>
            <div class="cv-title" id="pvRole">Your Job Title</div>
            <div class="cv-banner-contact" id="pvContact"></div>
          </div>
        </div>

        <!-- Body Layout -->
        <div class="cv-body">
          <!-- Sidebar -->
          <div class="cv-sidebar">
            <div id="secMgtSkills">
              <div class="cv-side-title">Core Skills</div>
              <div class="cv-badge-list" id="pvMgtSkills"></div>
            </div>

            <div id="secSoft">
              <div class="cv-side-title">Leadership</div>
              <ul class="cv-simple-list" id="pvSoft"></ul>
            </div>

            <div id="secCerts">
              <div class="cv-side-title">Certifications</div>
              <ul class="cv-simple-list" id="pvCerts"></ul>
            </div>

            <div id="secLanguages">
              <div class="cv-side-title">Languages</div>
              <ul class="cv-simple-list" id="pvLanguages"></ul>
            </div>
          </div>

          <!-- Main Content -->
          <div class="cv-main">
            <!-- Strategic Highlights -->
            <div class="cv-kpi-bar" id="pvKPIs"></div>

            <!-- Profile Summary -->
            <div id="secProfile">
              <div class="cv-main-title"><i class="fa-solid fa-user-gear"></i> Executive Profile</div>
              <p class="cv-profile-text" id="pvProfile"></p>
            </div>

            <!-- Experience -->
            <div class="cv-block" id="secExperience">
              <div class="cv-main-title"><i class="fa-solid fa-briefcase"></i> Professional Experience</div>
              <div id="pvExperience"></div>
            </div>

            <!-- Education -->
            <div class="cv-block" id="secEducation">
              <div class="cv-main-title"><i class="fa-solid fa-graduation-cap"></i> Education & Qualifications</div>
              <div id="pvEducation"></div>
            </div>

            <!-- Projects -->
            <div class="cv-block" id="secProjects">
              <div class="cv-main-title"><i class="fa-solid fa-diagram-project"></i> Key Projects & Initiatives</div>
              <div id="pvProjects"></div>
            </div>

            <!-- Custom Section -->
            <div class="cv-block" id="secCustom" style="display:none;">
              <div class="cv-main-title" id="pvCustomTitle"><i class="fa-solid fa-star"></i> Additional Information</div>
              <ul class="cv-simple-list" id="pvCustomContent"></ul>
            </div>

            <!-- Referees -->
            <div class="cv-block" id="secReferees">
              <div class="cv-main-title"><i class="fa-solid fa-user-group"></i> References</div>
              <div id="pvReferees"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div style="margin-top:12px;">
      <button type="button" class="btn btnprimary" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download Management CV</button>
    </div>
  </div>
</div>

<script>
let eduCount = 0, expCount = 0, projCount = 0, refCount = 0;

// Dynamic Templates
function expTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="exp-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('exp-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Experience Entry</div>
    <div class="frow"><label>Role / Position</label><input type="text" class="exp-title-in" value="${data.title||''}" placeholder="Job Title"></div>
    <div class="twocol">
      <div class="frow"><label>Company / Org</label><input type="text" class="exp-org-in" value="${data.org||''}" placeholder="Company Name"></div>
      <div class="frow"><label>Duration</label><input type="text" class="exp-date-in" value="${data.date||''}" placeholder="2022 - Present"></div>
    </div>
    <div class="frow"><label>Responsibilities & Achievements</label><textarea class="exp-desc-in" rows="3" placeholder="Key accomplishments...">${data.desc||''}</textarea></div>
  </div>`;
}

function eduTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="edu-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('edu-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Education Entry</div>
    <div class="frow"><label>Degree / Qualification</label><input type="text" class="edu-degree-in" value="${data.degree||''}" placeholder="e.g. BSc in Business Administration"></div>
    <div class="twocol">
      <div class="frow"><label>University / Institute</label><input type="text" class="edu-org-in" value="${data.org||''}" placeholder="University Name"></div>
      <div class="frow"><label>Year</label><input type="text" class="edu-year-in" value="${data.year||''}" placeholder="2018 - 2022"></div>
    </div>
  </div>`;
}

function projTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="proj-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('proj-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Project Entry</div>
    <div class="frow"><label>Project Name</label><input type="text" class="proj-title-in" value="${data.title||''}" placeholder="e.g. Digital Transformation Project"></div>
    <div class="frow"><label>Scope / Impact Summary</label><textarea class="proj-desc-in" rows="2" placeholder="Briefly describe project outcome...">${data.desc||''}</textarea></div>
  </div>`;
}

function refTemplate(id, data = {}) {
  return `
  <div class="entrycard" id="ref-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('ref-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Referee Entry</div>
    <div class="frow"><label>Full Name</label><input type="text" class="ref-name-in" value="${data.name||''}" placeholder="Dr. / Mr. Name"></div>
    <div class="frow"><label>Designation & Org</label><input type="text" class="ref-title-in" value="${data.title||''}" placeholder="Senior Manager, XYZ Corp"></div>
    <div class="twocol">
      <div class="frow"><label>Phone</label><input type="text" class="ref-phone-in" value="${data.phone||''}" placeholder="+94 7X XXX XXXX"></div>
      <div class="frow"><label>Email</label><input type="text" class="ref-email-in" value="${data.email||''}" placeholder="referee@example.com"></div>
    </div>
  </div>`;
}

// Add/Remove Handlers
function addExperience(data) {
  expCount++;
  document.getElementById('expContainer').insertAdjacentHTML('beforeend', expTemplate(expCount, data));
  bindLiveUpdate(); renderPreview();
}

function addEducation(data) {
  eduCount++;
  document.getElementById('eduContainer').insertAdjacentHTML('beforeend', eduTemplate(eduCount, data));
  bindLiveUpdate(); renderPreview();
}

function addProject(data) {
  projCount++;
  document.getElementById('projContainer').insertAdjacentHTML('beforeend', projTemplate(projCount, data));
  bindLiveUpdate(); renderPreview();
}

function addReferee(data) {
  refCount++;
  document.getElementById('refContainer').insertAdjacentHTML('beforeend', refTemplate(refCount, data));
  bindLiveUpdate(); renderPreview();
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

// Photo upload
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
  d.innerText = str || '';
  return d.innerHTML;
}

// Main Render Function
function renderPreview() {
  const val = id => document.getElementById(id).value;

  document.getElementById('pvName').textContent = val('fullName') || 'Your Name';
  document.getElementById('pvRole').textContent = val('roleTitle') || 'Your Job Title';
  document.getElementById('pvProfile').textContent = val('profileText') || 'Your professional summary will be displayed here...';

  if (photoDataUrl) {
    document.getElementById('pvPhoto').src = photoDataUrl;
    document.getElementById('pvPhoto').style.display = 'block';
    document.getElementById('pvPhotoPlaceholder').style.display = 'none';
  } else {
    document.getElementById('pvPhoto').style.display = 'none';
    document.getElementById('pvPhotoPlaceholder').style.display = 'flex';
  }

  // Contact
  const contactRows = [
    { icon: 'fa-solid fa-phone', v: val('cPhone') },
    { icon: 'fa-solid fa-envelope', v: val('cEmail') },
    { icon: 'fa-solid fa-location-dot', v: val('cLocation') },
    { icon: 'fa-brands fa-linkedin', v: val('cLinkedin') },
    { icon: 'fa-solid fa-globe', v: val('cWebsite') }
  ].filter(r => r.v);

  document.getElementById('pvContact').innerHTML = contactRows.map(r =>
    `<div><i class="${r.icon}"></i><span>${escapeHtml(r.v)}</span></div>`
  ).join('') || '<div>Contact details will appear here</div>';

  // Metrics
  const kpis = [val('kpi1'), val('kpi2'), val('kpi3')].filter(Boolean);
  document.getElementById('pvKPIs').innerHTML = kpis.map(k => {
    const parts = k.split('|');
    return `<div class="cv-kpi-box"><div class="cv-kpi-val">${escapeHtml(parts[0]?parts[0].trim():'')}</div><div class="cv-kpi-lbl">${escapeHtml(parts[1]?parts[1].trim():'')}</div></div>`;
  }).join('');

  // Skills & Sidebar
  const formatBadges = (text) => linesToArray(text).map(s => `<span class="cv-badge">${escapeHtml(s)}</span>`).join('') || '<span class="emptyhint">—</span>';
  const formatSimpleList = (text) => linesToArray(text).map(s => `<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';

  document.getElementById('pvMgtSkills').innerHTML = formatBadges(val('mgtSkills'));
  document.getElementById('pvSoft').innerHTML = formatSimpleList(val('softSkills'));
  document.getElementById('pvCerts').innerHTML = formatSimpleList(val('certs'));
  document.getElementById('pvLanguages').innerHTML = formatSimpleList(val('languages'));

  // Experience
  const expCards = document.querySelectorAll('#expContainer .entrycard');
  let expHtml = '';
  expCards.forEach(card => {
    const title = card.querySelector('.exp-title-in').value;
    const org = card.querySelector('.exp-org-in').value;
    const date = card.querySelector('.exp-date-in').value;
    const desc = card.querySelector('.exp-desc-in').value;
    if (!title && !org) return;

    expHtml += `
    <div class="cv-item">
      <div class="cv-item-header">
        <div class="cv-item-title">${escapeHtml(title)}</div>
        <div class="cv-item-date">${escapeHtml(date)}</div>
      </div>
      <div class="cv-item-sub">${escapeHtml(org)}</div>
      <p class="cv-item-desc">${escapeHtml(desc)}</p>
    </div>`;
  });
  document.getElementById('pvExperience').innerHTML = expHtml || '<p class="emptyhint">Add work experience entries</p>';

  // Education
  const eduCards = document.querySelectorAll('#eduContainer .entrycard');
  let eduHtml = '';
  eduCards.forEach(card => {
    const degree = card.querySelector('.edu-degree-in').value;
    const org = card.querySelector('.edu-org-in').value;
    const year = card.querySelector('.edu-year-in').value;
    if (!degree && !org) return;

    eduHtml += `
    <div class="cv-item">
      <div class="cv-item-header">
        <div class="cv-item-title">${escapeHtml(degree)}</div>
        <div class="cv-item-date">${escapeHtml(year)}</div>
      </div>
      <div class="cv-item-sub">${escapeHtml(org)}</div>
    </div>`;
  });
  document.getElementById('pvEducation').innerHTML = eduHtml || '<p class="emptyhint">Add education details</p>';

  // Projects
  const projCards = document.querySelectorAll('#projContainer .entrycard');
  let projHtml = '';
  projCards.forEach(card => {
    const title = card.querySelector('.proj-title-in').value;
    const desc = card.querySelector('.proj-desc-in').value;
    if (!title) return;

    projHtml += `
    <div class="cv-item">
      <div class="cv-item-title">${escapeHtml(title)}</div>
      <p class="cv-item-desc">${escapeHtml(desc)}</p>
    </div>`;
  });
  document.getElementById('pvProjects').innerHTML = projHtml || '<p class="emptyhint">Add key projects if applicable</p>';

  // Referees
  const refCards = document.querySelectorAll('#refContainer .entrycard');
  let refHtml = '';
  if (refCards.length > 0) {
    refHtml = '<div class="ref-grid">';
    refCards.forEach(card => {
      const name = card.querySelector('.ref-name-in').value;
      const title = card.querySelector('.ref-title-in').value;
      const phone = card.querySelector('.ref-phone-in').value;
      const email = card.querySelector('.ref-email-in').value;
      if (!name) return;

      refHtml += `
      <div class="ref-card">
        <div class="ref-name">${escapeHtml(name)}</div>
        <div class="ref-title">${escapeHtml(title)}</div>
        <div>${escapeHtml(phone)}</div>
        <div>${escapeHtml(email)}</div>
      </div>`;
    });
    refHtml += '</div>';
  } else {
    refHtml = '<p class="emptyhint" style="font-size:11.5px; font-style:italic;">Available upon request</p>';
  }
  document.getElementById('pvReferees').innerHTML = refHtml;

  // Custom Section
  const cTitle = val('customTitle');
  const cContent = val('customContent');
  if (cTitle || cContent) {
    document.getElementById('secCustom').style.display = 'block';
    document.getElementById('pvCustomTitle').innerHTML = `<i class="fa-solid fa-star"></i> ${escapeHtml(cTitle || 'Additional Information')}`;
    document.getElementById('pvCustomContent').innerHTML = formatSimpleList(cContent);
  } else {
    document.getElementById('secCustom').style.display = 'none';
  }
}

// Download PDF
function downloadPDF() {
  const btns = document.querySelectorAll('.btnprimary');
  btns.forEach(b => { b.disabled = true; b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating PDF...'; });

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

    const name = (document.getElementById('fullName').value || 'Executive_CV').trim().replace(/\s+/g, '_');
    pdf.save(`${name}.pdf`);

    btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download Management CV'; });
  }).catch(err => {
    console.error(err);
    alert('Failed to generate PDF.');
    btns.forEach(b => { b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download Management CV'; });
  });
}

window.addEventListener('DOMContentLoaded', () => {
  bindLiveUpdate();
  renderPreview();
});
</script>
</body>
</html>