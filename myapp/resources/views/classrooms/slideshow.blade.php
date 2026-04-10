<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slideshow — {{ $classroom->course_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #0f172a;
            font-family: 'Segoe UI', sans-serif;
        }

        /* ── Themes ── */
        body.theme-ocean {
            --bg1: #1e3a5f;
            --bg2: #0f172a;
            --bg3: #1a1a2e;
            --bg4: #0f3460;
            --accent: #60a5fa;
            --avg: #34d399;
        }

        body.theme-forest {
            --bg1: #14532d;
            --bg2: #052e16;
            --bg3: #1a2e1a;
            --bg4: #15803d;
            --accent: #4ade80;
            --avg: #fbbf24;
        }

        body.theme-sunset {
            --bg1: #7c2d12;
            --bg2: #1c0a03;
            --bg3: #2d1a05;
            --bg4: #9a3412;
            --accent: #fb923c;
            --avg: #f472b6;
        }

        body.theme-royal {
            --bg1: #3730a3;
            --bg2: #1e1b4b;
            --bg3: #2e1065;
            --bg4: #4c1d95;
            --accent: #a78bfa;
            --avg: #34d399;
        }

        body.theme-mono {
            --bg1: #1f2937;
            --bg2: #030712;
            --bg3: #111827;
            --bg4: #374151;
            --accent: #e5e7eb;
            --avg: #ffffff;
        }

        .slide {
            display: none;
            width: 100vw;
            height: 100vh;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }

        .slide.active {
            display: flex;
        }

        .slide-grades {
            background: linear-gradient(135deg, var(--bg1) 0%, var(--bg2) 100%);
            color: white;
        }

        .slide-average {
            background: linear-gradient(135deg, var(--bg3) 0%, var(--bg4) 100%);
            color: white;
        }

        .course-badge {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            padding: .4rem 1.2rem;
            font-size: 1rem;
            letter-spacing: .05em;
            margin-bottom: 2rem;
        }

        .student-name {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: .5rem;
        }

        .student-info {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, .6);
            margin-bottom: 3rem;
        }

        .grade-row {
            display: flex;
            gap: 3rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .grade-box {
            background: rgba(255, 255, 255, .07);
            border: 1px solid rgba(255, 255, 255, .15);
            border-radius: 1.5rem;
            padding: 2rem 3rem;
            min-width: 180px;
        }

        .grade-label {
            font-size: .85rem;
            text-transform: uppercase;
            letter-spacing: .1em;
            color: rgba(255, 255, 255, .5);
            margin-bottom: .5rem;
        }

        .grade-weight {
            font-size: .75rem;
            color: rgba(255, 255, 255, .3);
            margin-top: .25rem;
        }

        .grade-value {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            color: var(--accent);
        }

        .average-box {
            background: rgba(255, 255, 255, .07);
            border: 2px solid rgba(99, 179, 237, .4);
            border-radius: 2rem;
            padding: 3rem 5rem;
        }

        .average-label {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: rgba(255, 255, 255, .5);
            margin-bottom: 1rem;
        }

        .average-value {
            font-size: clamp(4rem, 12vw, 7rem);
            font-weight: 900;
            color: var(--avg);
            line-height: 1;
        }

        .average-note {
            font-size: .85rem;
            color: rgba(255, 255, 255, .35);
            margin-top: .75rem;
        }

        /* Progress bar */
        #progress-bar-container {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: rgba(255, 255, 255, .1);
            z-index: 100;
        }

        #progress-bar {
            height: 100%;
            background: var(--accent);
            width: 0%;
        }

        /* Controls */
        #controls {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: .5rem;
            z-index: 100;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        #controls button,
        #controls select {
            background: rgba(255, 255, 255, .1);
            border: 1px solid rgba(255, 255, 255, .2);
            color: white;
            border-radius: .5rem;
            padding: .4rem .9rem;
            font-size: .85rem;
            cursor: pointer;
            backdrop-filter: blur(4px);
        }

        #controls button:hover {
            background: rgba(255, 255, 255, .2);
        }

        #controls select option {
            color: #1f2937;
            background: white;
        }

        #slide-counter {
            position: fixed;
            bottom: 1.2rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, .3);
            font-size: .8rem;
            z-index: 100;
        }
    </style>
</head>

<body class="theme-ocean">

    @php $students = $students->values(); @endphp

    @forelse($students as $index => $student)
        {{-- Slide 1: Individual Grades --}}
        <div class="slide slide-grades {{ $index === 0 ? 'active' : '' }}" data-slide="{{ $index * 2 }}">
            <div class="course-badge">{{ $classroom->course_name }} &mdash; {{ $classroom->course_code }}</div>
            <div class="student-name">{{ $student->full_name }}</div>
            <div class="student-info">
                {{ $student->student_id }} &nbsp;|&nbsp; {{ $student->course }} &nbsp;|&nbsp; {{ $student->year }} Year
                &nbsp;|&nbsp; Block {{ $student->block }}
            </div>
            <div class="grade-row">
                <div class="grade-box">
                    <div class="grade-label">Laboratory Grade</div>
                    <div class="grade-value">{{ $student->laboratory_grade ?? '—' }}</div>
                </div>
                <div class="grade-box">
                    <div class="grade-label">Lecture Grade</div>
                    <div class="grade-value">{{ $student->lecture_grade ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Slide 2: Average --}}
        <div class="slide slide-average" data-slide="{{ $index * 2 + 1 }}">
            <div class="course-badge">{{ $classroom->course_name }} &mdash; {{ $classroom->course_code }}</div>
            <div class="student-name">{{ $student->full_name }}</div>
            <div class="student-info">
                {{ $student->student_id }} &nbsp;|&nbsp; {{ $student->course }} &nbsp;|&nbsp; {{ $student->year }} Year
                &nbsp;|&nbsp; Block {{ $student->block }}
            </div>
            <div class="average-box">
                <div class="average-label">Midterm Average</div>
                <div class="average-value">
                    @if($student->final_grade !== null)
                        {{ number_format($student->final_grade, 2) }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="average-note">(Lecture × 0.40 + Laboratory × 0.60)</div>
            </div>
        </div>
    @empty
        <div class="slide slide-grades active">
            <p style="color:white; font-size:1.5rem;">No students in this classroom yet.</p>
            <a href="{{ route('classrooms.show', $classroom) }}" style="color:#60a5fa; margin-top:1rem;">Go back</a>
        </div>
    @endforelse

    {{-- Controls --}}
    <div id="controls">
        <select id="theme-select" onchange="changeTheme(this.value)">
            <option value="theme-ocean">🌊 Ocean</option>
            <option value="theme-forest">🌿 Forest</option>
            <option value="theme-sunset">🌅 Sunset</option>
            <option value="theme-royal">👑 Royal</option>
            <option value="theme-mono">⬛ Mono</option>
        </select>
        <button onclick="prevSlide()">◀ Prev</button>
        <button onclick="togglePause()" id="pause-btn">⏸ Pause</button>
        <button onclick="nextSlide()">Next ▶</button>
        <a href="{{ route('classrooms.show', $classroom) }}">
            <button>✕ Exit</button>
        </a>
    </div>

    <div id="slide-counter"></div>

    <div id="progress-bar-container">
        <div id="progress-bar"></div>
    </div>

    <script>
        const SLIDE_DURATION = 15000; // 15 seconds
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        let current = 0;
        let paused = false;
        let startTime = null;
        let elapsed = 0;
        let animFrame = null;

        function showSlide(index) {
            slides.forEach(s => s.classList.remove('active'));
            slides[index].classList.add('active');
            updateCounter();
            resetProgress();
        }

        function nextSlide() {
            current = (current + 1) % totalSlides;
            showSlide(current);
        }

        function prevSlide() {
            current = (current - 1 + totalSlides) % totalSlides;
            showSlide(current);
        }

        function togglePause() {
            paused = !paused;
            document.getElementById('pause-btn').textContent = paused ? '▶ Resume' : '⏸ Pause';
            if (!paused) {
                startTime = performance.now() - elapsed;
                animateProgress();
            } else {
                cancelAnimationFrame(animFrame);
            }
        }

        function updateCounter() {
            const studentNum = Math.floor(current / 2) + 1;
            const totalStudents = Math.ceil(totalSlides / 2);
            const pageType = current % 2 === 0 ? 'Grades' : 'Final Grade';
            document.getElementById('slide-counter').textContent =
                `Student ${studentNum} of ${totalStudents} — ${pageType}`;
        }

        function resetProgress() {
            elapsed = 0;
            startTime = performance.now();
            cancelAnimationFrame(animFrame);
            if (!paused) animateProgress();
        }

        function animateProgress() {
            animFrame = requestAnimationFrame(function (now) {
                elapsed = now - startTime;
                const pct = Math.min((elapsed / SLIDE_DURATION) * 100, 100);
                document.getElementById('progress-bar').style.width = pct + '%';
                if (elapsed >= SLIDE_DURATION) {
                    nextSlide();
                } else {
                    animateProgress();
                }
            });
        }

        function changeTheme(theme) {
            document.body.className = theme;
        }

        // Start
        showSlide(0);
    </script>
</body>

</html>