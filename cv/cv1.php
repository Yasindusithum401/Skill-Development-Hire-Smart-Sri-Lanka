<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>CV Builder Create &amp; Download Your Resume</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<style>   
:root {
  --orange: #f2811d;
  --dark: #1e1e1e;
  --text: #2b2b2b;
  --muted: #6b6b6b;
  --border: #e3e3e3;
  --page-bg: #f2f3f5;
}

* {
  box-sizing: border-box;
}

body {
  margin: 0;
  font-family: 'Segoe UI', Arial, sans-serif;
  background: var(--page-bg);
  color: var(--text);
}

/*Page layout*/
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
  color: var(--dark);
}
.appsub {
  margin: 0 0 20px;
  font-size: 13px;
  color: var(--muted);
}
/* Form controls */
.fsection {
  margin-bottom: 14px;
  border: 1px solid var(--border);
  border-radius: 10px;
  overflow: hidden;
}
.fsection > summary {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 12px 14px;
  background: #faf7f4;
  color: var(--dark);
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  list-style: none;
}
.fsection > summary::-webkit-details-marker {
  display: none;
}
.fsection > summary::after {
  content: '\f107';
  font-family: 'Font Awesome 6 Free';
  font-weight: 900;
  color: var(--orange);
  margin-left: auto;
}
.fsection[open] > summary::after {
  content: '\f106';
}
.fbody {
  padding: 14px;
}
.frow {
  margin-bottom: 12px;
}
.frow label {
  display: block;
  margin-bottom: 4px;
  font-size: 12px;
  font-weight: 700;
  color: var(--muted);
  text-transform: uppercase;
  letter-spacing: .02em;
}
.frow input,
.frow textarea {
  width: 100%;
  padding: 9px 10px;
  border: 1px solid var(--border);
  border-radius: 7px;
  font-family: inherit;
  font-size: 13.5px;
  color: var(--text);
  background: #fff;
}
.frow textarea {
  min-height: 56px;
  line-height: 1.4;
  resize: vertical;
}
.frow input:focus,
.frow textarea:focus {
  outline: none;
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(242, 129, 29, .15);
}
.twocol {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
}
.photorow {
  display: flex;
  align-items: center;
  gap: 12px;
}
.photopreviewmini {
  width: 56px;
  height: 56px;
  flex: 0 0 auto;
  border-radius: 50%;
  border: 2px solid var(--orange);
  background: #eee;
  object-fit: cover;
}
/* Buttons*/
.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 6px;
  padding: 9px 14px;
  border: none;
  border-radius: 7px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
}
.btnorange { 
    background: var(--orange);
    color: #fff;
}
.btnorange:hover { 
    background: #d76f10; 
}

.btnghost { 
    background: #f1f1f1; 
    color: var(--text);
}
.btnghost:hover { 
    background: #e6e6e6; 
}

.btndanger { 
    background: #fdeceb; 
    color: #c0392b; 
}
.btndanger:hover { 
    background: #fbdbd8; 
}

.btnblock { 
    width: 100%; 
}
.btnsm { 
    padding: 6px 10px; font-size: 12px; 
}
.entrycard {
  position: relative;
  margin-bottom: 10px;
  padding: 12px;
  border: 1px dashed var(--border);
  border-radius: 8px;
  background: #fcfcfc;
}
.entrycard .removeentry {
  position: absolute;
  top: 8px;
  right: 8px;
}
.entrytitle {
  margin-bottom: 8px;
  font-size: 12px;
  font-weight: 800;
  color: var(--orange);
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
.topactions {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  justify-content: center;
}
/*A4 preview eka */
.pagewrap {
  background: #fff;
  box-shadow: 0 6px 24px rgba(0, 0, 0, .12);
}
#resume {
  width: 794px;
  min-height: 1123px;
  display: flex;
  position: relative;
  background: #fff;
  color: #222;
  font-family: 'Segoe UI', Arial, sans-serif;
}
/*Sidebar*/
.sidebar {
  width: 270px;
  flex: 0 0 270px;
  position: relative;
  overflow: hidden;
  padding: 32px 22px;
  background: var(--dark);
  color: #fff;
}
.cornerflag {
  position: absolute;
  top: 0;
  left: 0;
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 80px 90px 0 0;
  border-color: var(--orange) transparent transparent transparent;
}
.avatarwrap {
  width: 190px;
  height: 190px;
  margin: 0 auto 18px;
  border-radius: 50%;
  border: 4px solid #fff;
  overflow: hidden;
  background: #555;
  position: relative;
  z-index: 2;
}
.avatarwrap img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
}
.avatarwrap .placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #999;
  font-size: 13px;
  text-align: center;
}
.sidename {
  margin: 0 0 2px;
  font-size: 19px;
  font-weight: 800;
  letter-spacing: .02em;
  position: relative;
  z-index: 2;
}
.sidetitle {
  margin: 0 0 14px;
  font-size: 11.5px;
  letter-spacing: .09em;
  color: #cfcfcf;
  text-transform: uppercase;
}
.sidehr {
  width: 46px;
  height: 3px;
  margin: 0 0 18px;
  background: var(--orange);
}
.sideblock {
  margin-bottom: 22px;
}
.sideh {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 10px;
}
.sideh .ic {
  width: 24px;
  height: 24px;
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--orange);
  font-size: 11px;
}
.sideh span {
  font-size: 12.5px;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .06em;
}
.contactline {
  display: flex;
  align-items: flex-start;
  gap: 9px;
  margin-bottom: 9px;
  font-size: 11.5px;
  line-height: 1.4;
  color: #e7e7e7;
  word-break: break-word;
}
.contactline i {
  width: 14px;
  margin-top: 1px;
  color: var(--orange);
}
.skillcat {
  margin: 10px 0 6px;
  font-size: 11px;
  font-weight: 800;
  color: var(--orange);
  text-transform: uppercase;
  letter-spacing: .05em;
}
.skillcat:first-child {
  margin-top: 0;
}
.sidelist {
  margin: 0;
  padding: 0;
  list-style: none;
}
.sidelist li {
  margin-bottom: 5px;
  font-size: 12px;
  color: #e2e2e2;
}
/* Main content column */
.main {
  flex: 1;
  min-width: 0;
  padding: 32px 34px;
}
.mh {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 0 0 14px;
}
.mh:not(:first-child) {
  margin-top: 22px;
}
.mh .ic {
  width: 30px;
  height: 30px;
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: var(--orange);
  color: #fff;
  font-size: 13px;
}
.mh .txt {
  font-size: 15.5px;
  font-weight: 800;
  letter-spacing: .03em;
  color: #222;
  white-space: nowrap;
}
.mh .line {
  flex: 1;
  height: 1px;
  position: relative;
  background: #e3e3e3;
}
.mh .line::after {
  content: '';
  position: absolute;
  top: -3px;
  right: 0;
  width: 7px;
  height: 7px;
  border-radius: 50%;
  background: var(--orange);
}
.profiletext {
  margin: 0 0 4px;
  font-size: 12.6px;
  line-height: 1.65;
  color: #444;
}
/* Education */
.eduitem {
  display: flex;
  gap: 12px;
  margin-bottom: 14px;
}
.edudot {
  width: 16px;
  height: 16px;
  flex: 0 0 auto;
  margin-top: 3px;
  border: 3px solid var(--orange);
  border-radius: 50%;
}
.edudegree { 
    margin: 0 0 2px; 
    font-size: 13.5px; 
    font-weight: 800; 
    color: #222; 
}
.eduorg { 
    margin: 0 0 2px; 
    font-size: 12px; 
    color: #555; 
}
.eduyear { 
    margin: 0 0 6px; 
    font-size: 12px; 
    font-weight: 700; 
    color: var(--orange); 
}
.educourselabel { 
    margin: 4px 0 3px; 
    font-size: 11.5px; 
    font-weight: 700; 
    color: #333; 
}
.educourselist { 
    font-size: 11.5px; 
    line-height: 1.6; 
    color: #555; 
}
.projitem {
  display: flex;
  gap: 14px;
  margin-bottom: 18px;
}
.projicon {
  width: 58px;
  height: 58px;
  flex: 0 0 auto;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 2px solid var(--orange);
  border-radius: 8px;
  color: var(--orange);
  font-size: 22px;
}
.projtitle { 
    margin: 0 0 2px; 
    font-size: 13.5px; 
    font-weight: 800; 
    color: #222; 
}
.projtech { 
    margin: 0 0 5px; 
    font-size: 11.5px; 
    font-weight: 700; 
    color: var(--orange); 
}
.projdesc { 
    margin: 0 0 8px; 
    font-size: 11.8px; 
    line-height: 1.55; 
    color: #555; 
}
.projfeatures {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 4px 14px;
}
.projfeatures div {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11.3px;
  color: #444;
}
.projfeatures i {
  font-size: 10px;
  color: var(--orange);
}
.certgrid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 10px;
}
.certchip {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 9px 10px;
  border: 1px solid #e3e3e3;
  border-radius: 8px;
  font-size: 11.8px;
  font-weight: 700;
  color: #333;
}
.certchip i {
  font-size: 14px;
  color: #3aa856;
}
.intgrid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  text-align: center;
}
.intitem i {
  display: block;
  margin-bottom: 6px;
  font-size: 20px;
  color: var(--orange);
}
.intitem span {
  font-size: 11px;
  font-weight: 700;
  color: #333;
}
.quotebar {
  display: flex;
  align-items: center;
  gap: 16px;
  margin-top: 26px;
  padding: 16px 22px;
  background: #f4f4f4;
}
.quotebar .qmark {
  font-size: 26px;
  color: var(--orange);
}
.quotebar .qtext {
  flex: 1;
  font-size: 11.8px;
  font-style: italic;
  color: #444;
}
.emptyhint {
  color: #bbb;
  font-style: italic;
}
/* ---- tablet ---- */
@media (max-width: 1100px) {
  .app {
    flex-direction: column;
  }
  .formpanel {
    width: 100%;
    height: auto;
    position: relative;
    flex: none;
  }
  .previewpanel {
    height: auto;
  }
}
/*mobile*/
@media (max-width: 768px) {
  body{
    overflow-x: hidden;
  }
  .app{
    flex-direction: column;
  }
  .formpanel{
    width: 100%;
    flex: none;
    height: auto;
    position: relative;
    padding: 18px;
    border-right: none;
    border-bottom: 1px solid var(--border);
  }
  .previewpanel{
    width: 100%;
    height: auto;
    padding: 15px;
    overflow-x: auto;
    align-items: flex-start;
  }
  .topactions{
    flex-direction: column;
    width: 100%;
  }
  .topactions .btn{
    width: 100%;
  }
  .stickyactions{
    position: relative;
  }
  .twocol{
    grid-template-columns: 1fr;
  }
  .photorow{
    flex-direction: column;
    align-items: flex-start;
  }
  .pagewrap{
    width: 100%;
    overflow-x: auto;
  }
  #resume{
    width: 794px;
    min-width: 794px;
    transform-origin: top left;
  }
  .apptitle{
    font-size:18px;
  }
  .appsub{
    font-size:12px;
  }
  .frow input,
  .frow textarea{
    font-size:14px;
  }
}
</style>
</head>
<body>


<div class="app">
  <!--FORM PANEL-->
  <div class="formpanel">
    <p class="apptitle"> Online CV Builder</p>
    <p class="appsub">Fill in your details. The preview on the right updates live then download it as a PDF.</p>

    <form id="cvForm" onsubmit="return false;">

      <details class="fsection" open>
        <summary><i class="fa-solid fa-id-badge"></i> Basic Info &amp; Photo</summary>
        <div class="fbody">
          <div class="frow">
            <label>Photo</label>
            <div class="photorow">
              <img id="photoMini" class="photopreviewmini" src="" style="display:none;">
              <input type="file" id="photoInput" accept="image/*">
            </div>
          </div>
          <div class="frow"><label>Full Name</label><input type="text" id="fullName" value="" placeholder="Enter Name"></div>
          <div class="frow"><label>Title / Role</label><input type="text" id="roleTitle" value="" placeholder="Enter Degree"></div>
          <div class="frow"><label>Professional Profile (summary)</label>
            <textarea spellcheck="true" id="profileText" rows="5"></textarea>
          </div>
          <div class="frow"><label>Closing Quote</label>
          <input type="text" spellcheck="true" id="quoteText" value=""></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-address-book"></i> Contact</summary>
        <div class="fbody">
          <div class="frow"><label>Phone</label><input type="tel" id="cPhone" value=""></div>
          <div class="frow"><label>Email</label><input type="email" id="cEmail" value=""></div>
          <div class="frow"><label>Location</label><input type="text" id="cLocation" value=""></div>
          <div class="frow"><label>LinkedIn</label><input type="text" id="cLinkedin" value=""></div>
          <div class="frow"><label>GitHub</label><input type="text" id="cGithub" value=""></div>
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
          <div class="frow"><label>Languages (one per line)</label><textarea spellcheck="true" id="skillLangs" rows="6"></textarea></div>
          <div class="frow"><label>Frameworks &amp; Tools (one per line)</label><textarea spellcheck="true" id="skillTools" rows="6"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-people-group"></i> Soft Skills</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea spellcheck="true" id="softSkills" rows="6"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-language"></i> Languages Spoken</summary>
        <div class="fbody">
          <div class="frow"><label>One per line  "Language  Proficiency"</label><textarea spellcheck="true" id="spokenLangs" rows="3"></textarea></div>
        </div>
      </details>

      <details class="fsection" open>
        <summary><i class="fa-solid fa-briefcase"></i> Academic Projects</summary>
        <div class="fbody" id="projContainer"></div>
        <div style="padding:0 14px 14px;">
          <button type="button" class="btn btnghost btnblock btnsm" onclick="addProject()"><i class="fa-solid fa-plus"></i> Add Project</button>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-certificate"></i> Certifications</summary>
        <div class="fbody">
          <div class="frow"><label>One per line</label><textarea id="certs" rows="5"></textarea></div>
        </div>
      </details>

      <details class="fsection">
        <summary><i class="fa-solid fa-star"></i> Interests</summary>
        <div class="fbody">
          <div class="frow"><label>One per line  "icon-keyword | Label" (icon keywords: code, design, web, ai, opensource, music, sports, travel, book, camera, game)</label>
            <textarea id="interests" rows="6"></textarea>
          </div>
        </div>
      </details>

    </form>

    <div class="stickyactions">
      <button type="button" class="btn btnorange btnblock" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download as PDF</button>
    </div>
  </div>

  <!-- PREVIEW PANEL  -->
  <div class="previewpanel">
    <div class="topactions">
      <span style="font-size:12px;color:#888;align-self:center;">Live preview (A4)  updates as you type</span>
    </div>
    <div class="pagewrap">
      <div id="resume">
        <div class="sidebar">
          <div class="cornerflag"></div>
          <div class="avatarwrap">
            <img id="pvPhoto" src="" style="display:none;">
            <div id="pvPhotoPlaceholder" class="placeholder">Your Photo</div>
          </div>
          <h1 class="sidename" id="pvNameSide"></h1>
          <p class="sidetitle" id="pvRoleSide"></p>
          <div class="sidehr"></div>

          <div class="sideblock">
            <div class="sideh"><span class="ic"><i class="fa-solid fa-user"></i></span><span>Contact</span></div>
            <div id="pvContact"></div>
          </div>

          <div class="sideblock">
            <div class="sideh"><span class="ic"><i class="fa-solid fa-gear"></i></span><span>Technical Skills</span></div>
            <div class="skillcat">Languages</div>
            <ul class="sidelist" id="pvSkillLangs"></ul>
            <div class="skillcat">Frameworks &amp; Tools</div>
            <ul class="sidelist" id="pvSkillTools"></ul>
          </div>

          <div class="sideblock">
            <div class="sideh"><span class="ic"><i class="fa-solid fa-people-group"></i></span><span>Soft Skills</span></div>
            <ul class="sidelist" id="pvSoft"></ul>
          </div>

          <div class="sideblock">
            <div class="sideh"><span class="ic"><i class="fa-solid fa-globe"></i></span><span>Languages</span></div>
            <ul class="sidelist" id="pvSpoken"></ul>
          </div>
        </div>

        <div class="main">
          <div class="mh"><span class="ic"><i class="fa-solid fa-user"></i></span><span class="txt">PROFESSIONAL PROFILE</span><span class="line"></span></div>
          <p class="profiletext" id="pvProfile"></p>

          <div class="mh"><span class="ic"><i class="fa-solid fa-graduation-cap"></i></span><span class="txt">EDUCATION</span><span class="line"></span></div>
          <div id="pvEducation"></div>

          <div class="mh"><span class="ic"><i class="fa-solid fa-briefcase"></i></span><span class="txt">ACADEMIC PROJECTS</span><span class="line"></span></div>
          <div id="pvProjects"></div>

          <div class="mh"><span class="ic"><i class="fa-solid fa-award"></i></span><span class="txt">CERTIFICATIONS</span><span class="line"></span></div>
          <div class="certgrid" id="pvCerts"></div>

          <div class="mh"><span class="ic"><i class="fa-solid fa-star"></i></span><span class="txt">INTERESTS</span><span class="line"></span></div>
          <div class="intgrid" id="pvInterests"></div>

          <div class="quotebar">
            <span class="qmark">&#8220;</span>
            <span class="qtext" id="pvQuote"></span>
          </div>
        </div>
      </div>
    </div>

    <div class="topactions" style="margin-top:6px;">
      <button type="button" class="btn btnorange" onclick="downloadPDF()"><i class="fa-solid fa-file-arrow-down"></i> Download as PDF</button>
    </div>
  </div>
</div>
<script>
    
const ICON_MAP = {
  code:'fa-solid fa-code', design:'fa-solid fa-pen-ruler', web:'fa-solid fa-globe',
  ai:'fa-solid fa-brain', opensource:'fa-brands fa-github', music:'fa-solid fa-music',
  sports:'fa-solid fa-futbol', travel:'fa-solid fa-plane', book:'fa-solid fa-book',
  camera:'fa-solid fa-camera', game:'fa-solid fa-gamepad'
};

let eduCount = 0, projCount = 0;

function eduTemplate(id, data){
  data = data || {};
  return `
  <div class="entrycard" id="edu-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('edu-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Education Entry</div>
    <div class="frow"><label>Degree / Qualification</label><input type="text" class="edu-degree-in" value="${data.degree||''}"></div>
    <div class="twocol">
      <div class="frow"><label>Institution</label><input type="text" class="edu-org-in" value="${data.org||''}"></div>
      <div class="frow"><label>Year / Expected Graduation</label><input type="text" class="edu-year-in" value="${data.year||''}"></div>
    </div>
    <div class="frow"><label>Relevant Coursework (one per line, leave empty if none)</label><textarea class="edu-course-in" rows="3">${data.course||''}</textarea></div>
  </div>`;
}

function addEducation(data){
  eduCount++;
  document.getElementById('eduContainer').insertAdjacentHTML('beforeend', eduTemplate(eduCount, data));
  bindLiveUpdate();
  renderPreview();
}

function projTemplate(id, data){
  data = data || {};
  return `
  <div class="entrycard" id="proj-${id}">
    <button type="button" class="btn btndanger btnsm removeentry" onclick="removeEntry('proj-${id}')"><i class="fa-solid fa-xmark"></i></button>
    <div class="entrytitle">Project Entry</div>
    <div class="frow"><label>Project Title</label><input type="text" class="proj-title-in" value="${data.title||''}"></div>
    <div class="frow"><label>Technologies (comma separated)</label><input type="text" class="proj-tech-in" value="${data.tech||''}"></div>
    <div class="frow"><label>Description</label><textarea class="proj-desc-in" rows="3">${data.desc||''}</textarea></div>
    <div class="frow"><label>Key Features (one per line, optional)</label><textarea class="proj-feat-in" rows="4">${data.feat||''}</textarea></div>
  </div>`;
}

function addProject(data){
  projCount++;
  document.getElementById('projContainer').insertAdjacentHTML('beforeend', projTemplate(projCount, data));
  bindLiveUpdate();
  renderPreview();
}

function removeEntry(id){
  const el = document.getElementById(id);
  if(el){ el.remove(); renderPreview(); }
}

/* Live update */
function bindLiveUpdate(){
  document.querySelectorAll('#cvForm input, #cvForm textarea').forEach(el=>{
    el.oninput = renderPreview;
  });
}

let photoDataUrl = '';
document.getElementById('photoInput').addEventListener('change', function(e){
  const file = e.target.files[0];
  if(!file) return;
  const reader = new FileReader();
  reader.onload = function(ev){
    photoDataUrl = ev.target.result;
    document.getElementById('photoMini').src = photoDataUrl;
    document.getElementById('photoMini').style.display = 'block';
    renderPreview();
  };
  reader.readAsDataURL(file);
});

function linesToArray(text){
  return (text||'').split('\n').map(s=>s.trim()).filter(Boolean);
}
function escapeHtml(str){
  const d = document.createElement('div');
  d.innerText = str;
  return d.innerHTML;
}
/* Main render function */
function renderPreview(){
  const val = id => document.getElementById(id).value;

  document.getElementById('pvNameSide').textContent = val('fullName') || 'Enter Name';
  document.getElementById('pvRoleSide').textContent = val('roleTitle') || 'Enter Degree';
  document.getElementById('pvProfile').textContent = val('profileText');
  document.getElementById('pvQuote').textContent = val('quoteText');

  if(photoDataUrl){
    document.getElementById('pvPhoto').src = photoDataUrl;
    document.getElementById('pvPhoto').style.display = 'block';
    document.getElementById('pvPhotoPlaceholder').style.display = 'none';
  } else {
    document.getElementById('pvPhoto').style.display = 'none';
    document.getElementById('pvPhotoPlaceholder').style.display = 'flex';
  }

  const contactRows = [
    {icon:'fa-solid fa-phone', v: val('cPhone')},
    {icon:'fa-solid fa-envelope', v: val('cEmail')},
    {icon:'fa-solid fa-location-dot', v: val('cLocation')},
    {icon:'fa-brands fa-linkedin', v: val('cLinkedin')},
    {icon:'fa-brands fa-github', v: val('cGithub')},
  ].filter(r=>r.v);
  document.getElementById('pvContact').innerHTML = contactRows.map(r=>
    `<div class="contactline"><i class="${r.icon}"></i><span>${escapeHtml(r.v)}</span></div>`
  ).join('') || '<div class="contactline emptyhint">Add your contact details</div>';

  document.getElementById('pvSkillLangs').innerHTML = linesToArray(val('skillLangs')).map(s=>`<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';
  document.getElementById('pvSkillTools').innerHTML = linesToArray(val('skillTools')).map(s=>`<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';
  document.getElementById('pvSoft').innerHTML = linesToArray(val('softSkills')).map(s=>`<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';
  document.getElementById('pvSpoken').innerHTML = linesToArray(val('spokenLangs')).map(s=>`<li>${escapeHtml(s)}</li>`).join('') || '<li class="emptyhint">—</li>';

  const eduCards = document.querySelectorAll('#eduContainer .entrycard');
  let eduHtml = '';
  eduCards.forEach(card=>{
    const degree = card.querySelector('.edu-degree-in').value;
    const org = card.querySelector('.edu-org-in').value;
    const year = card.querySelector('.edu-year-in').value;
    const course = linesToArray(card.querySelector('.edu-course-in').value);
    if(!degree && !org) return;
    eduHtml += `
    <div class="eduitem">
      <div class="edudot"></div>
      <div>
        <p class="edudegree">${escapeHtml(degree)}</p>
        <p class="eduorg">${escapeHtml(org)}</p>
        ${year? `<p class="eduyear">${escapeHtml(year)}</p>`:''}
        ${course.length? `<p class="educourselabel">Relevant Coursework:</p><p class="educourselist">${course.map(escapeHtml).join(' &nbsp;•&nbsp; ')}</p>`:''}
      </div>
    </div>`;
  });
  document.getElementById('pvEducation').innerHTML = eduHtml || '<p class="emptyhint">Add your education details</p>';

  const projCards = document.querySelectorAll('#projContainer .entrycard');
  let projHtml = '';
  projCards.forEach(card=>{
    const title = card.querySelector('.proj-title-in').value;
    const tech = card.querySelector('.proj-tech-in').value;
    const desc = card.querySelector('.proj-desc-in').value;
    const feats = linesToArray(card.querySelector('.proj-feat-in').value);
    if(!title && !desc) return;
    projHtml += `
    <div class="projitem">
      <div class="projicon"><i class="fa-solid fa-diagram-project"></i></div>
      <div>
        <p class="projtitle">${escapeHtml(title)}</p>
        ${tech? `<p class="projtech">Technologies: ${escapeHtml(tech)}</p>`:''}
        <p class="projdesc">${escapeHtml(desc)}</p>
        ${feats.length? `<div class="projfeatures">${feats.map(f=>`<div><i class="fa-solid fa-check"></i>${escapeHtml(f)}</div>`).join('')}</div>`:''}
      </div>
    </div>`;
  });
  document.getElementById('pvProjects').innerHTML = projHtml || '<p class="emptyhint">Add your academic projects</p>';

  const certList = linesToArray(val('certs'));
  document.getElementById('pvCerts').innerHTML = certList.map(c=>
    `<div class="certchip"><i class="fa-solid fa-circle-check"></i>${escapeHtml(c)}</div>`
  ).join('') || '<p class="emptyhint">Add certifications</p>';

  const intList = linesToArray(val('interests'));
  document.getElementById('pvInterests').innerHTML = intList.map(line=>{
    const [key, label] = line.split('|').map(s=>s && s.trim());
    const iconClass = ICON_MAP[key] || 'fa-solid fa-star';
    return `<div class="intitem"><i class="${iconClass}"></i><span>${escapeHtml(label||key||'')}</span></div>`;
  }).join('') || '<p class="emptyhint">Add interests</p>';
}

/* PDF Download  eka */
function downloadPDF(){
  const btns = document.querySelectorAll('.btnorange');
  btns.forEach(b=>{ b.disabled = true; b.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Generating...'; });
  const node = document.getElementById('resume');
  html2canvas(node, {scale:2, useCORS:true, backgroundColor:'#ffffff'}).then(canvas=>{
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
    const y = 0;
    pdf.addImage(imgData, 'JPEG', x, y, imgW, imgH);

    const name = (document.getElementById('fullName').value || 'CV').trim().replace(/\s+/g,'_');
    pdf.save(`${name}_CV.pdf`);

    btns.forEach(b=>{ b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download as PDF'; });
  }).catch(err=>{
    console.error(err);
    alert('Something went wrong generating the PDF. Please try again.');
    btns.forEach(b=>{ b.disabled = false; b.innerHTML = '<i class="fa-solid fa-file-arrow-down"></i> Download as PDF'; });
  });
}

window.addEventListener('DOMContentLoaded', ()=>{
  addEducation();
  addProject();

  bindLiveUpdate();
  renderPreview();
});
</script>
</body>
</html>