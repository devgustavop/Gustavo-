<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendador de Músicas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        #playlist-container {
            margin-bottom: 20px;
        }
        #playlist-container input[type="text"] {
            margin-bottom: 10px;
            width: 100%;
            padding: 5px;
        }
        #playlist-container button {
            padding: 5px 10px;
        }
        #schedule-container {
            margin-bottom: 20px;
        }
        #schedule-container table {
            width: 100%;
            border-collapse: collapse;
        }
        #schedule-container th, #schedule-container td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        #schedule-container th {
            background-color: #f2f2f2;
        }
        #schedule-container button {
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    
<h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>
    <p>This is the home page.</p>
    <p><a href="logout.php">Logout</a></p>





    <h1>Agendador de Músicas</h1>

    <div id="playlist-container">
        <h2>Playlist</h2>
        <input type="text" id="youtube-url" placeholder="Cole a URL do YouTube aqui">
        <button onclick="addToPlaylist()">Adicionar à Playlist</button>
        <ul id="playlist"></ul>
    </div>

    <div id="schedule-container">
        <h2>Agendar Músicas</h2>
        <table>
            <thead>
                <tr>
                    <th>Hora</th>
                    <th>Música</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody id="schedule-body"></tbody>
        </table>
        <button onclick="schedule()">Agendar</button>
    </div>

    <script>
        let playlist = [];

        function addToPlaylist() {
            const youtubeUrl = document.getElementById('youtube-url').value;
            const videoId = getYoutubeVideoId(youtubeUrl);
            if (videoId) {
                playlist.push(videoId);
                renderPlaylist();
            } else {
                alert('URL do YouTube inválida!');
            }
        }

        function getYoutubeVideoId(url) {
            const regex = /[?&]([^=#]+)=([^&#]*)/g;
            let match;
            while (match = regex.exec(url)) {
                if (match[1] === 'v') {
                    return match[2];
                }
            }
            return null;
        }

        function renderPlaylist() {
            const playlistElement = document.getElementById('playlist');
            playlistElement.innerHTML = '';
            playlist.forEach(videoId => {
                const listItem = document.createElement('li');
                listItem.textContent = `https://www.youtube.com/watch?v=${videoId}`;
                playlistElement.appendChild(listItem);
            });
        }

        function schedule() {
            const scheduleBody = document.getElementById('schedule-body');
            scheduleBody.innerHTML = '';
            playlist.forEach((videoId, index) => {
                const time = prompt(`Em que horário você quer que a música ${index + 1} toque? (formato 24 horas, ex: 08:00)`);
                if (time) {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${time}</td>
                        <td>https://www.youtube.com/watch?v=${videoId}</td>
                        <td><button onclick="play('${videoId}')">Tocar Agora</button></td>
                    `;
                    scheduleBody.appendChild(row);
                    const [hours, minutes] = time.split(':').map(Number);
                    const now = new Date();
                    const scheduleTime = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes);
                    if (scheduleTime > now) {
                        setTimeout(() => play(videoId), scheduleTime - now);
                    } else {
                        alert(`Horário ${time} já passou para a música ${index + 1}!`);
                    }
                }
            });
        }

        function play(videoId) {
            const playerDiv = document.getElementById('youtube-player');
            playerDiv.innerHTML = `<iframe width="560" height="315" src="https://www.youtube.com/embed/${videoId}?autoplay=1" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>`;
        }
    </script>

    <div id="youtube-player"></div>
</body>
</html>

