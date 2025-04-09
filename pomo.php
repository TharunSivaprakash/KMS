<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pomodoro Timer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .timer {
            font-size: 50px;
            margin: 20px 0;
        }
        .status {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        button {
            font-size: 16px;
            padding: 10px 15px;
            margin: 5px;
            cursor: pointer;
        }
        .blocked-sites {
            margin-top: 30px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            display: inline-block;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Pomodoro Timer</h1>
    <p class="status">Work Session</p>
    <p class="timer">25:00</p>
    <button onclick="startTimer()">Start</button>
    <button onclick="pauseTimer()">Pause</button>
    <button onclick="resetTimer()">Reset</button>

    <div class="blocked-sites">
        <h3>Blocked Websites (during work sessions)</h3>
        <ul id="blockedSitesList">
            <li>facebook.com</li>
            <li>twitter.com</li>
            <li>instagram.com</li>
            <li>youtube.com</li>
            <li>reddit.com</li>
        </ul>
        <div>
            <input type="text" id="newSite" placeholder="Add website to block">
            <button onclick="addSite()">Add Site</button>
        </div>
    </div>

    <script>
        let time = 25 * 60;
        let breakTime = 5 * 60;
        let isRunning = false;
        let isBreak = false;
        let timerInterval;
        let blockedSites = ['facebook.com', 'twitter.com', 'instagram.com', 'youtube.com', 'reddit.com'];
        let isBlockingEnabled = false;

        // Initialize blocked sites list
        function updateBlockedSitesList() {
            const listElement = document.getElementById('blockedSitesList');
            listElement.innerHTML = '';
            blockedSites.forEach(site => {
                const li = document.createElement('li');
                li.textContent = site;
                const removeBtn = document.createElement('button');
                removeBtn.textContent = '×';
                removeBtn.style.marginLeft = '10px';
                removeBtn.style.padding = '2px 5px';
                removeBtn.onclick = () => removeSite(site);
                li.appendChild(removeBtn);
                listElement.appendChild(li);
            });
        }

        function addSite() {
            const newSiteInput = document.getElementById('newSite');
            const site = newSiteInput.value.trim().toLowerCase();
            if (site && !blockedSites.includes(site)) {
                blockedSites.push(site);
                updateBlockedSitesList();
                newSiteInput.value = '';
            }
        }

        function removeSite(site) {
            blockedSites = blockedSites.filter(s => s !== site);
            updateBlockedSitesList();
        }

        // Website blocking functions
        function enableBlocking() {
            isBlockingEnabled = true;
            console.log("Blocking enabled for:", blockedSites);
            // In a Chrome extension, you would use chrome.webRequest API here
            // For this demo, we'll just show alerts
        }

        function disableBlocking() {
            isBlockingEnabled = false;
            console.log("Blocking disabled");
            // In a Chrome extension, you would remove the webRequest listener
        }

        function checkUrl(url) {
            return blockedSites.some(site => url.includes(site));
        }

        // Timer functions
        function updateTimerDisplay() {
            const minutes = Math.floor(time / 60).toString().padStart(2, '0');
            const seconds = (time % 60).toString().padStart(2, '0');
            document.querySelector('.timer').innerText = `${minutes}:${seconds}`;
        }

        function startTimer() {
            if (!isRunning) {
                isRunning = true;
                if (!isBreak) {
                    enableBlocking();
                } else {
                    disableBlocking();
                }
                
                timerInterval = setInterval(() => {
                    if (time > 0) {
                        time--;
                    } else {
                        clearInterval(timerInterval);
                        isRunning = false;
                        isBreak = !isBreak;
                        const statusElement = document.querySelector('.status');
                        statusElement.innerText = isBreak ? 'Break Time' : 'Work Session';
                        
                        if (isBreak) {
                            disableBlocking();
                            alert("Break time! Websites are now unblocked.");
                        } else {
                            enableBlocking();
                            alert("Work session started! Blocking distracting websites.");
                        }
                        
                        time = isBreak ? breakTime : 25 * 60;
                        startTimer();
                    }
                    updateTimerDisplay();
                }, 1000);
            }
        }

        function pauseTimer() {
            clearInterval(timerInterval);
            isRunning = false;
            disableBlocking();
        }

        function resetTimer() {
            clearInterval(timerInterval);
            isRunning = false;
            isBreak = false;
            time = 25 * 60;
            document.querySelector('.status').innerText = 'Work Session';
            disableBlocking();
            updateTimerDisplay();
        }

        // Initialize
        updateTimerDisplay();
        updateBlockedSitesList();

        // Note: For actual website blocking, this would need to be implemented as a Chrome extension
        // using the chrome.webRequest API to block requests to these sites during work sessions.
        // This demo shows the UI and logic but can't actually block sites in a standalone HTML page.
    </script>
</body>
</html>