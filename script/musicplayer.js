let sound;
let updatedInterval;
let isPlaying = false; 
let isSeeking = false;

// Equalizer variables
let analyser;
let bufferLength;
let dataArray;
let animationFrame; 

// equalizer
let canvas = document.getElementById('equalizer');
let ctx = canvas.getContext('2d');

// Volume slider 
const volumeSlider = document.getElementById('volumeSlider');

function loadSong(songSrc) {
    console.log("Loading song:", songSrc);
    resetPlayer();


    if (sound) {
        sound.stop();
        sound.unload();
        cancelAnimationFrame(animationFrame);
    }

    sound = new Howl({
        src: [songSrc],
        volume: parseFloat(volumeSlider.value),
        html5: false,
        onplay: () => {
            setupEqualizer();
            animateEqualizer();
            startTrackTimer(songSrc);
        },
        onload: () => updateDuration(),
        onend: handleTrackEnd,
        onpause: stopTrackTimer,
        onstop: stopTrackTimer,
    });
}

function loadRadio(radioSrc) {
    console.log("Loading radio:", radioSrc);
    resetPlayer();

    if (sound) {
        sound.stop();
        sound.unload();
        cancelAnimationFrame(animationFrame);
    }

    sound = new Howl({
        src: [radioSrc],
        volume: parseFloat(volumeSlider.value),
        html5: true, 
        onplay: () => {
            setupEqualizer();
            animateEqualizer();
        },
        onstop: stopTrackTimer,
        onpause: stopTrackTimer,
    });

    sound.play();
    updatePlayPauseIcon(true);
}

function resetPlayer() {
    document.getElementById('currentTime').textContent = "0:00";
    document.getElementById('seekBar').value = 0;
    document.getElementById('lyricsDisplay').textContent = "";
    updatePlayPauseIcon(false);
    isPlaying = false;
}

volumeSlider.addEventListener('input', function() {
    if (sound) {
        sound.volume(parseFloat(volumeSlider.value));
    }
});

function setupEqualizer() {
    analyser = Howler.ctx.createAnalyser();
    bufferLength = analyser.frequencyBinCount;
    dataArray = new Uint8Array(bufferLength);

    Howler.masterGain.disconnect();
    Howler.masterGain.connect(Howler.ctx.destination); 
    Howler.masterGain.connect(analyser); 
    analyser.fftSize = 2048;
}

// Equalizer
function animateEqualizer() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    analyser.getByteFrequencyData(dataArray);

    let barWidth = (canvas.width / bufferLength) * 2.5;
    let barHeight;
    let x = 0;

    for (let i = 0; i < bufferLength; i++) {
        barHeight = dataArray[i] / 2;
        ctx.fillStyle = 'purple';
        ctx.fillRect(x, canvas.height - barHeight, barWidth, barHeight);
        x += barWidth + 1;
    }

    animationFrame = requestAnimationFrame(animateEqualizer);
}

function updateDuration() {
    const duration = sound.duration();
    document.getElementById('songLength').textContent = formatTime(duration);
}

function updatePlayPauseIcon(isPlaying) {
    if (isPlaying) {
        playIcon.classList.remove('fa-play');
        playIcon.classList.add('fa-pause');
    } else {
        playIcon.classList.remove('fa-pause');
        playIcon.classList.add('fa-play');
    }
}

function handleTrackEnd() {
    clearInterval(updatedInterval);
    document.getElementById('seekBar').value = 0;
    document.getElementById('lyricsDisplay').textContent = "";
    updatePlayPauseIcon(false);
    isPlaying = false;
}

function startTrackTimer(songSrc) {
    updatedInterval = setInterval(() => {
        if (!isSeeking) {
            const currentTime = sound.seek();
            const duration = sound.duration();
            document.getElementById('seekBar').value = (currentTime / duration) * 100;
            document.getElementById('currentTime').textContent = formatTime(currentTime);
            updateLyrics(currentTime, songSrc);
        }
    }, 1000);
}

function stopTrackTimer() {
    clearInterval(updatedInterval);
}

function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds % 60).toString().padStart(2, '0');
    return `${minutes}:${secs}`;
}

function updateLyrics(currentTime, songSrc) {
    const songFileName = songSrc.split('/').pop();
    const currentLyrics = lyricsData[songFileName] || [];

    let previousLyric = { text: "" };
    let currentLyric = { text: "" };
    let nextLyric = { text: "" };

    for (let i = 0; i < currentLyrics.length; i++) {
        if (currentLyrics[i].time <= currentTime) {
            previousLyric = currentLyric;
            currentLyric = currentLyrics[i];
            nextLyric = currentLyrics[i + 1] || { text: "" };
        } else {
            break;
        }
    }

    document.getElementById('previousLyric').textContent = previousLyric.text;
    document.getElementById('currentLyric').textContent = currentLyric.text;
    document.getElementById('nextLyric').textContent = nextLyric.text;
}

document.querySelectorAll('ul li').forEach(item => {
    item.addEventListener('click', function() {
        const songSrc = item.getAttribute('data-song');
        const isRadio = item.getAttribute('data-radio');

        if (isRadio === 'true') {
            loadRadio(songSrc);
        } else {
            loadSong(songSrc);
        }
    });
});

const playPauseButton = document.getElementById('playbtn');
const playIcon = document.getElementById('playpauseIcon');

playPauseButton.addEventListener('click', function() {
    if (sound) {
        if (isPlaying) {
            sound.pause();
            updatePlayPauseIcon(false);
        } else {
            sound.play();
            updatePlayPauseIcon(true);
        }
        isPlaying = !isPlaying;
    } else {
        alert("No song or radio selected.");
    }
});

const seekBar = document.getElementById('seekBar');
seekBar.addEventListener('input', function() {
    if (sound) {
        isSeeking = true;
        const duration = sound.duration();
        const newTime = (seekBar.value / 100) * duration;
        document.getElementById('currentTime').textContent = formatTime(newTime);
    }
});

seekBar.addEventListener('change', function() {
    if (sound) {
        const duration = sound.duration();
        const newTime = (seekBar.value / 100) * duration;
        sound.seek(newTime);
        isSeeking = false;
    }
});

loadSong("../audio/supernova.mp3");
