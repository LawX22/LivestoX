<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/details-page.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <h1>Main Content</h1>
            <!-- <p>This section takes 80% of the screen width.</p> -->
        </div>
        <div class="right-side">
            <h1>Sidebar</h1>
            <div class="chatbox">
                <p>Send Farmer a Message</p>
                <div class="chat-messages" id="chat-messages">
                    <!-- Messages will appear here -->
                </div>
                <div class="chat-input">
                    <input type="text" placeholder="Type a message..." id="chat-input-field">
                    <button onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function sendMessage() {
            const inputField = document.getElementById('chat-input-field');
            const message = inputField.value.trim();
            if (message) {
                const messagesContainer = document.getElementById('chat-messages');
                const newMessage = document.createElement('div');
                newMessage.className = 'message';
                newMessage.textContent = message;
                messagesContainer.appendChild(newMessage);
                inputField.value = '';
            }
        }
    </script>
</body>
</html>
