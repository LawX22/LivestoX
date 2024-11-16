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
                    <input type="hidden" id="uid-input">
                    <input type="hidden" id="id-input">
                    <input type="text" placeholder="Type a message..." id="chat-input-field">
                    <button onclick="sendMessage()">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
        const uid = urlParams.get("uid");
        const id = urlParams.get("id");

        if (uid) {
            document.getElementById("uid-input").value = uid;
        }

        if (id) {
            document.getElementById("id-input").value = id;
        }

        document.getElementById("chat-input-field").value = "Hello World";

        function sendMessage() {
            if (uid === id ) {
                window.location.href = '../../Frontend/Farmer/message';
            } else {
                const uid = document.getElementById("uid-input").value;
                const id = document.getElementById("id-input").value;
                const message = document.getElementById("chat-input-field").value;

                if (!message.trim()) {
                    alert("Please type a message.");
                    return;
                }

                const data = {
                    sender: uid,
                    receiver: id,
                    initial: message
                };

                fetch('../../Backend/chat/start_chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                    alert('Message sent successfully!');
                    document.getElementById("chat-input-field").value = '';
                })
                .catch((error) => {
                    console.error('Error:', error);
                    alert('Error sending message.');
                });
            }
        }

    </script>
</body>
</html>
