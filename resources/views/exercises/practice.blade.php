@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width:720px;">
  <h4 class="mb-2">Practice pronouncing</h4>

  <form id="practice-form" class="mb-3">
    @csrf
    <label class="form-label">Target</label>
    <input id="target" name="target" class="form-control mb-2" value="The cat sat on the mat." required>

    <div class="d-flex gap-2 mb-2">
      <button class="btn btn-secondary" type="button" id="start">🎙️ Start</button>
      <button class="btn btn-outline-secondary" type="button" id="stop" disabled>⏹ Stop</button>
      <button class="btn btn-primary" type="submit" id="submit" disabled>Submit</button>
    </div>
  </form>

  <div id="result" class="border rounded p-3 bg-light" style="min-height:140px;"></div>
</div>

<script>
let mediaRecorder, chunks=[];
const startBtn = document.getElementById('start');
const stopBtn  = document.getElementById('stop');
const submitBtn= document.getElementById('submit');
const resultEl = document.getElementById('result');

startBtn.onclick = async () => {
  try {
    const stream = await navigator.mediaDevices.getUserMedia({ audio:true });
    mediaRecorder = new MediaRecorder(stream);
    chunks=[];
    mediaRecorder.ondataavailable = e => chunks.push(e.data);
    mediaRecorder.onstop = () => { submitBtn.disabled = (chunks.length===0); };
    mediaRecorder.start();
    startBtn.disabled=true; stopBtn.disabled=false; resultEl.textContent='Recording...';
  } catch(err) {
    resultEl.innerHTML = `<div class="text-danger">Microphone error: ${err.message}</div>`;
  }
};

stopBtn.onclick = () => {
  if (mediaRecorder) mediaRecorder.stop();
  startBtn.disabled=false; stopBtn.disabled=true; resultEl.textContent='Recorded. Click Submit.';
};

document.getElementById('practice-form').addEventListener('submit', async (e)=>{
  e.preventDefault();
  resultEl.textContent='Analyzing...';

  const blob = new Blob(chunks, { type:'audio/webm' });
  const fd = new FormData();
  fd.append('audio', blob, 'audio.webm');
  fd.append('target', document.getElementById('target').value);

  const res = await fetch('{{ route('practice.score') }}', {
    method:'POST',
    headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body:fd
  });

  let data;
  try { data = await res.json(); } catch(e) {
    const text = await res.text();
    resultEl.innerHTML = `<div class="text-danger">Server returned non-JSON (${res.status}).<br>${text}</div>`;
    return;
  }

  if (!res.ok) {
    resultEl.innerHTML = `<div class="text-danger">Error: ${data.error || 'Unknown error'} (HTTP ${res.status})</div>`;
    return;
  }

  resultEl.innerHTML = `
    <div><strong>Heard:</strong> ${data.transcript || '(none)'}</div>
    <div><strong>Accuracy:</strong> ${Math.round((data.accuracy||0)*100)}%</div>
    <div><strong>Result:</strong> ${data.passed ? '✅ Correct' : '❌ Try again'}</div>
    <div class="mt-2"><strong>Feedback:</strong> ${data.feedback}</div>
    ${data.diff_html || ''}
  `;
});
</script>
@endsection
