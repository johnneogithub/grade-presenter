<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slideshow — {{ $classroom->course_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { margin: 0; background: #0f172a; font-family: 'Segoe UI', sans-serif; }

        .slide {
            display: none;
            width: 100vw;
            height: 100vh;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
            box-sizing: border-box;
        }
        .slide.active { display: flex; }

        /* Slide 1 — Individual Grades */
        .slide-grades {
            background: linear-gradient(135deg, #1e3a5f 0%, #0f172a 100%);
            color: white;
        }
        /* Slide 2 — Average */
        .slide-average {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            color: white;
        }

        .course-badge {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px;
            padding: 0.4rem 1.2rem;
            font-size: 1rem;
            letter-spacing: 0.05em;
            margin-bottom: 2rem;
            backdrop-filter: blur(4px);
        }

        .student-name {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 0.5rem;
        }

        .student-info {
            font-size: 1.1rem;
            color: rgba(255,255,255,0.6);
            margin-bottom: 3rem;
        }

        .grade-row {
            display: flex;
            gap: 3rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .grade-box {
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 1.5rem;
            padding: 2rem 3rem;
            min-width: 180px;
            backdrop-filter: blur(8px);
        }

        .grade-label {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.5rem;
        }

        .grade-value {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 900;
            color: #60a5fa;
        }

        .average-box {
            background: rgba(255,255,255,0.07);
            border: 2px solid rgba(99, 179, 237, 0.4);
            border-radius: 2rem;
            padding: 3rem 5rem;
            backdrop-filter: blur(8px);
        }

        .average-label {
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255,255,255,0.5);
            margin-bottom: 1rem;
        }

        .average-value {
            font-size: clamp(4rem, 12vw, 7rem);
            font-weight: 900;
            color: #34d399;
            line-height: 1;
        }

        .average-note {
            font-size: 0.85rem;
            color: rgba(255,255,255,0.35);
            margin-top: 0.75rem;
        }

        /* Progress bar */
        #progress-bar-container {
            position: fixed;
            bottom: 0; left: 0; right: 0;
            height: 5px;
            background: rgba(255,255,255,0.1);
            z-index: 100;
        }
        #progress-bar {
            height: 100%;
            background: #60a5fa;
            width: 0%;
            transition: width linear;
        }

        /* Controls */
        #controls {
            position: fixed;
            top: 1rem;
            right: 1rem;
            display: flex;
            gap: 0.5rem;
            z-index: 100;
        }

        #controls button {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: white;
            border-radius: 0.5rem;
            padding: 0.4rem 0.9rem;
            font-size: 0.85rem;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: background 0.2s;
        }
        #controls button:hover { background: rgba(255,255,255,0.2); }

        #slide-counter {
            position: fixed;
            bottom: 1.2rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255,255,255,0.3);
            font-size: 0.8rem;
            z-index: 100;
        }
    </style>
</head>
<body>

    @php
        $students = $students->values();
    @endphp

    @forelse($students as $index => $student)
        {{-- Slide 1: Individual Grades --}}
        <div class="slide slide-grades {{ $index === 0 ? 'active' : '' }}"
             data-slide="{{ $index * 2 }}">
            <div class="course-badge">{{ $classroom->course_name }} &mdash; {{ $classroom->course_code }}</div>
            <div class="student-name">{{ $student->full_name }}</div>
            <div class="student-info">
                {{ $student->student_id }} &nbsp;|&nbsp; {{ $student->course }} &nbsp;|&nbsp; {{ $student->year }} Year &nbsp;|&nbsp; Block {{ $student->block }}
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
                {{ $student->student_id }} &nbsp;|&nbsp; {{ $student->course }} &nbsp;|&nbsp; {{ $student->year }} Year &nbsp;|&nbsp; Block {{ $student->block }}
            </div>
            <div class="average-box">
                <div class="average-label">Midterm Average</div>
                <div class="average-value">
                    @if($student->average_grade !== null)
                        {{ number_format($student->average_grade, 2) }}
                    @else
                        N/A
                    @endif
                </div>
                <div class="average-note">(Laboratory + Lecture) ÷ 2</div>
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
            const totalStudents = Math.floor(totalSlides / 2);
            const pageType = current % 2 === 0 ? 'Grades' : 'Average';
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
            animFrame = requestAnimationFrame(function(now) {
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

        // Start
        showSlide(0);
    </script>
</body>
</html>