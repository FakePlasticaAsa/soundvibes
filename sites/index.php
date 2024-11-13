<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] !== true) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

//echo "Welcome, " . $_SESSION['username'] . "! Your role is: " . $_SESSION['role'] . " and your email is: " . $_SESSION['email'];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Player</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../script/template.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.3/howler.min.js"></script>
    
</head>

<body class="bg-neutral-800">
<header class="bg-neutral-900 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <a class="flex justify-center"> 
        <img src="../imgs/logoweb.png" class="w-16 h-16 rounded-lg mb-6">
        <h1 class="text-3xl font-bold mt-1 ml-2">Supernatural</h1>
        </a>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="index.php" class="hover:text-pink-300">Home</a></li>
                <li><a href="about.html" class="hover:text-pink-300">About</a></li>
                <li><a href="contact.html" class="hover:text-pink-300">Contact</a></li>
                <?php
    // Check if the user is logged in and has the 'admin' role
    if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true && $_SESSION['role'] === 'admin') {
        // Show this <li> only for admin users
        echo '<li><a href="admin-panel.php" class="hover:text-pink-300">Admin Dashboard</a></li>';
    }
    ?>
                <li><a href="logout.php" class="hover:text-pink-300">Logout</a></li>
                </ul>
        </nav>

    </div>
</header>
    <main class="flex flex-grow container mx-auto p-4">
        <!-- Apartado para el reproductor básico -->
        <div class="flex-grow bg-slate-200 p-4 mx-4 rounded shadow bg-opacity-50">
            <!-- Reproductor básico -->
            <div class="mt-6">
                <div class="flex flex-col items-center">
                    <!-- Imagen del álbum -->
                     <h1 id="songTitle" class="mb-2.5">Supernova - Aespa</h1>
                    <img id="album-cover" src="../imgs/supernova.jpg" alt="Imagen del álbum" class="w-96 h-96 object-cover rounded-lg shadow-md">

                    <!-- Controles del reproductor -->
                    <div class="w-full mt-4">
                        <!-- Botones de control -->
                        <div class="flex justify-around mb-2">
                            <button class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i class="fas fa-random"></i> <!-- Botón Shuffle -->
                            </button>
                            <button class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i class="fas fa-backward"></i> <!-- Botón Atrás -->
                            </button>
                            <button id="playbtn" class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i id="playpauseIcon" class="fa fa-play w-6"></i> <!-- Botón Play/Pause -->
                            </button>
                            <button class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i class="fas fa-forward"></i> <!-- Botón Siguiente -->
                            </button>
                            <button class="text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i class="fas fa-redo-alt"></i> <!-- Botón Repetir -->
                            </button>
                        </div>

                        <input id="seekBar" type="range"  max="100" value="0" class="w-full">
                        <div class="flex justify-between text-sm mt-1 mb-5">
                            <span id="currentTime">0:00</span>
                            <span id="songLength"></span> 
                        </div>
                    </div>
                </div>
            </div>

            <div id="lyricsContainer" class="relative flex items-center justify-center p-6 text-white">
                <!-- Equalizer Background -->
                <canvas id="equalizer" class="bg-black absolute inset-0 z-0 w-full h-full rounded-lg"></canvas>
                
                <!-- Dark Background Blur -->
                <div class="absolute inset-0 bg-cover bg-center blur-sm" style="background-color: black; opacity: 0.5;"></div>
                
                <!-- Lyrics -->
                <div class="relative z-10 text-center">
                    <h2 class="text-xl font-bold mt-6" style="min-height: 100px;">Lyrics</h2>
                    <p id="lyricsDisplay" class="mt-2 text-lg" style="min-height: 100px; margin-bottom: 50px; display: none;">The lyrics will show up right here.</p>
                    <p id="previousLyric" class="mt-2 text-lg" style="opacity: 0.6;">Previous lyric will show here</p>
                    <p id="currentLyric" class="mt-2 text-lg font-bold" style="margin-top: 10px;">The current lyric will show here</p>
                    <p id="nextLyric" class="mt-2 text-lg" style="opacity: 0.6; margin-bottom: 50px;">Next lyric will show here</p>
                </div>
            </div>
            <div class="slider-container">
                <label for="volumeSlider">Volume</label>
                <input type="range" id="volumeSlider" class="slider" min="0" max="1" step="0.01" value="0.5">
            </div>
            
            <div class="slider-container">
                <label for="rateSlider">Playback Rate</label>
                <input type="range" id="rateSlider" class="slider" min="0.5" max="2" step="0.1" value="1">
            </div>
            
            <div class="slider-container">
                <label for="panSlider">Panning (Left-Right)</label>
                <input type="range" id="panSlider" class="slider" min="-1" max="1" step="0.1" value="0">
            </div>
            
            <div class="radio-container">
                <h2>Available Radio Stations</h2>
                <ul>
                    <li data-song="https://stream.radioparadise.com/mp3-128" data-radio="true">98.8 - KISS FM Berlin</li>
                    <li data-song="http://media-ice.musicradio.com/ClassicFMMP3" data-radio="true">100-102 - Classic FM</li>
                </ul>
                
            </div>
            

            
        </div>

        <!-- Canciones -->
        <aside class="w-1/4 bg-slate-200 p-4 rounded shadow bg-opacity-50">
            <h2 class="text-xl font-bold">Playlist</h2>
            <ul class="mt-2">
                <li data-song="../audio/supernova.mp3" data-album="../imgs/supernova.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Supernova - Aespa</li>
                <li data-song="../audio/whiplash.mp3" data-album="../imgs/whiplash.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Whiplash - Aespa</li>                
                <li data-song="../audio/kill-it.mp3" data-album="../imgs/whiplash.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Kill it - Aespa</li>
                <li data-song="../audio/zoomzoom.mp3" data-album="../imgs/zoom.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Zoom Zoom - Aespa</li>
                <li data-song="../audio/illusion.mp3" data-album="../imgs/illusion.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Illusion - Aespa</li>
                <li data-song="../audio/drama.mp3" data-album="../imgs/drama.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Drama - Aespa</li>
                <li data-song="../audio/spark.mp3" data-album="../imgs/spark.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Spark (WINTER SOLO) - Aespa</li>
                <li data-song="../audio/kida.mp3" data-album="../imgs/kida.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Everything In Its Right Place - Radiohead</li>
                <li data-song="../audio/hikousen.mp3" data-album="../imgs/chou.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Hikousen - Lily Chou-Chou</li>
                <li data-song="../audio/kingslayer.mp3" data-album="../imgs/kingslayer.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Kingslayer (feat. BABYMETAL) - Bring Me The Horizon</li>
                <li data-song="../audio/hell-is-here.mp3" data-album="../imgs/cryalot.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Hell Is Here - Cryalot</li>
                <li data-song="../audio/1-800-hot-n-fun.mp3" data-album="../imgs/lesserafim.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">1-800-hot-n-fun - LE SSERAFIM</li>
                <li data-song="../audio/xerces.mp3" data-album="../imgs/xerces.jpg" class="hover:bg-gray-300 p-2 cursor-pointer">Xerces - Deftones</li>
            </ul>
        </aside>
    </main>
    <footer id="footer"></footer>
    <script src="../script/lyricsData.js"></script>
    <script src="../script/albumCovers.js"></script>
    <script src="../script/musicplayer.js"></script>
</body>
</html>